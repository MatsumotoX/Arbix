<?php

namespace App\Blueprint;

use App\Migration;
use Artisan;
use Storage;
use Illuminate\Support\Str;

class GlobalBlueprint
{
	protected $content;


	public function rollback($mainDirectory, $subDirectory)
	{
		$response = Artisan::call('migrate:rollback', array('--path' => 'database/migrations/' . strtolower($this->getPluralName($mainDirectory)) . '/' . strtolower($this->getPluralName($subDirectory)), '--force' => true));

		return $response;
	}

	public function migrate($mainDirectory, $subDirectory)
	{
		$response = Artisan::call('migrate', array('--path' => 'database/migrations/' . strtolower($this->getPluralName($mainDirectory)) . '/' . strtolower($this->getPluralName($subDirectory)), '--force' => true));

		return $response;
	}

	public function addHasManyToRelation($modelName, $mainDirectory, $subDirectory, $mainProperty)
	{
		$abbreviate = $this->getAbbreviate($mainDirectory, $subDirectory);

		$modelName = $abbreviate . '_' . $modelName;

		$this->content = Storage::disk('model')->get($this->getPluralName($mainDirectory) . '\\' . $this->getPluralName($subDirectory) . '\\' . $abbreviate . '_Relation.php');

		$this->content = substr($this->content, 0, - 1);

		$this->addRelationship(strtolower(substr($modelName, 3)), 'hasMany', '\'App\Model\\' . $this->getPluralName($mainDirectory) . '\\' . $this->getPluralName($subDirectory) . '\\' . $modelName . '\'', $mainProperty . '_id');

		$this->closePhp();

		$response = Storage::disk('model')->put($this->getPluralName($mainDirectory) . '\\' . $this->getPluralName($subDirectory) . '\\' . $abbreviate . '_' . 'Relation.php', $this->content);

		return $response;
	}

	public function removeHasManyToRelation($modelName, $mainDirectory, $subDirectory)
	{
		$abbreviate = $this->getAbbreviate($mainDirectory, $subDirectory);

		$this->content = Storage::disk('model')->get($this->getPluralName($mainDirectory) . '\\' . $this->getPluralName($subDirectory) . '\\' . $abbreviate . '_Relation.php');

		$modelStartingPos = strpos($this->content, ' ' . strtolower(str_replace("_", '', $modelName)) . '()') - 16;

		$modelEndingPos = strpos($this->content, "}", $modelStartingPos);

		$modelFirstLinePos = strpos($this->content, "\n", $modelEndingPos);

		$newLineFormat = (($modelFirstLinePos - $modelEndingPos) * 2) + 1;

		$topString = substr($this->content, 0, $modelStartingPos);

		$bottomString = substr($this->content, $modelEndingPos + $newLineFormat);

		$finalString = $topString . $bottomString;

		$response = Storage::disk('model')->put($this->getPluralName($mainDirectory) . '\\' . $this->getPluralName($subDirectory) . '\\' . $abbreviate . '_' . 'Relation.php', $finalString);

		return $response;
	}

	public function addHasManyToUser($modelName, $mainDirectory, $subDirectory)
	{
		$abbreviate = $this->getAbbreviate($mainDirectory, $subDirectory);

		$modelName = $abbreviate . '_' . $modelName;

		$this->content = Storage::disk('model')->get('HRS\Users\HU_Relation.php');

		$this->content = substr($this->content, 0, - 1);

		$this->addRelationship(strtolower($modelName), 'hasMany', '\'App\Model\\' . $this->getPluralName($mainDirectory) . '\\' . $this->getPluralName($subDirectory) . '\\' . $modelName . '\'', 'createdBy_id');

		$this->closePhp();

		$response = Storage::disk('model')->put('HRS\Users\HU_Relation.php', $this->content);

		return $response;
	}

	public function removeHasManyToUser($modelName, $mainDirectory, $subDirectory)
	{
		$abbreviate = $this->getAbbreviate($mainDirectory, $subDirectory);

		$modelName = strtolower($abbreviate . '_' . $modelName);

		$this->content = Storage::disk('model')->get('HRS\Users\HU_Relation.php');

		$modelStartingPos = strpos($this->content, ' ' . strtolower($modelName) . '()') - 16;

		$modelEndingPos = strpos($this->content, "}", $modelStartingPos);

		$modelFirstLinePos = strpos($this->content, "\n", $modelEndingPos);

		$newLineFormat = (($modelFirstLinePos - $modelEndingPos) * 2) + 1;

		$topString = substr($this->content, 0, $modelStartingPos);

		$bottomString = substr($this->content, $modelEndingPos + $newLineFormat);

		$finalString = $topString . $bottomString;

		$response = Storage::disk('model')->put('HRS\Users\HU_Relation.php', $finalString);

		return $response;
	}

