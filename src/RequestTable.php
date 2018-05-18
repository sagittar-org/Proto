<?php
namespace pieni\Proto;

class RequestTable implements \pieni\Sync\Driver
{
	public static $columns = [
		'actions' => [],
		'primary_keys' => [],
		'columns' => ['expr', 'type', 'nullable', 'default', 'extra'],
	];

	public function __construct($params = [])
	{
		$this->request_database = $params['request_database'];
		$this->actual_table = $params['actual_table'];
		$this->application_table = $params['application_table'];
		$this->filter_table = $params['filter_table'];
	}

	public function mtime($name = '')
	{
		list($actor, $alias, $action) = explode('.', $name);
		return max(
			$this->request_database->mtime($actor),
			$this->actual_table->mtime(''),
			$this->application_table->mtime($alias),
			$this->filter_table->mtime($alias)
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
		$data['actions'] = $application_table['actions'];
		$data['primary_keys'] = $actual_table['primary_keys'];
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
		foreach (array_keys($this->filter_table->get($alias)['filters']) as $filter_name) {
			list($filter['actor'], $filter['alias'], $filter['action'], $filter['from'], $filter['id']) = explode('.', $filter_name);
			if (!in_array($filter['actor'], ['*', $actor])) continue;
			if (!in_array($filter['alias'], ['*', $alias])) continue;
			if (!in_array($filter['action'], ['*', $action])) continue;
			unset($data[$filter['from']][$filter['id']]);
		}
		return $data;
	}

	public function put($data, $mtime, $name = '')
	{
	}
}
