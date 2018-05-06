<?php
namespace pieni\Proto;

class Actual_table implements \pieni\Sync\Driver
{
	public static $columns = [
		'primary_keys' => [],
		'columns' => ['type', 'nullable', 'default', 'extra'],
	];

	public function __construct($params = [])
	{
		$this->database = $params['database'];
		$this->db = $params['db'];
	}

	public function mtime($name = '')
	{
		return strtotime($this->db->query("
			SELECT `CREATE_TIME`
			FROM `information_schema`.`TABLES`
			WHERE `TABLE_SCHEMA` = '{$this->database}' AND `TABLE_NAME` = '{$name}'
		")->fetch_row()[0]);
	}

	public function get($name = '')
	{
		$data['primary_keys'] = $this->listToHash($this->db->query("
			SELECT
				`COLUMN_NAME`
			FROM `information_schema`.`COLUMNS`
			WHERE `TABLE_SCHEMA` = '{$this->database}' AND `TABLE_NAME` = '{$name}' AND `COLUMN_KEY` = 'PRI'
			ORDER BY `ORDINAL_POSITION` ASC
		")->fetch_all(MYSQLI_ASSOC), 'COLUMN_NAME');
		$data['columns'] = $this->listToHash($this->db->query("
			SELECT
				`COLUMN_NAME`,
				`COLUMN_TYPE` AS `type`,
				`IS_NULLABLE` AS `nullable`,
				IFNULL(`COLUMN_DEFAULT`, 'NULL') AS `default`,
				`EXTRA` AS `extra`
			FROM `information_schema`.`COLUMNS`
			WHERE `TABLE_SCHEMA` = '{$this->database}' AND `TABLE_NAME` = '{$name}'
			ORDER BY `ORDINAL_POSITION` ASC
		")->fetch_all(MYSQLI_ASSOC), 'COLUMN_NAME');
		return $data;
	}

	public function put($data, $mtime, $name = '')
	{
	}

	private function listToHash($list, $key)
	{
		foreach ($list as $row) {
			$row_name = $row[$key];
			unset($row[$key]);
			$hash[$row_name] = $row;
		}
		return $hash;
	}
}
