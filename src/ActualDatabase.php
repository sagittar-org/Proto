<?php
namespace pieni\Proto;

class ActualDatabase implements \pieni\Sync\Driver
{
	public static $columns = [
		'tables' => [],
		'references' => ['table', 'column', 'referenced_table', 'referenced_column'],
		'er_diagram' => ['value'],
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
		foreach (array_keys($data['tables']) as $table) {
			$lines[] = "{$table};";
		}
		foreach ($data['references'] as $reference_name => $reference) {
			$lines[] = "{$reference['referenced_table']}->{$reference['table']} [label='{$reference_name}'];";
		}
		$er_diagram['dot']['value'] = "digraph er_diagram {\n\trankdir=LR;\n\t".implode("\n\t", $lines)."\n}\n";
		$er_diagram['base64']['value'] = 'data:image/svg+xml;base64,'.shell_exec("echo '{$er_diagram['dot']['value']}' | dot -Tsvg | base64 -w0");
		return [
			'tables' => $data['tables'],
			'references' => $data['references'],
			'er_diagram' => $er_diagram,
		];
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
