<?php
include 'lib/constants.php';
include LIB_PATH . '/Connect-With-Database.php';

$netId = '';
if (isset($_GET["netId"])) {
    $netId = htmlentities($_GET["netId"], ENT_QUOTES, "UTF-8");
}
$classList = array();
$query = "SELECT tblCourse.fldClassSubject, tblCourse.fldCourseNum, tblSections.fldSection, tblSections.fnkCourseId, tblSections.fnkSectionId "
        . "FROM tblStudentSection JOIN tblSections ON tblSections.pmkSectionId = fnkSectionId "
        . "JOIN tblCourse ON tblCourse.pmkCourseId = tblSections.fnkCourseId "
        . "WHERE fldStuNetId = ? "
        . "GROUP BY tblSections.fldSection, tblSections.fnkCourseId";
$parameters = array($netId);
if ($thisDatabaseReader->querySecurityOk($query, 1, 0, 0, 0, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $records = $thisDatabaseReader->select($query, $parameters);
}

print "<pre>";
print_r($records);
print "</pre>";
foreach ($records as $record) {
    $array = array("courseSubj" => $record["fldClassSubject"], "courseNum" => $record["fldCourseNum"], "section" => $record["fldSection"], "sectionId" => $record["fnkSectionId"]);
    array_push($classList, $array);
}
echo json_encode($classList);
?>
