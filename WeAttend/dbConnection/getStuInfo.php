<?php

$netId = '';
if (isset($_GET["netId"])) {
    $netId = htmlentities($_GET["netId"], ENT_QUOTES, "UTF-8");
}

$returnVal->isStudent = "false";
$returnVal->givenName = '';
$returnVal->lastName = '';

if (!empty($netId)) {
    $ds = ldap_connect("ldap.uvm.edu");
    if ($ds) {
        $r = ldap_bind($ds);
        $dn = "uid=$netId,ou=People,dc=uvm,dc=edu";
        $filter = "(|(netid=$netId))";
        $findthese = array("sn", "givenname");
        // now do the search and get the results which are stored in $info
        $sr = ldap_search($ds, $dn, $filter, $findthese);
        // if we found a match (in this example we should actually always find just one
        if (ldap_count_entries($ds, $sr) > 0) {
            $info = ldap_get_entries($ds, $sr);
            $returnVal->isStudent = "true";
            $returnVal->givenName = $info[0]["givenname"][0];
            $returnVal->lastName = $info[0]["sn"][0];
        }
    }
    ldap_close($ds);
}


if($returnVal->isStudent == "true") {
    $returnVal->classList = array();
    include 'lib/constants.php';
    include LIB_PATH . '/Connect-With-Database.php';

    $query = "SELECT tblCourse.fldClassSubject, tblCourse.fldCourseNum, tblSections.fldSection, tblSections.fnkCourseId, fnkSectionId "
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
        $array = array("courseSubj" => $record["fldClassSubject"], "courseNum" => $record["fldCourseNum"], "section" => $record["fldSection"]);
        array_push($returnVal->classList, $array);
    }
}
echo json_encode($returnVal);
?>