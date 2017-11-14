<?php

/**
 * Author: Neal Zhu (zxykit@gmail.com)
 * This file will take a netID as input and check if the netID belong to UVM student. 
 * if the netId belong to a student, it will output this student's name, and class list
*/


// get student/faculty status of netid
function LDAPstatus($uvmID) {
    error_reporting(0);
    //you need to connect to the ldap server
    $ds = ldap_connect("ldap.uvm.edu");
    //if your connection worked lets get the info we need
    if ($ds) {
        //set up our parameters (no need to change them)
        $r = ldap_bind($ds);
        $dn = "uid=$uvmID,ou=People,dc=uvm,dc=edu";
        $filter = "(|(netid=$uvmID))";
        /* in this array (between the parenthisis you place all the LDAP names you
          are looking for. You will notice that they are used below as well in the
          print statements.
         */
        $findthese = array("ou", "edupersonaffiliation");
        // now do the search and get the results which are storing in $info
        if (ldap_search($ds, $dn, $filter, $findthese)) {
            $sr = ldap_search($ds, $dn, $filter, $findthese);
            // if we found a match (in this example we should actually always find just one
            if (ldap_count_entries($ds, $sr) > 0) {
                $info = ldap_get_entries($ds, $sr);
                for ($k = 0; $k < $info[0]["edupersonaffiliation"]["count"]; $k++) {
                    if ($info[0]["edupersonaffiliation"][$k] == "Student") {
                        ldap_close($ds);
                        return "Student";
                    }
                }
                ldap_close($ds);
                return "Other";
            } else {
                // there is still a warning message before this line prints :(
                ldap_close($ds);
                return "Invalid";
            }
        } else {
            // same here, there is still a warning message before this line prints :(
            ldap_close($ds);
            return "Invalid"; // they had no uvm record
        }
    }
    ldap_close($ds);
    return "Invalid"; // they were found
}

$netId = '';
if (isset($_GET["netId"])) {
    $netId = htmlentities($_GET["netId"], ENT_QUOTES, "UTF-8");
}

$returnVal->isStudent = "false";
$returnVal->givenName = '';
$returnVal->lastName = '';
$returnVal->classList = array();

if (!empty($netId) && LDAPstatus($netId) == "Student") {
    $returnVal->isStudent = "true";
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
            $returnVal->givenName = $info[0]["givenname"][0];
            $returnVal->lastName = $info[0]["sn"][0];
        }
    }
    ldap_close($ds);

    
    include 'lib/constants.php';
    include LIB_PATH . '/Connect-With-Database.php';

    $netId = '';
    if (isset($_GET["netId"])) {
        $netId = htmlentities($_GET["netId"], ENT_QUOTES, "UTF-8");
    }
    $classList = array();
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
        $array = array("courseSubj" => $record["fldClassSubject"], "courseNum" => $record["fldCourseNum"], "section" => $record["fldSection"], "sectionId" => $record["fnkSectionId"]);
        array_push($returnVal->classList, $array);
    }
}



echo json_encode($returnVal);
?>