<?php
namespace pieni\Proto;

class Actual_database implements \pieni\Sync\Driver
{
	public static $columns = [
		'tables' => [],
		'references' => ['table', 'column', 'referenced_table', 'referenced_column'],
	];

	public function __construct($params = [])
	{
		$this->database = $params['database'];
		$this->db = $params['db'];
	}

	public function mtime($name = '')
	{
		return strtotime($this->db->query("
			SELECT MAX(`CREATE_TIME`)
			FROM `information_schema`.`TABLES`
			WHERE `TABLE_SCHEMA` = '{$this->database}'
		")->fetch_row()[0]);
	}

	public function get($name = '')
	{
		$data['tables'] = $this->listToHash($this->db->query("
			SELECT `TABLE_NAME`
			FROM `information_schema`.`TABLES`
			WHERE `TABLE_SCHEMA` = '{$this->database}'
			ORDER BY `TABLE_NAME` ASC
		")->fetch_all(MYSQLI_ASSOC), 'TABLE_NAME');
		$data['references'] = $this->listToHash($this->db->query("
			SELECT
				`CONSTRAINT_NAME`,
				`TABLE_NAME` AS `table`,
				`COLUMN_NAME` AS `column`,
				`REFERENCED_TABLE_NAME` AS `referenced_table`,
				`REFERENCED_COLUMN_NAME` AS `referenced_column`
			FROM `information_schema`.`KEY_COLUMN_USAGE`
			WHERE `TABLE_SCHEMA` = '{$this->database}' AND `REFERENCED_TABLE_NAME` IS NOT NULL
			ORDER BY `CONSTRAINT_NAME` ASC
		")->fetch_all(MYSQLI_ASSOC), 'CONSTRAINT_NAME');
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
