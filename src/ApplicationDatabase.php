<?php
namespace pieni\Proto;

class ApplicationDatabase implements \pieni\Sync\Driver
{
	public static $columns = [
		'tables' => [],
		'references' => [],
	];

	public function __construct($params = [])
	{
		$this->actual_database = $params['actual_database'];
	}

	public function mtime($name = '')
	{
		return 0;
	}

	public function get($name = '')
	{
		$actual_database = $this->actual_database->get();
		foreach ($actual_database['tables'] as $table_name => $table) {
			$data['tables'][$table_name] = [];
		}
		foreach ($actual_database['references'] as $reference_name => $reference) {
			$data['references'][$reference_name] = [];
		}
		return [
			'tables' => $data['tables'],
			'references' => $data['references'],
		];
	}

	public function put($data, $mtime, $name = '')
	{
	}
}
