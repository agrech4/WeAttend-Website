<?php

  $date = $_POST['attendanceDate'];

  $selectAttendQuery = 'SELECT tblClassAttendance.fnkStuNetId, tblClassAttendance.fldTimeIn, '
                      . 'tblClassAttendance.fldTimeOut, tblClassAttendance.fldTimeInClass, '
                      . 'tblClassAttendance.fldAttend FROM tblClassAttendance '
                      . 'WHERE tblClassAttendance.fnkSectionId = ' . $sectionId . ' '
                      . 'AND tblClassAttendance.fldDate = DATE("' . $date . '")';
  $parameters = array();
  if ($thisDatabaseReader->querySecurityOk($selectAttendQuery, 1, 1, 2, 0, 0)) {
      $records = $thisDatabaseReader->select($selectAttendQuery, $parameters);
  }
  if (!isset($records)) {
    $success = false;
    $type = 'danger';
    $message = 'Something went wrong.';
  } elseif (empty($records)) {
    $success = false;
    $type = 'warning';
    $message = 'No records for ' . date('M j, Y',strtotime($date)) . '.';
  } else {
    $success = true;
    $type = 'success';
    $message = 'Success!';
    $stuAttendance = $records;
  }
?>
