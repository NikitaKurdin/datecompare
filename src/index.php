<?php

@session_start ();
@error_reporting ( E_ERROR );

@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ERROR );

require_once '../vendor/autoload.php';

use NikitaKurdin\DateCompare\DateCompare;

$DateCompare = new DateCompare;

$date1 = "02.05.2008";
$date2 = "05.2008";

if (isset($argv[1]) AND isset($argv[2]))
{
	$date1 = $argv[1];
	$date2 = $argv[2];
}

$status = $DateCompare->Check($date1, $date2);
var_dump($status);

?>