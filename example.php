<?php
require_once 'vendor/autoload.php';

$database = 'world';
$db = new mysqli('localhost', 'root', '');

$config = new \pieni\Sync\Handler('config', [
//	['\pieni\Sync\Json', ['path' => __DIR__]],
	['\pieni\Proto\Config', []],
]);
$config = $config->get();
$database = $config['db']['database']['value'];
$db = new mysqli($config['db']['host']['value'], $config['db']['user']['value'], $config['db']['password']['value']);

$actual_database = new \pieni\Sync\Handler('actual_database', [
//	['\pieni\Sync\Json', ['path' => __DIR__]],
	['\pieni\Proto\ActualDatabase', ['database' => $database, 'db' => $db]],
]);
$application_database = new \pieni\Sync\Handler('application_database', [
//	['\pieni\Sync\Json', ['path' => __DIR__]],
//	['\pieni\Sync\Excel', ['path' => __DIR__]],
//	['\pieni\Sync\Mysql', ['database' => $database, 'db' => $db]],
	['\pieni\Proto\ApplicationDatabase', ['actual_database' => $actual_database]],
]);
$request_database = new \pieni\Sync\Handler('request_database', [
	['\pieni\Proto\RequestDatabase', ['actual_database' => $actual_database, 'application_database' => $application_database]],
]);
$actual_table = new \pieni\Sync\Handler('actual_table', [
//	['\pieni\Sync\Json', ['path' => __DIR__]],
	['\pieni\Proto\ActualTable', ['database' => $database, 'db' => $db]],
]);
$application_table = new \pieni\Sync\Handler('application_table', [
//	['\pieni\Sync\Json', ['path' => __DIR__]],
//	['\pieni\Sync\Excel', ['path' => __DIR__]],
//	['\pieni\Sync\Mysql', ['database' => $database, 'db' => $db]],
	['\pieni\Proto\ApplicationTable', ['actual_table' => $actual_table]],
]);
$request_table = new \pieni\Sync\Handler('request_table', [
	['\pieni\Proto\RequestTable', ['request_database' => $request_database, 'actual_table' => $actual_table, 'application_table' => $application_table]],
]);
echo "<pre>";
//print_r( $actual_database->get() );
//print_r( $actual_table->get('country') );
//print_r( $application_database->get() );
//print_r( $application_table->get('country') );
echo '<div><img src="data:image/svg+xml;base64,'.shell_exec("echo '{$request_database->get('admin')['er_diagram']}' | dot -Tsvg | base64 -w0").'"></div>';
print_r( $request_database->get('guest') );
print_r( $request_table->get('guest.country.index') );
