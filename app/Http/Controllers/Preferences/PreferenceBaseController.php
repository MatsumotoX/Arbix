<?php

namespace App\Http\Controllers\Preferences;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use GlobalBlueprint;

class PreferenceBaseController extends Controller
{
	public function createProperty($modelName, $mainDirectory, $subDirectory, $mainProperty, $tableType, $hasDate, $relation, $isUnique, $digit, $decimal)
	{
		GlobalBlueprint::createModel($modelName, $mainDirectory, $subDirectory, $mainProperty, $relation);
		GlobalBlueprint::createMigration($modelName, $mainProperty, $mainDirectory, $subDirectory, $hasDate, $relation, $tableType, $isUnique, $digit, $decimal);
		GlobalBlueprint::addHasManyToRelation($modelName, $mainDirectory, $subDirectory, $mainProperty);
		GlobalBlueprint::addHasManyToUser($modelName, $mainDirectory, $subDirectory);
		GlobalBlueprint::migrate($mainDirectory, $subDirectory);
	}

	public function destroyProperty($modelName, $mainDirectory, $subDirectory)
	{
		GlobalBlueprint::modifyMigration($modelName, $mainDirectory, $subDirectory);
		GlobalBlueprint::rollback($mainDirectory, $subDirectory);
		GlobalBlueprint::removeHasManyToRelation($modelName, $mainDirectory, $subDirectory);
		GlobalBlueprint::removeHasManyToUser($modelName, $mainDirectory, $subDirectory);
		GlobalBlueprint::deleteModel($modelName, $mainDirectory, $subDirectory);
		GlobalBlueprint::deleteMigration($modelName, $mainDirectory, $subDirectory);
	}


}
