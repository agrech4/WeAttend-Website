<?php

  $date = $_POST['attendanceDate'];

  $selectAttendQuery = 'SELECT tblClassAttendance.fnkStuNetId, tblClassAttendance.fldTimeIn, '
                      . 'tblClassAttendance.fldTimeOut, tblClassAttendance.fldTimeInClass, '
                      . 'tblClassAttendance.fldAttend FROM tblClassAttendance '
                      . 'WHERE tblClassAttendance.fnkSectionId = ' . $sectionKey . ' '
                      . 'AND tblClassAttendance.fldDate = DATE("' . $date . '")';
  $parameters = array();
  if ($thisDatabaseReader->querySecurityOk($selectAttendQuery, 1, 1, 2, 0, 0)) {
      $records = $thisDatabaseReader->select($selectAttendQuery, $parameters);
  }
  $stuAttendance = $records;

  if (isset($_POST['download'])) {
    header('Refresh: 5; URL=scripts/download.php');
    // <META HTTP-EQUIV="Refresh" CONTENT="5; URL=/download.php">


  }
?>
