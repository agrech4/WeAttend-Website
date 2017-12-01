<?php
if (!empty($_POST["studentId"]) and !empty($_POST["attendanceDate"])){
  $stuNetId = $_POST["studentId"];
  $date = $_POST["attendanceDate"];
  $rosterCheckQuery = 'SELECT * FROM tblStudentSection WHERE fldStuNetId = "' . $stuNetId . '"';
  if ($thisDatabaseReader->querySecurityOk($rosterCheckQuery, 1, 0, 2, 0, 0)) {
    $stuSections = $thisDatabaseReader->select($rosterCheckQuery, '');
  }
  foreach ($stuSections as $stuSection) {
    if (in_array($sectionId, $stuSection)) {
      $inSection = true;
    }
  }
  if($inSection) {

    $timeInOutQuery = 'SELECT fldStart, fldEnd FROM tblSections WHERE pmkSectionId = ' . $sectionId . ';';

    if ($thisDatabaseReader->querySecurityOk($timeInOutQuery, 1, 0, 0, 0, 1)) {
      $timeInOut = $thisDatabaseReader->select($timeInOutQuery, '');
    }


    $timeIn = $timeInOut[0][0];
    $timeOut = $timeInOut[0][1];

  //  $timeIn = DATE($timeIn); SQL function, put in query
  //  $timeOut = DATE($timeOut);

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
      $message = 'Attendance was not recorded. Please try again.';
    } else {
      $success = true;
      $type = 'success';
      $message = 'Success! Attendance has been recorded.';
    }
    unset($records);
  } else {
    $success = false;
    $type = 'warning';
    $message = 'Student is not in class roster.';
  }
} else {
  $success = false;
  $type = 'warning';
  $message = 'All fields must be set.';
}
?>
