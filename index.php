<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Index Test</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body>
      <?php
      // find name in uvm directory
      function ldapName($uvmID) {
        if (empty($uvmID))
          return "no:netid";

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
      $studentNetId = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
      echo "netid: " . $studentNetId;
	    $studentName = explode(":", ldapName($studentNetId));
      $status = LDAPstatus($studentNetId);
      echo "status: " . $status;
      echo "firstName: " . $studentName[0];
      echo "lastName: " . $studentName[1];
      ?>
    <!-- Navbar -->
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">WeAttend</a>
        </div>
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">Home</a></li>
          <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">My Classes<span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">Class1</a></li>
              <li><a href="#">Class2</a></li>
              <li><a href="#">Class3</a></li>
            </ul>
          </li>
          <li><a href="classList.html">Class List</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
        <p class="navbar-text navbar-right">jdoe</p>
      </div>
    </nav>

    <div class="container-fluid">
      <h1>Welcome!</h1>
      <p>Keep track of attendence with WeAttend<sup>TM</sup></p>
    </div>
  </body>
</html>
