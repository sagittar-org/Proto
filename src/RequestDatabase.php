<?php
namespace pieni\Proto;

class RequestDatabase implements \pieni\Sync\Driver
{
	public static $columns = [
		'tables' => [],
		'references' => ['table', 'column', 'referenced_table', 'referenced_column'],
		'er_diagram' => ['value'],
	];

	public function __construct($params = [])
	{
		$this->actual_database = end($params['application_database']->drivers)->actual_database;
		$this->application_database = $params['application_database'];
	}

	public function mtime($name = '')
	{
		return max(
			$this->actual_database->mtime(),
			$this->application_database->mtime()
		);
	}

	public function get($name = '')
	{
		$actor = $name;
		$actual_database = $this->actual_database->get();
		$application_database = $this->application_database->get();
		foreach ($application_database['tables'] as $table_name => $table) {
			$data['tables'][$table_name] = $actual_database['tables'][$table_name];
		}
		foreach ($application_database['references'] as $reference_name => $reference) {
			$data['references'][$reference_name] = $actual_database['references'][$reference_name];
		}
		foreach ($application_database['unset'] as $unset) {
			if (preg_match("/{$unset['actor']}/", $actor)) {
				unset($data[$unset['from']][$unset['unset']]);
			}
		}
		foreach (array_keys($data['tables']) as $table) {
			$lines[] = "{$table};";
		}
		foreach ($data['references'] as $reference_name => $reference) {
			$lines[] = "{$reference['referenced_table']}->{$reference['table']} [label='{$reference_name}'];";
		}
		$er_diagram['dot']['value'] = "digraph er_diagram {\n\trankdir=LR;\n\t".implode("\n\t", $lines)."\n}\n";
		$er_diagram['base64']['value'] = 'data:image/svg+xml;base64,'.shell_exec("echo '{$er_diagram['dot']['value']}' | dot -Tsvg | base64 -w0");
		return [
			'tables' => $data['tables'],
			'references' => $data['references'],
			'er_diagram' => $er_diagram,
		];
	}

	public function put($data, $mtime, $name = '')
	{
	}
}
