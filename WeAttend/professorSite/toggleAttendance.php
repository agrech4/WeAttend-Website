<?php

//setup database connection
include 'lib/constants.php';
include LIB_PATH . '/Connect-With-Database.php';

$sectionId = '';
if (isset($_GET["sectionId"])) {
    $sectionId = htmlentities($_GET["sectionId"], ENT_QUOTES, "UTF-8");
}

$turn = '';
if (isset($_GET["turn"])) {
    $turn = htmlentities($_GET["turn"], ENT_QUOTES, "UTF-8");
}

if ($turn == "on") {
    $toggleAttendanceQuery = "UPDATE tblSections SET fldTakeAttendance = 1 WHERE pmkSectionId = " . $sectionId;
} else {
    $toggleAttendanceQuery = "UPDATE tblSections SET fldTakeAttendance = 0 WHERE pmkSectionId = " . $sectionId;
}

$parameters = array();
$records = $thisDatabaseWriter->testSecurityQuery($toggleAttendanceQuery, $parameters);
if ($thisDatabaseWriter->querySecurityOk($toggleAttendanceQuery, 1, 0, 0, 0, 0)) {
    $records = $thisDatabaseWriter->insert($toggleAttendanceQuery, $parameters);
}

if($records){
    print ("Done");
} else{
    print ("something wrong, please contact Alex");
}