<?php

//setup database connection
include 'lib/constants.php';
include LIB_PATH . '/Connect-With-Database.php';

// retrive all data
$netId = '';
if (isset($_GET["netId"])) {
    $netId = htmlentities($_GET["netId"], ENT_QUOTES, "UTF-8");
}

$uuid = "";
if (isset($_GET["uuid"])) {
    $uuid = htmlentities($_GET["uuid"], ENT_QUOTES, "UTF-8");
}

$major = "";
if (isset($_GET["major"])) {
    $major = htmlentities($_GET["major"], ENT_QUOTES, "UTF-8");
}
$minor = "";
if (isset($_GET["minor"])) {
    $minor = htmlentities($_GET["minor"], ENT_QUOTES, "UTF-8");
}
$identifier = "";
if (isset($_GET["identifier"])) {
    $identifier = htmlentities($_GET["identifier"], ENT_QUOTES, "UTF-8");
}
$date = getdate();
$time = $date["hours"] . ":" . $date["minutes"] . ":" . $date["seconds"];
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
$status = "";
if (isset($_GET["status"])) {
    $status = htmlentities($_GET["status"], ENT_QUOTES, "UTF-8");
}

//TODO testing only
$weekday = "M";
// check if student
$class = array();

// get this student's class which start within one hour or before current time
// and the end time is after current time.
// get
$getClassQuery = "SELECT tblSections.pmkSectionId, tblClassAttendance.fldTimeIn, tblClassAttendance.fldTimeOut, "
        . "tblClassAttendance.pmkAttendanceId, tblClassAttendance.pmkAttendanceId "
        . "FROM tblSections "
        . "JOIN tblStudentSection ON tblSections.pmkSectionId = tblStudentSection.fnkSectionId "
        . "JOIN tblLocation ON tblLocation.pmkLocationId = tblSections.fnkLocationId "
        . "JOIN tblBeaconLoc ON tblLocation.pmkLocationId = tblBeaconLoc.fnkLocationId "
        . "LEFT JOIN tblClassAttendance ON tblClassAttendance.fnkSectionId = tblSections.pmkSectionId "
        . "AND tblClassAttendance.fnkStuNetId = '" . $netId . "' "
        . "AND tblClassAttendance.fldDate = DATE(NOW()) "
        . "WHERE tblSections.fldDays LIKE '%" . $weekday . "%' "
        . "AND tblStudentSection.fldStuNetId = '" . $netId . "' "
        . "AND tblSections.fldTakeAttendance = 1 "
        // TODO: testing only
        . "AND tblSections.fldStart < DATE_ADD(TIME('22:30:00'), INTERVAL 1 HOUR) "
        // TODO: testing only
        . "AND tblSections.fldEnd > TIME('22:30:00') "
        . "AND tblBeaconLoc.fldUUID = '" . $uuid . "' "
        . "AND tblBeaconLoc.fldMajor = " . $major . " "
        . "AND tblBeaconLoc.fldMinor = " . $minor . " ";
$parameters = array();

//$class = $thisDatabaseReader->testSecurityQuery($getClassQuery, 1, 9, 12, 2, 0);
if ($thisDatabaseReader->querySecurityOk($getClassQuery, 1, 9, 12, 2, 0)) {
    $class = $thisDatabaseReader->select($getClassQuery, $parameters);
}

print "<pre>";
print_r($class);
print "</pre>";

if ($class[0]["pmkSectionId"]) {
    $classSectionId = $class[0]["pmkSectionId"];
    if ($class[0][pmkAttendanceId]) {
        if($status == "checkIn") {
            $updateQuery = "UPDATE tblClassAttendance SET tblClassAttendance.fldTimeIn = NOW(),"
                    . " tblClassAttendance.fldTimeOut = NULL "
                    . "WHERE tblClassAttendance.pmkAttendanceId = " . $class[0][pmkAttendanceId];

            $updateQueryParameters = array();
            if ($thisDatabaseWriter->querySecurityOk($updateQuery, 1, 0, 0, 0, 0)) {
                $class = $thisDatabaseWriter->insert($updateQuery, $updateQueryParameters);
            }
        }
        // checkout
        else{
            $updateQuery1 = "UPDATE tblClassAttendance SET tblClassAttendance.fldTimeOut = NOW(),"
                    . "tblClassAttendance.fldTimeInClass = TIME_TO_SEC(TIMEDIFF(TIME(NOW()), tblClassAttendance.fldTimeIn))/60 "
                    . "WHERE tblClassAttendance.pmkAttendanceId = " . $class[0][pmkAttendanceId];

            $updateQueryParameters1 = array();
            if ($thisDatabaseWriter->querySecurityOk($updateQuery1, 1, 0, 2, 0, 0)) {
                $class = $thisDatabaseWriter->insert($updateQuery1, $updateQueryParameters);
            }
        }
    } else {
        $insertQuery = 'INSERT INTO tblClassAttendance'
                . ' (tblClassAttendance.fldDate, tblClassAttendance.fldTimeIn, tblClassAttendance.fnkSectionId, tblClassAttendance.fnkStuNetId)'
                . ' VALUES (DATE(NOW()), NOW(), ' . $classSectionId . ', "' . $netId . '") ';

        $insertQueryParameters = array();
        if ($thisDatabaseWriter->querySecurityOk($insertQuery, 0, 0, 2, 0, 0)) {
            $class = $thisDatabaseWriter->insert($insertQuery, $insertQueryParameters);
        }
    }
}
?>
