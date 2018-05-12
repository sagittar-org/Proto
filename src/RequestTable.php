<?php
namespace pieni\Proto;

class RequestTable implements \pieni\Sync\Driver
{
	public static $columns = [
		'primary_keys' => [],
		'actions' => [],
		'columns' => ['expr', 'type', 'nullable', 'default', 'extra'],
	];

	public function __construct($params = [])
	{
		$this->request_database = $params['request_database'];
		$this->actual_table = end($params['application_table']->drivers)->actual_table;
		$this->application_table = $params['application_table'];
	}

	public function mtime($name = '')
	{
		list($actor, $alias, $action) = explode('.', $name);
		return max(
			$this->request_database->mtime($actor),
			$this->actual_table->mtime(''),
			$this->application_table->mtime($alias)
		);
	}

	public function get($name = '')
	{
		list($actor, $alias, $action) = explode('.', $name);
		$request_database = $this->request_database->get($actor);
		if (in_array($alias, array_keys($request_database['tables']))) {
			$name = $alias;
		} elseif (in_array($alias, array_keys($request_database['references']))) {
			$name = $request_database['references'][$alias]['table'];
		} else {
			trigger_error("Unknown alias '{$alias}'", E_USER_ERROR);
		}
		$actual_table = $this->actual_table->get($name);
		$application_table = $this->application_table->get($name);
		$data['primary_keys'] = $actual_table['primary_keys'];
		$data['actions'] = $application_table['actions'];
		foreach ($application_table['columns'] as $column_name => $column) {
			if (isset($actual_table['columns'][$column_name])) {
				$data['columns'][$column_name] = [
					'expr' => '',
					'type' => $actual_table['columns'][$column_name]['type'],
					'nullable' => $actual_table['columns'][$column_name]['nullable'],
					'default' => $actual_table['columns'][$column_name]['default'],
					'extra' => $actual_table['columns'][$column_name]['extra'],
				];
			} else {
				$data['columns'][$column_name] = [
					'expr' => $application_table['columns'][$column_name]['expr'],
					'type' => '',
					'nullable' => '',
					'default' => '',
					'extra' => '',
				];
			}
		}
		foreach ($application_table['unset'] as $unset) {
			if (preg_match("/{$unset['actor']}/", $actor) && preg_match("/{$unset['alias']}/", $alias) && preg_match("/{$unset['action']}/", $action)) {
				unset($data[$unset['from']][$unset['unset']]);
			}
		}
		return $data;
	}

	public function put($data, $mtime, $name = '')
	{
	}
}
