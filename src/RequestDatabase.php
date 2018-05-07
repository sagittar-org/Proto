<?php
namespace pieni\Proto;

class RequestDatabase implements \pieni\Sync\Driver
{
	public static $columns = [
		'tables' => [],
		'references' => ['table', 'column', 'referenced_table', 'referenced_column'],
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
		$data = $this->actual_database->get();
		$application_database = $this->application_database->get();
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
		$data['er_diagram'] = "digraph er_diagram {\n\trankdir=LR;\n\t".implode("\n\t", $lines)."\n}\n";
		return $data;
	}

	public function put($data, $mtime, $name = '')
	{
	}
}
