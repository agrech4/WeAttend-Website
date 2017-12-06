<?php
if (!empty($_POST["studentId"]) and !empty($_POST["attendanceDate"])){

  $stuNetId = $_POST["studentId"];
  $date = $_POST["attendanceDate"];

  if(ctype_alnum($stuNetId)) {

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

      $checkAttendanceQuery = 'SELECT pmkAttendanceId, fldAttend FROM tblClassAttendance '
                            . 'WHERE fnkStuNetId = "' . $stuNetId . '" AND fldDate = DATE("' . $date . '") '
                            . 'AND fldTimeIn > DATE_ADD(TIME("' . $timeIn . '"), INTERVAL -1 HOUR) '
                            . 'AND fldTimeIn < TIME("' . $timeOut . '") AND fnkSectionId = ' . $sectionId;
                            $parameter = array();
      if ($thisDatabaseReader->querySecurityOk($checkAttendanceQuery, 1, 4, 8, 2, 0)) {
        $checkAttendanceQuery = $thisDatabaseReader->sanitizeQuery($checkAttendanceQuery, false, false, true);
        $currentAttendances = $thisDatabaseReader->select($checkAttendanceQuery, $parameter);
      }
      $alreadyRecorded = false;
      foreach ($currentAttendances as $attendance) {
        if($attendance['fldAttend'] == 1) {
          $alreadyRecorded = true;
          $maxId = $attendance['pmkAttendanceId'];
        }
      }

      if(!$alreadyRecorded) {

        $deleteQuery = '';
        foreach ($currentAttendances as $attendance) {
          $deleteQuery .= 'DELETE FROM tblClassAttendance WHERE pmkAttendanceId = ' . $attendance['pmkAttendanceId'] . ';';
        }
        $parameter = array();
        if ($thisDatabaseWriter->querySecurityOk($deleteQuery,sizeof($currentAttendances), 0, 0, 0, sizeof($currentAttendances))) {
          $deleted = $thisDatabaseWriter->delete($deleteQuery, $parameter);
        }

        $insertQuery = 'INSERT INTO tblClassAttendance (fldDate, fldTimeInClass, fldTimeIn, fldTimeOut, fldAttend, fnkSectionId, fnkStuNetId) '
                        . 'VALUES ("' . $date . '", ' . 'TIME_TO_SEC(TIMEDIFF("' . $timeOut . '","' . $timeIn . '"))/' . 60 . ', "' . $timeIn . '", "'
                        . $timeOut . '", ' . 1 . ', ' . $sectionId . ', "' . $stuNetId . '")' ;
        $parameter = array();
        if ($thisDatabaseWriter->querySecurityOk($insertQuery, 0, 0, 12, 0, 0)) {
          $insertQuery = $thisDatabaseWriter->sanitizeQuery($insertQuery, false, false, true);
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

        $deleteQuery = '';
        foreach ($currentAttendances as $attendance) {
          if($attendance['pmkAttendanceId'] != $maxId) {
            $deleteQuery .= 'DELETE FROM tblClassAttendance WHERE pmkAttendanceId = ' . $attendance['pmkAttendanceId'] . ';';
          }
        }
        $parameter = array();
        if ($thisDatabaseWriter->querySecurityOk($deleteQuery,sizeof($currentAttendances)-1, 0, 0, 0, sizeof($currentAttendances)-1)) {
          $deleted = $thisDatabaseWriter->delete($deleteQuery, $parameter);
        }

        $success = false;
        $type = 'warning';
        $message = 'Attendance has already been recorded for ' . $stuNetId . ' on ' . date('M j, Y',strtotime($date));
      }
    } else {
      $success = false;
      $type = 'warning';
      $message = 'Student is not in class roster.';
    }
  } else {
    $success = false;
    $type = 'warning';
    $message = 'NetID must only be lowercase letters.';
  }
} else {
  $success = false;
  $type = 'warning';
  $message = 'All fields must be set.';
}
?>
