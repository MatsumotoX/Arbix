<?php

namespace App\Http\Controllers\HRs\Users;

use App\Http\Controllers\Line\FlexMessageController;
use App\Http\Controllers\Properties\PropertyController;
use App\Model\HRS\Users\HU_การลา;
use Illuminate\Http\Request;
use View;
use JavaScript;
use Line;

class LeaveController extends PropertyController
{
	public function createLeave(Request $request)
	{

		if (count($request->query()) > 0)
		{
			$user = $this->id::find($request->user_id);

		} else
		{

			$user = auth()->user()->user;
		}

		$userInfo['value'] = $user->ชื่อ[0]->value . ' ' . $user->สกุล[0]->value;
		$userInfo['id']    = $user->id;
		$type              = $this->makeSelectSingleOption(['ลาป่วย', 'ลากิจ', 'ลาพักร้อน']);

		JavaScript::put([
			'userInfo' => $userInfo,
			'type'     => $type
		]);

		return View::make('hrs.users.leaves.create');
	}

	public function storeLeave(Request $request)
	{

		$validatedData = $request->validate([
			'value'     => 'required',
			'leaveType' => 'required',
			'leaveDate' => 'required',
			'reason'    => 'required',
		]);

		$this->data = $request->all();

		$this->splitDateRange('leaveDate', 'leaveFrom', 'leaveTo');

		$this->formatFormData($this->mainDirectory, $this->subDirectory, 'การลา');

		$model = $this->getModelPath($this->mainDirectory, $this->subDirectory, 'การลา');

		$reportPerson              = $this->id::find($request->user_id)->ผู้บังคับบัญชา[0];
		$this->data['reportTo_id'] = $reportPerson->relation_id;

		$model::create($this->data);

		$this->sendLeave($reportPerson->relation_id);

		return ['message' => 'Submitted to ' . $reportPerson->value];

	}

	public function sendLeave($reportId)
	{
		$leave = $this->getModelPath('HR', 'User', 'การลา')::where('reportTo_id', $reportId)->where('isActive', 1)->get()->toArray();

		$destination = $this->getModelPath('HR', 'User', 'LineID')::where('user_id', $reportId)->first()->value;

		if (count($leave) > 0)
		{
			$line = new FlexMessageController();
			$flex = [];

			foreach ($leave as $key => $left)
			{
				$flex[$key] = $line->buildFlex(2, $left['id'], true);
			}

			$line->sendCarouselManual($flex, 'ขออนุมัติการลาหยุด', $destination);
		}

	}

	public function testLeave()
	{
		$this->leaveGrant(1);
	}

	public function leaveGrant($id)
	{
		$leave = $this->getModelPath('HR', 'User', 'การลา')::find($id);

		if ($leave->isActive == 1)
		{
			Line::pushText($leave->user->LineId[0]->value, 'คำขอลาของคุณได้รับการอนุมัติแล้ว');

			$leave->approve  = 1;
			$leave->isActive = 0;
			$leave->save();

			Line::pushText($leave->reportTo->LineId[0]->value, 'Granted');
			$this->sendLeave($leave->reportTo->id);
		}

	}

	public function leaveDeny($id)
	{
		$leave = $this->getModelPath('HR', 'User', 'การลา')::find($id);

		if ($leave->isActive == 1)
		{
			Line::pushText($leave->user->LineId[0]->value, 'คำขอลาของคุณได้ถูกปฏิเสธ');

			$leave->approve  = 0;
			$leave->isActive = 0;
			$leave->save();

			Line::pushText($leave->reportTo->LineId[0]->value, 'Denied');
			$this->sendLeave($leave->reportTo->id);
		}

	}

}
