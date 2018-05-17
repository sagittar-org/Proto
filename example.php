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
$request_database = new \pieni\Sync\Handler('request_database', [
	['\pieni\Proto\RequestDatabase', ['application_database' => $application_database]],
]);
print_r($request_database->get());