	public function createModel($modelName, $mainDirectory, $subDirectory, $mainProperty, $relation = null)
	{
		$abbreviate = $this->getAbbreviate($mainDirectory, $subDirectory);

		$modelName = $abbreviate . '_' . $modelName;

		$tableTitle = $this->getTableTitle($mainDirectory, $subDirectory);

		$this->setPhp();

		$this->addNamespace('App\Model\\' . $this->getPluralName($mainDirectory) . '\\' . $this->getPluralName($subDirectory));

		$this->addUseModel();

		$this->addClass($modelName, 'Model');

		$this->addTable($modelName, $tableTitle);

		$this->addGuarded();

		$this->addRelationship($mainProperty, 'belongsTo', '\'App\Model\\' . $this->getPluralName($mainDirectory) . '\\' . $this->getPluralName($subDirectory) . '\\' . $abbreviate . '_ID\'');

		if (!is_null($relation))
		{
			$this->addRelationship('relation', 'belongsTo', '\'' . $relation['path'] . '\'', 'relation_id');
		}

		$this->addRelationship('createdBy', 'belongsTo', '\'App\Model\HRS\Users\HU_ID\'', 'createdBy_id');

		$this->closePhp();

		$response = Storage::disk('model')->put($this->getPluralName($mainDirectory) . '\\' . $this->getPluralName($subDirectory) . '\\' . $modelName . '.php', $this->content);

		return $response;
	}

	public function deleteModel($modelName, $mainDirectory, $subDirectory)
	{
		$modelName = $this->getModelName($modelName, $mainDirectory, $subDirectory);

		$response = Storage::disk('model')->delete($this->getPluralName($mainDirectory) . '\\' . $this->getPluralName($subDirectory) . '\\' . $modelName . '.php');

		return $response;
	}

	public function createMigration($modelName, $mainProperty, $mainDirectory, $subDirectory, $hasDate = false, $relation = null, $tableType = 'string', $isUnique = false, $digit = null, $decimal = null)
	{
		$modelName = $this->getModelName($modelName, $mainDirectory, $subDirectory);

		$tableTitle = $this->getTableTitle($mainDirectory, $subDirectory);

		$this->setPhp();

		$this->useMigration();

		$this->addClass($this->getMigrationClass($modelName), 'Migration');

		$this->addUp($this->getTableName($modelName, $tableTitle), $tableType, $isUnique, $digit, $decimal, $hasDate, $relation, $mainProperty);

		$this->addDown($this->getTableName($modelName, $tableTitle));

		$this->closePhp();

		$response = Storage::disk('migration')->put(strtolower($this->getPluralName($mainDirectory)) . '/' . strtolower($this->getPluralName($subDirectory)) . '/2018_08_28_000000_create_' . $this->getPluralName(str_replace('_', '', $modelName)) . '_table.php', $this->content);

		return $response;
	}

	public function deleteMigration($modelName, $mainDirectory, $subDirectory)
	{
		$modelName = $this->getModelName($modelName, $mainDirectory, $subDirectory);

		$response = Storage::disk('migration')->delete(strtolower($this->getPluralName($mainDirectory)) . '/' . strtolower($this->getPluralName($subDirectory)) . '/2018_08_28_000000_create_' . $this->getPluralName(str_replace('_', '', $modelName)) . '_table.php');

		return $response;
	}

	public function modifyMigration($modelName, $mainDirectory, $subDirectory)
	{
		$modelName = $this->getModelName($modelName, $mainDirectory, $subDirectory);

		$table = Migration::where('migration', '2018_08_28_000000_create_' . $this->getPluralName(str_replace('_', '', $modelName)) . '_table')->first();

		$table->batch = 999999999;

		$table->timestamps = false;

		$response = $table->save();

		return $response;
	}

//	---------------------------------Style-----------------------------------------

	protected function skipLine(): void
	{
		$this->content .= "\r\n\r\n";
	}

	protected function breakLine(): void
	{
		$this->content .= "\r\n";
	}

	protected function addNamespace($namespace): void
	{
		$this->content .= "namespace " . $namespace . ";";
		$this->skipLine();
	}

	protected function useModel(): void
	{
		$this->content .= 'use Illuminate\Database\Eloquent\Model;';
		$this->skipLine();
	}

	protected function useMigration(): void
	{
		$this->content .= 'use Illuminate\Support\Facades\Schema;';
		$this->breakLine();
		$this->content .= 'use Illuminate\Database\Schema\Blueprint;';
		$this->breakLine();
		$this->content .= 'use Illuminate\Database\Migrations\Migration;';
		$this->skipLine();
	}

	/**
	 * @param $modelName
	 * @param $extends
	 */
	protected function addClass($modelName, $extends): void
	{
		$this->content .= 'class ' . $modelName . ' extends ' . $extends;
		$this->breakLine();
		$this->content .= '{';
		$this->breakLine();
	}

	/**
	 * @param $modelName
	 * @param $tableTitle
	 */
	protected function addTable($modelName, $tableTitle): void
	{
		$this->addTab();
		$this->content .= 'protected $table = \'' . $this->getTableName($modelName, $tableTitle) . '\';';
		$this->skipLine();
	}

	public function getTableName($modelName, $tableTitle)
	{
		return $tableTitle . '_' . $this->getPluralName(substr($modelName, 3));
	}

	protected function addGuarded(): void
	{
		$this->addTab();
		$this->content .= 'protected $guarded = [];';
		$this->skipLine();
	}

