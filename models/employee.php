<?php
require(dirname(__FILE__) . '/../config.php');
require(dirname(__FILE__) . '/../classes/employee.php');


$class_employee 		= new employee();
$class_employee->link 	= $link;

$date_debut = isset($_POST['start']) ? $class_employee->conversion($_POST['start']) : date('Y-m-d');
$date_fin   = isset($_POST['end']) ? $class_employee->conversion($_POST['end']) : date('Y-m-d');
$num   		= isset($_POST['badge']) ? $_POST['badge'] : '';
$e   		= isset($_POST['e']) ? $_POST['e'] : '0';
$site_      = isset($_POST['site']) ? $_POST['site'] : '';
$type   	= isset($_POST['type']) ? $_POST['type'] : '';

$option_employee      	= $option_site  = $option_machine  = $table  = "";

$employee 				= $class_employee->employees();
$site                   = $class_employee->site();
/*$machine 				= $class_employee->machines();
$site                   = $class_employee->machines();*/
$listes 				= $class_employee->requete($date_debut,$date_fin,$num,$type,$site_,SQLSRV_FETCH_NUMERIC);
$el_ 					= $class_employee->requete($date_debut,$date_fin,$num,$type,$site_,SQLSRV_FETCH_ASSOC);

foreach ($employee as $value) {
    $option_employee 	.= '<option value="'.$value[0].'">'.$value[0].' - '.$value[1].' '.$value[2].'</option>';
}

foreach ($site as $value) {
    $option_site        .= '<option value="'.$value[0].'">'.$value[1].'</option>';
}

/*foreach ($machine as $value) {
    $option_machine 	.= '<option value="'.$value[0].'">'.$value[1].'</option>';
}*/

foreach ($listes as $value) {
    $exp     = explode(":", $value[11]);
    $h       = empty($class_employee->nombre_G($exp[0])) ? '00' : $class_employee->nombre_G($exp[0]);
    $m       = empty($class_employee->nombre_D($exp[1])) ? '00' : $class_employee->nombre_D($exp[1]);
    $table 	.= '<tr>';
    $table 	.=    '<td>'.$value[0].'</td>';
    $table 	.=    '<td>'.$value[1].'</td>';
    $table 	.=    '<td>'.$value[2].' '.$value[3].'</td>';
    $table 	.=    '<td>'.$value[4].'</td>';
    $table 	.=    '<td>'.$value[5].'</td>';
    $table  .=    '<td>'.$value[6].'</td>';
    $table 	.=    '<td>'.$value[7].'</td>';
    $table 	.=    '<td>'.$value[8].'</td>';
    $table  .=    '<td>'.$value[9].'</td>';
    $table  .=    '<td>'.$value[10].'</td>';
    $table 	.=    '<td>'.$h.':'.$m.':00</td>';

    $table 	.= '</tr>';
}

if(isset($e) && $e == 1){
	require(dirname(__FILE__) . '/excel.php');
}

