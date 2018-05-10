<?php
namespace pieni\Proto;

class ApplicationTable implements \pieni\Sync\Driver
{
	public static $columns = [
		'actions' => [],
		'columns' => ['expr'],
		'unset' => ['unset', 'from', 'actor', 'alias', 'action'],
	];

	public function __construct($params = [])
	{
		$this->actual_table = $params['actual_table'];
	}

	public function mtime($name = '')
	{
		return 0;
	}

	public function get($name = '')
	{
		$actual_table = $this->actual_table->get($name);
		foreach ($actual_table['columns'] as $column_name => $column) {
			$columns[$column_name] = [
				'expr' => '',
			];
		}
		return [
			'actions' => [
				'index' => [],
				'view' => [],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
			'columns' => $columns,
			'unset' => [],
		];
	}

	public function put($data, $mtime, $name = '')
	{
	}
}
