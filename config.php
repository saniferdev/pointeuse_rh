<?php

/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

$sqlServerHost = '192.168.130.75';
$sqlServerDatabase = 'IN';
$sqlServerUser = 'pointage';
$sqlServerPassword = 'WesoKhu640Rfz0Yi';

$connectionInfo = array("Database" => $sqlServerDatabase, "UID" => $sqlServerUser, "PWD" => $sqlServerPassword, "CharacterSet" => "UTF-8");
$link = sqlsrv_connect($sqlServerHost, $connectionInfo);
if (!$link) {
     die( print_r( sqlsrv_errors(), true));
}
