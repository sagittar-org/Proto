<?php
namespace pieni\Proto;

class FilterTable implements \pieni\Sync\Driver
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
		return [];
	}

	public function put($data, $mtime, $name = '')
	{
	}
}
