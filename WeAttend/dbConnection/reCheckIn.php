<?php

//setup database connection
include 'lib/constants.php';
include LIB_PATH . '/Connect-With-Database.php';

// retrive all data
// student's netid, beacon's uuid, major, minor, identifier, current date and time
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
$time = "";
if (isset($_GET["time"])) {
    $time = htmlentities($_GET["time"], ENT_QUOTES, "UTF-8");
}
$date = "";
if (isset($_GET["date"])) {
    $date = htmlentities($_GET["date"], ENT_QUOTES, "UTF-8");
}
$weekday = date(“l”, strtotime($date));

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

// status: "checkin" or "checkout"
$status = "";
if (isset($_GET["status"])) {
    $status = htmlentities($_GET["status"], ENT_QUOTES, "UTF-8");
}

$class = array();

// get this student's class which start within 1 hour or before current time 
// and the end time is after current time. 
$getClassQuery = "SELECT tblSections.pmkSectionId, tblSections.fldStart, tblSections.fldEnd, "
		. "tblClassAttendance.fldTimeIn, tblClassAttendance.fldTimeOut, "
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
        . "AND tblSections.fldStart < DATE_ADD(NOW(), INTERVAL 1 HOUR) "
        . "AND tblSections.fldEnd > NOW() "
        . "AND tblBeaconLoc.fldUUID = '" . $uuid . "' "
        . "AND tblBeaconLoc.fldMajor = " . $major . " "
        . "AND tblBeaconLoc.fldMinor = " . $minor . " ";
$parameters = array();

//$class = $thisDatabaseReader->testSecurityQuery($getClassQuery, 1, 9, 12, 2, 0);
if ($thisDatabaseReader->querySecurityOk($getClassQuery, 1, 9, 8, 2, 0)) {
    $class = $thisDatabaseReader->select($getClassQuery, $parameters);
}


if ($class[0]["pmkSectionId"]) {
    $classSectionId = $class[0]["pmkSectionId"];
    if ($class[0][pmkAttendanceId]) {
        if($status == "checkIn") {
            $updateQuery = "UPDATE tblClassAttendance SET tblClassAttendance.fldTimeIn = NOW(),"
                    . " tblClassAttendance.fldTimeOut = NULL "
                    . "WHERE tblClassAttendance.pmkAttendanceId = " . $class[0][pmkAttendanceId];

            $updateQueryParameters = array();
            if ($thisDatabaseWriter->querySecurityOk($updateQuery, 1, 0, 0, 0, 0)) {
                $checkInResult = $thisDatabaseWriter->insert($updateQuery, $updateQueryParameters);
            }
        }
        // checkout
        else{
            $updateQuery1 = "UPDATE tblClassAttendance "
            		. "INNER JOIN tblSections ON tblClassAttendance.fnkSectionId = tblSections.pmkSectionId "
                    . "SET tblClassAttendance.fldTimeOut = TIME(NOW()), "
                    . "tblClassAttendance.fldTimeInClass = GREATEST(0, (TIME_TO_SEC(TIMEDIFF(TIME(LEAST(tblSections.fldEnd, NOW())), TIME(GREATEST(tblClassAttendance.fldTimeIn, tblSections.fldStart))))/60 + tblClassAttendance.fldTimeInClass)) "
                    . "WHERE tblClassAttendance.pmkAttendanceId = " . $class[0][pmkAttendanceId];
            $updateQueryParameters1 = array();
            if ($thisDatabaseWriter->querySecurityOk($updateQuery1, 1, 0, 0, 0, 0)) {
                $checkInResult = $thisDatabaseWriter->update($updateQuery1, $updateQueryParameters1);
            }
        }
    } else {
        if($status == "checkIn") {
            $insertQuery = 'INSERT INTO tblClassAttendance'
                    . ' (tblClassAttendance.fldDate, tblClassAttendance.fldTimeIn, tblClassAttendance.fnkSectionId, tblClassAttendance.fnkStuNetId)'
                    . ' VALUES (DATE(NOW()), NOW(), ' . $classSectionId . ', "' . $netId . '") ';

            $insertQueryParameters = array();
            if ($thisDatabaseWriter->querySecurityOk($insertQuery, 0, 0, 2, 0, 0)) {
                $checkInResult = $thisDatabaseWriter->insert($insertQuery, $insertQueryParameters);
            }
        } else {
            $insertQuery1 = 'INSERT INTO tblClassAttendance'
                    . ' (fldDate, fldTimeIn, fnkSectionId, fnkStuNetId, fldTimeOut, fldTimeInClass)'
                    . ' VALUES (DATE(NOW()), TIME("'. $class[0]["fldStart"].'"), ' . $classSectionId . ', "' . $netId . '", TIME(LEAST(TIME("'. $class[0]["fldEnd"].'"), NOW())), TIME_TO_SEC(TIMEDIFF(TIME(LEAST(TIME("'. $class[0]["fldEnd"].'"), NOW())), TIME("'. $class[0]["fldStart"].'")))/60)';

            $insertQueryParameters1 = array();
            // $checkInResult = $thisDatabaseReader->testSecurityQuery($insertQuery1, 0, 0, 10, 0, 0);
            if ($thisDatabaseWriter->querySecurityOk($insertQuery1, 0, 0, 10, 0, 0)) {
                $checkInResult = $thisDatabaseWriter->insert($insertQuery1, $insertQueryParameters1);
            }
            
        }
    }

    if($checkInResult != 1){
        $returnVal->updateDB = "false";
    }else{
        $returnVal->updateDB = "true";
    }
    echo json_encode($returnVal);
}
?>