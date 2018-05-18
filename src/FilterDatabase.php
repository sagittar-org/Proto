<?php
namespace pieni\Proto;

class FilterDatabase implements \pieni\Sync\Driver
{
	public static $columns = [
		'filters' => [],
	];

	public function __construct($params = [])
	{
	}

	public function mtime($name = '')
	{
		return 0;
	}

	public function get($name = '')
	{
		return [
			'filters' => [],
		];
	}

	public function put($data, $mtime, $name = '')
	{
	}
}
