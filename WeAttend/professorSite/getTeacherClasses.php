<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include 'lib/constants.php';
include LIB_PATH . '/Connect-With-Database.php';

$netId = '';
if (isset($_GET["netId"])) {
    $netId = htmlentities($_GET["netId"], ENT_QUOTES, "UTF-8");
}

$classList = array();
$query = "SELECT tblCourse.fldClassSubject, tblCourse.fldCourseNum, tblSections.fldSection, "
        . "tblSections.fldStart, tblSections.fldEnd, tblSections.fldDays "
        . "FROM tblCourse JOIN tblSections ON tblCourse.pmkCourseId = tblSections.fnkCourseId "
        . "WHERE tblSections.fldTeacherNetId = ?";
$parameters = array($netId);

if ($thisDatabaseReader->querySecurityOk($query, 1, 0, 0, 0, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $records = $thisDatabaseReader->select($query, $parameters);
}
print "<pre>";
print_r($records);
print "</pre>";
?>
