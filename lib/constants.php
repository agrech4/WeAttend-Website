<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  // find name in uvm directory
  function ldapName($uvmID) {
    if (empty($uvmID)){
      return "no:netid";
    }
    $name = "not:found";

    $ds = ldap_connect("ldap.uvm.edu");

    if ($ds) {
      $r = ldap_bind($ds);
      $dn = "uid=$uvmID,ou=People,dc=uvm,dc=edu";
      $filter = "(|(netid=$uvmID))";
      $findthese = array("sn", "givenname");

      // now do the search and get the results which are stored in $info
      $sr = ldap_search($ds, $dn, $filter, $findthese);

      // if we found a match (in this example we should actually always find just one
      if (ldap_count_entries($ds, $sr) > 0) {
        $info = ldap_get_entries($ds, $sr);
        $name = $info[0]["givenname"][0] . ":" . $info[0]["sn"][0];
      }
    }

    ldap_close($ds);

    return $name;
  }
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
      $sr = ldap_search($ds, $dn, $filter, $findthese);
      // if we found a match (in this example we should actually always find just one
      if (ldap_count_entries($ds, $sr) > 0) {
        $info = ldap_get_entries($ds, $sr);
        for ($k = 0; $k < $info[0]["edupersonaffiliation"]["count"]; $k++) {
          if ($info[0]["edupersonaffiliation"][$k] == "Faculty") {
            if ($info[0]["ou"][0] == "Computer Science") {
              ldap_close($ds);
              return "CSfac";
            } else {
              ldap_close($ds);
              return "Faculty";
            }
          }
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
    ldap_close($ds);
    return "Invalid"; // they were found
  }
  //$USER_NET_ID = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
  $USER_NET_ID = 'ceskalka';
  $USER_NAME = explode(":", ldapName($USER_NET_ID));
  $USER_STATUS = LDAPstatus($USER_NET_ID);
  define('PHP_SELF', htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'));
  $PATH_PARTS = pathinfo(PHP_SELF);
  $FILE_NAME = $PATH_PARTS['filename'];
  $classFile = fopen("http://giraffe.uvm.edu/~rgweb/batch/curr_enroll_fall.txt", "r") or die("Error opening file");
  $i = 0;
  while(($line = fgetcsv($classFile)) !== FALSE) {
    if($i == 0) {
      $c = 0;
      foreach($line as $col) {
        $cols[$c] = $col;
        $c++;
      }
      $i++;
    } else if($i > 0) {
      $c = 0;
      if($line[17] == $USER_NET_ID) {
        foreach($line as $col) {
          $CLASS_LIST[$i-1][$cols[$c]] = $col;
          $c++;
        }
        $i++;
      }
    }
    $i++;
  }
?>
