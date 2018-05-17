<?php
namespace pieni\Proto;

class ApplicationTable implements \pieni\Sync\Driver
{
	public static $columns = [
		'actors' => [],
		'aliases' => [],
		'actions' => [],
		'columns' => ['expr'],
	];

	public function __construct($params = [])
	{
		$this->config = $params['config'];
		$this->actual_database = $params['actual_database'];
		$this->actual_table = $params['actual_table'];
	}

	public function mtime($name = '')
	{
		return 0;
	}

	public function get($name = '')
	{
		$config = $this->config->get();
		$actual_database = $this->actual_database->get();
		foreach ($actual_database['references'] as $reference_name => $reference) {
			if ($reference['table'] !== $name) continue;
			$aliases[$reference_name] = $reference;
		}
		$actual_table = $this->actual_table->get($name);
		foreach ($actual_table['columns'] as $column_name => $column) {
			$columns[$column_name] = [
				'expr' => '',
			];
		}
		return [
			'actors' => $config['actors'],
			'aliases' => $aliases,
			'actions' => [
				'index' => [],
				'view' => [],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
			'columns' => $columns,
		];
	}

	public function put($data, $mtime, $name = '')
	{
	}
}
