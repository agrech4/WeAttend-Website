<?php

// //setup database connection
// include '../lib/constants.php';
// include '../' . LIB_PATH . '/Connect-With-Database.php';

  $date = $_POST["attendanceDate"];

  $selectAttendQuery = 'SELECT tblClassAttendance.fnkStuNetId, tblClassAttendance.fldTimeIn, '
                      . 'tblClassAttendance.fldTimeOut, tblClassAttendance.fldTimeInClass, '
                      . 'tblClassAttendance.fldAttend FROM tblClassAttendance '
                      . 'WHERE tblClassAttendance.fnkSectionId = ' . $sectionKey . ' '
                      . 'AND tblClassAttendance.fldDate = DATE("' . $date . '")';
  $parameters = array();
  if ($thisDatabaseReader->querySecurityOk($selectAttendQuery, 1, 1, 2, 0, 0)) {
      $records = $thisDatabaseReader->select($selectAttendQuery, $parameters);
  }
  $attendance = $records;
  // echo '<pre>';
  // print_r($records);
  // echo '</pre>';
?>
