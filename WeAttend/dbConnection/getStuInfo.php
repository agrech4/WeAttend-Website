<!--
Author: Neal Zhu

This file will take a netID as input and check if the netID belong to UVM student. 
Then it will pull out this student's information such as first and last name 
courses they are current taking. 
-->
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
            print_r($info);
            $returnVal->isStudent = "true";
            $returnVal->givenName = $info[0]["givenname"][0];
            $returnVal->lastName = $info[0]["sn"][0];
        }
    }
    ldap_close($ds);
}



echo json_encode($returnVal);
?>