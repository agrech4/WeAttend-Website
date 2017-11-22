<?php

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
    $success = false;
    $type = 'danger';
    $message = 'Something went wrong.';
  } else {
    $success = true;
    $type = 'success';
    $message = 'Success!';
  }

?>
