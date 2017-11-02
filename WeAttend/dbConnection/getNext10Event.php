<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
$query = "SELECT tblSections.fldStart, tblSections.fldEnd, tblSections.fldDays, tblCourse.fldClassSubject, "
        . "tblCourse.fldCourseNum, tblSections.fldSection, tblSections.fnkCourseId, fnkSectionId "
        . "FROM tblStudentSection JOIN tblSections ON tblSections.pmkSectionId = fnkSectionId "
        . "JOIN tblCourse ON tblCourse.pmkCourseId = tblSections.fnkCourseId "
        . "WHERE fldStuNetId = ? "
        . "GROUP BY tblSections.fldSection, tblSections.fnkCourseId";
$parameters = array($netId);
if ($thisDatabaseReader->querySecurityOk($query, 1, 0, 0, 0, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $records = $thisDatabaseReader->select($query, $parameters);
}
foreach ($records as $record) {
    $day = explode(" ", $record["fldDays"]);
    
}
print "<pre>";
print_r($records);
print "</pre>";


echo $weekday;
?>