<?php

//setup database connection
include 'lib/constants.php';
include LIB_PATH . '/Connect-With-Database.php';

$netId = '';
if (isset($_GET["netId"])) {
    $netId = htmlentities($_GET["netId"], ENT_QUOTES, "UTF-8");
}

$sectionId = '';
if (isset($_GET["sectionId"])) {
    $sectionId = htmlentities($_GET["sectionId"], ENT_QUOTES, "UTF-8");
}

//$sectionId = '';
//if (isset($_GET["sectionId"])) {
//    $netId = htmlentities($_GET["sectionId"], ENT_QUOTES, "UTF-8");
//}

$startDate = '';
if (isset($_GET["startDate"])) {
    $startDate = htmlentities($_GET["startDate"], ENT_QUOTES, "UTF-8");
}

$endDate = '';
if (isset($_GET["endDate"])) {
    $endDate = htmlentities($_GET["endDate"], ENT_QUOTES, "UTF-8");
}

$selectAttendQuery = 'SELECT tblClassAttendance.fldDate, tblClassAttendance.fldTimeIn, '
        . 'tblClassAttendance.fldTimeOut, tblClassAttendance.fldAttend '
        . 'FROM tblClassAttendance '
        . 'WHERE tblClassAttendance.fnkStuNetId = "' . $netId . '" '
        . 'AND tblClassAttendance.fnkSectionId = ' . $sectionId . ' '
        . 'AND tblClassAttendance.fldDate <= DATE("' . $endDate . '") '
        . 'AND tblClassAttendance.fldDate >= DATE("' . $startDate . '")';
$parameters = array();
if ($thisDatabaseReader->querySecurityOk($selectAttendQuery, 1, 3, 6, 2, 0)) {
    $records = $thisDatabaseReader->select($selectAttendQuery, $parameters);
}
$returnVal->history = array();
foreach ($records as $record) {
    $array = array("date" => $record["fldDate"], "timeIn" => $record["fldTimeIn"], "timeOut" => $record["fldTimeOut"], "attend" => $record["fldAttend"]);
    array_push($returnVal->history, $array);
}
echo json_encode($returnVal);
?>
