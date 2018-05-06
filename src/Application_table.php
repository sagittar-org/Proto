<?php
namespace pieni\Proto;

class Application_table implements \pieni\Sync\Driver
{
	public static $columns = [
		'actions' => [],
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
		return [
			'actions' => [
				'index' => [],
				'view' => [],
			],
			'unset' => [],
		];
	}

	public function put($data, $mtime, $name = '')
	{
	}
}
