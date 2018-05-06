<?php
namespace pieni\Proto;

class ApplicationDatabase implements \pieni\Sync\Driver
{
	public static $columns = [
		'unset' => ['unset', 'from', 'actor']
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
		return ['unset' => []];
	}

	public function put($data, $mtime, $name = '')
	{
	}
}
