<?php
namespace pieni\Proto;

class Config implements \pieni\Sync\Driver
{
	public static $columns = [
		'db' => ['value'],
		'languages' => [],
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
		if (function_exists('fallback')) {
			$fallback = fallback([g('packages'), ['sync/json'], ['config.json']], false);
			if ($fallback !== null) {
				return json_decode(file_get_contents($fallback), true);
			}
		}
		$data['db'] = [
			'host' => ['value' => 'localhost'],
			'user' => ['value' => 'root'],
			'password' => ['value' => ''],
			'database' => ['value' => 'pieni-tutorial'],
		];
		$data['languages'] = [
			'en' => [],
			'ja' => [],
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
