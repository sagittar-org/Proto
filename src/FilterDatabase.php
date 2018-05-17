<?php
namespace pieni\Proto;

class FilterDatabase implements \pieni\Sync\Driver
{
	public static $columns = [
		'filters' => ['actor', 'from', 'target_id'],
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
