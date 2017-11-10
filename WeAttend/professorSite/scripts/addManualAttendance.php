<?php

include '../header.php';
include '../nav.php';

//setup database connection
include '../lib/constants.php';
include '../' . LIB_PATH . '/Connect-With-Database.php';

if (isset($_POST["submit"]) and isset($_GET['sectionId'])) {

  $sectionId = (int) htmlentities($_GET["sectionId"], ENT_QUOTES, "UTF-8");

  $timeInOutQuery = "SELECT fldStart, fldEnd FROM tblSections WHERE pmkSectionId = " . $sectionId . ";";
//  $records = $thisDatabaseWriter->testSecurityQuery($timeInOutQuery, 1, 0, 0, 0, 1);

  if ($thisDatabaseReader->querySecurityOk($timeInOutQuery, 1, 0, 0, 0, 1)) {
//    $timeInOutQuery = $thisDatabaseReader->sanitizeQuery($timeInOutQuery);
    $timeInOut = $thisDatabaseReader->select($timeInOutQuery, '');
  }

//  print query to check if correct
//  print "<pre>";
//  print_r($timeInOut);
//  print "</pre>";

  $timeIn = $timeInOut[0][0];
  $timeOut = $timeInOut[0][1];

//  $timeIn = DATE($timeIn); SQL function, put in query
//  $timeOut = DATE($timeOut);

  $stuNetId = $_POST["studentId"];
  $date = $_POST["attendanceDate"];
  $insertQuery = "INSERT INTO tblClassAttendance (fldDate, fldTimeInClass, fldTimeIn, fldTimeOut, fldAttend, fnkSectionId, fnkStuNetId)"
                  . " VALUES ('" . $date . "', " . "TIME_TO_SEC(TIMEDIFF('" . $timeOut . "','" . $timeIn . "'))/" . 60 . ", '" . $timeIn . "', '"
                  . $timeOut . "', " . 1 . ", " . $sectionId . ", '" . $stuNetId . "');" ;

//  print($insertQuery);
  $parameter = array();
//  $records = $thisDatabaseWriter->testSecurityQuery($insertQuery, $netIDarray);
  if ($thisDatabaseWriter->querySecurityOk($insertQuery, 0, 0, 12, 0, 1)) {
//    $insertQuery = $thisDatabaseWriter->sanitizeQuery($insertQuery);
    $records = $thisDatabaseWriter->insert($insertQuery, $parameter);
  }

  if (!$records) {
    echo "<script type=\"text/javascript\">
 					alert(\"Error recording attendance.\");
 					window.location = \"../class.php?sectionId=" . urlencode($sectionId) . "\"
 					</script>";
  } else {
    echo "<script type=\"text/javascript\">
 					alert(\"Manual Attendance has been recorded.\");
 					window.location = \"../class.php?sectionId=" . urlencode($sectionId) . "\"
 					</script>";
  }
}
