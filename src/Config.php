<?php
namespace pieni\Proto;

class Config implements \pieni\Sync\Driver
{
	public static $columns = [
		'db' => ['value'],
		'actors' => [],
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
		$data['db'] = [
			'host' => ['value' => 'localhost'],
			'user' => ['value' => 'root'],
			'password' => ['value' => ''],
			'database' => ['value' => 'world'],
		];
		$data['actors'] = [
			'g' => [],
			'm' => [],
			'a' => [],
		];
		return $data;
	}

	public function put($data, $mtime, $name = '')
	{
	}
}