	/**
	 * @param $functionName
	 * @param $relationship
	 * @param $path
	 * @param $foreignKey
	 */
	protected function addRelationship($functionName, $relationship, $path, $foreignKey = null): void
	{
		$this->addTab();
		$this->content .= 'public function ' . $functionName . '()';
		$this->breakLineWithTabs();
		$this->content .= '{';
		$this->breakLineWithTabs(2);
		if (is_Null($foreignKey))
		{
			$this->content .= 'return $this->' . $relationship . '(' . $path . ')->orderBy(\'id\', \'desc\');';
		} else
		{
			$this->content .= 'return $this->' . $relationship . '(' . $path . ', \'' . $foreignKey . '\')->orderBy(\'id\', \'desc\');';
		}
		$this->breakLineWithTabs();
		$this->content .= '}';
		$this->skipLine();
	}

	protected function addUp($tableName, $tableType, $isUnique, $digit, $decimal, $hasDate, $relation, $mainProperty): void
	{
		switch ($tableType)
		{
			case 'file':
			case 'image':
			case 'phone':
			case 'select':
				$tableType = 'string';
				break;
		}
		$this->addTab();
		$this->content .= 'public function up()';
		$this->breakLineWithTabs();
		$this->content .= '{';
		$this->breakLineWithTabs(2);
		$this->content .= 'Schema::create(\'' . $tableName . '\', function (Blueprint $table) {';
		$this->breakLineWithTabs(3);
		$this->content .= '$table->increments(\'id\');';
		$this->breakLineWithTabs(3);
		$this->content .= '$table->integer(\'' . $mainProperty . '_id\');';
		$this->breakLineWithTabs(3);
		$this->content .= '$table->' . $tableType . '(\'value\'';
		if ($digit != null)
		{
			$this->content .= ', ';
			$this->content .= $digit;
			$this->content .= ', ';
			$this->content .= $decimal;
		}
		$this->content .= ')';
		if ($isUnique)
		{
			$this->content .= '->unique()';
		}

		$this->content .= ';';
		$this->breakLineWithTabs(3);
		$this->content .= '$table->boolean(\'isActive\')->default(1);';

		if ($hasDate == true)
		{
			$this->breakLineWithTabs(3);
			$this->content .= '$table->date(\'Date\');';
		}
		if (!is_null($relation))
		{
			$this->breakLineWithTabs(3);
			$this->content .= '$table->integer(\'relation_id\');';
		}
		$this->breakLineWithTabs(3);
		$this->content .= '$table->integer(\'createdBy_id\');';
		$this->breakLineWithTabs(3);
		$this->content .= '$table->timestamps();';
		$this->breakLineWithTabs(2);
		$this->content .= '});';
		$this->breakLineWithTabs();
		$this->content .= '}';
		$this->skipLine();
	}

	protected function addDown($tableName): void
	{
		$this->addTab();
		$this->content .= 'public function down()';
		$this->breakLineWithTabs();
		$this->content .= '{';
		$this->breakLineWithTabs(2);
		$this->content .= 'Schema::dropIfExists(\'' . $tableName . '\');';
		$this->breakLineWithTabs();
		$this->content .= '}';
		$this->skipLine();
	}

	protected function setPhp(): void
	{
		$this->content = '<?php';
		$this->skipLine();
	}

	/**
	 * @param $modelName
	 * @return mixed
	 */
	protected function getPluralName($modelName): string
	{
		$plural = str_replace('\\', '', Str::plural(class_basename($modelName)));

		return $plural;
	}

	/**
	 * @param $modelName
	 * @return string
	 */
	protected function getMigrationClass($modelName): string
	{
		return str_replace('_', '', 'Create' . Str::plural(class_basename($modelName)) . 'Table');
	}

	protected function breakLineWithTabs($tab = 1): void
	{
		$this->breakLine();
		for ($i = 0; $i < $tab; $i ++)
		{
			$this->addTab();
		}
	}

	protected function addTab(): void
	{
		$this->content .= "\t";
	}

	protected function closePhp(): void
	{
		$this->content .= '}';
	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @return string
	 */
	protected function getAbbreviate($mainDirectory, $subDirectory): string
	{
		$abbreviate = strtoupper(substr($mainDirectory, 0, 1) . substr($subDirectory, 0, 1));

		return $abbreviate;
	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @return string
	 */
	protected function getTableTitle($mainDirectory, $subDirectory): string
	{
		$tableTitle = strtolower($this->getPluralName($mainDirectory) . '_' . $this->getPluralName($subDirectory));

		return $tableTitle;
	}

	/**
	 * @param $modelName
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @return string
	 */
	protected function getModelName($modelName, $mainDirectory, $subDirectory): string
	{
		$abbreviate = $this->getAbbreviate($mainDirectory, $subDirectory);

		$modelName = $abbreviate . '_' . $modelName;

		return $modelName;
	}

	protected function addUseModel(): void
	{
		$this->content .= 'use Illuminate\\Database\\Eloquent\\Model;';

		$this->content .= $this->skipLine();
	}

}
