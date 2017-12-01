<?php
if(!empty($_POST['attendanceDate'])){
  $date = $_POST['attendanceDate'];

  $selectAttendQuery = 'SELECT tblClassAttendance.fnkStuNetId, tblClassAttendance.fldTimeIn, '
                      . 'tblClassAttendance.fldTimeOut, tblClassAttendance.fldTimeInClass, '
                      . 'tblClassAttendance.fldAttend FROM tblClassAttendance '
                      . 'JOIN tblStudentSection ON tblClassAttendance.fnkStuNetId = tblStudentSection.fldStuNetId '
                      . 'WHERE tblClassAttendance.fnkSectionId = ' . $sectionId . ' '
                      . 'AND tblStudentSection.fnkSectionId = ' . $sectionId . ' '
                      . 'AND tblClassAttendance.fldDate = DATE("' . $date . '")';
  $parameters = array();
  if ($thisDatabaseReader->querySecurityOk($selectAttendQuery, 1, 2, 2, 0, 0)) {
      $records = $thisDatabaseReader->select($selectAttendQuery, $parameters);
  }
  if (!isset($records)) {
    $success = false;
    $type = 'danger';
    $message = 'Could not get attendance data. Pleeade try again.';
  } elseif (empty($records)) {
    $success = false;
    $type = 'warning';
    $message = 'No records for ' . date('M j, Y',strtotime($date)) . '.';
  } else {
    $success = true;
    $type = 'success';
    $message = 'Success! Loaded attendance for ' . date('M j, Y',strtotime($date)) . '.';
    $stuAttendance = $records;
  }
  unset($records);
} else {
  $success = false;
  $type = 'warning';
  $message = 'Please enter a date.';
}
?>
