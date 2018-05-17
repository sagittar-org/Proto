<?php
require_once 'vendor/autoload.php';

$database = 'pieni-tutorial';
$db = new mysqli('localhost', 'root', '');

// Config
$config = new \pieni\Sync\Handler('config', [
	['\pieni\Proto\Config', []],
]);
// print_r($config->get());

// Database
$actual_database = new \pieni\Sync\Handler('actual_database', [
	['\pieni\Proto\ActualDatabase', ['database' => $database, 'db' => $db]],
]);
$application_database = new \pieni\Sync\Handler('application_database', [
	['\pieni\Proto\ApplicationDatabase', ['actual_database' => $actual_database]],
]);
$filter_database = new \pieni\Sync\Handler('filter_database', [
	['\pieni\Proto\FilterDatabase', []],
]);
$request_database = new \pieni\Sync\Handler('request_database', [
	['\pieni\Proto\RequestDatabase', ['actual_database' => $actual_database, 'application_database' => $application_database, 'filter_database' => $filter_database]],
]);
//print_r($request_database->get('g'));

// Table
$actual_table = new \pieni\Sync\Handler('actual_table', [
	['\pieni\Proto\ActualTable', ['database' => $database, 'db' => $db]],
]);
$application_table = new \pieni\Sync\Handler('application_table', [
	['\pieni\Proto\ApplicationTable', ['config' => $config, 'actual_database' => $actual_database, 'actual_table' => $actual_table]],
]);
$filter_table = new \pieni\Sync\Handler('filter_table', [
	['\pieni\Proto\FilterTable', []],
]);
$request_table = new \pieni\Sync\Handler('request_table', [
	['\pieni\Proto\RequestTable', ['request_database' => $request_database, 'actual_table' => $actual_table, 'application_table' => $application_table, 'filter_table' => $filter_table]],
]);
print_r($request_table->get('g.post.index'));
