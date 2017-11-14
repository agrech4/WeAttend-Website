<?php

$date = getdate();
$weekday = $date["weekday"];
switch ($weekday) {
    case "Monday":
        $weekday = "M";
        break;
    case "Tuesday":
        $weekday = "T";
        break;
    case "Wednesday":
        $weekday = "W";
        break;
    case "Thursday":
        $weekday = "R";
        break;
    case "Friday":
        $weekday = "F";
        break;
    case "Saturday":
        $weekday = "S";
        break;
    case "Sunday":
        $weekday = "U";
        break;
    default:
        $weekday = "";
}

include 'lib/constants.php';
include LIB_PATH . '/Connect-With-Database.php';

$netId = '';
if (isset($_GET["netId"])) {
    $netId = htmlentities($_GET["netId"], ENT_QUOTES, "UTF-8");
}
$classList = array();
$query = "SELECT DISTINCT tblCourse.fldClassSubject,tblCourse.fldCourseNum, "
        . "tblSections.fldSection, tblLocation.fldBuildingArea, tblLocation.fldRoom, "
        . "tblBeaconLoc.fldUUID, tblBeaconLoc.fldMajor, tblBeaconLoc.fldMinor "
        . "FROM tblLocation "
        . "JOIN tblBeaconLoc ON tblLocation.pmkLocationId = tblBeaconLoc.fnkLocationId "
        . "JOIN tblSections ON tblLocation.pmkLocationId = tblSections.fnkLocationId "
        . "JOIN tblCourse ON tblCourse.pmkCourseId = tblSections.fnkCourseId "
        . "JOIN tblStudentSection ON tblSections.pmkSectionId = tblStudentSection.fnkSectionId "
        . "WHERE tblStudentSection.fldStuNetId = '" . $netId . "'";

$parameters = array();

if ($thisDatabaseReader->querySecurityOk($query, 1, 0, 2, 0, 0)) {
    $records = $thisDatabaseReader->select($query, $parameters);
}
foreach ($records as $record) {
    $day = explode(" ", $record["fldDays"]);
}

$returnVal->regions = array();
foreach($records as $record){
    $array = array("subject" => $record["fldClassSubject"], "courseNum" => $record["fldCourseNum"], 
        "section" => $record["fldSection"], "buildingArea" => $record["fldBuildingArea"], 
        "room" => $record["fldRoom"],"uuid" => $record["fldUUID"],"major" => $record["fldMajor"], 
        "minor" => $record["fldMinor"]);
    array_push($returnVal->regions,$array);
}

echo json_encode($returnVal);
?>