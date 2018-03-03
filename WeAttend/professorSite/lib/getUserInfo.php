<?php

/**
 * Author: Neal Zhu (zxykit@gmail.com)
 * This file will take a netID as input and check if the netID belong to UVM student.
 * if the netId belong to a student, it will output this student's name, and class list
*/

// include_once 'Connect-With-Database.php';

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

$USER_NET_ID = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
$USER_NET_ID = 'weattend';
$USER_STATUS = LDAPstatus($USER_NET_ID);

$CLASS_LIST = array();
$query = "SELECT tblCourse.pmkCourseId, tblSections.pmkSectionId, tblCourse.fldClassSubject,"
          ." tblCourse.fldCourseNum, tblSections.fldSection, tblSections.fldStart, tblSections.fldEnd,"
          ." tblSections.fldDays, tblLocation.fldBuildingArea, tblLocation.fldRoom, tblSections.fldTakeAttendance"
          ." FROM tblCourse JOIN tblSections ON tblCourse.pmkCourseId = tblSections.fnkCourseId"
          ." JOIN tblLocation ON tblLocation.pmkLocationId = tblSections.fnkLocationId WHERE tblSections.fldTeacherNetId = ?";
$parameters = array($USER_NET_ID);

if ($thisDatabaseReader->querySecurityOk($query, 1, 0, 0, 0, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $records = $thisDatabaseReader->select($query, $parameters);
}
foreach($records as $key => $class) {
  $CLASS_LIST[$class['pmkSectionId']] = $class;
}
unset($records);
?>
