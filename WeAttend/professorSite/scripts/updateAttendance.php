<?php

$query = 'SELECT tblClassAttendance.pmkAttendanceId, tblClassAttendance.fldTimeInClass, '
          . 'tblSections.fldStart, tblSections.fldEnd '
          . 'FROM tblClassAttendance JOIN tblSections ON tblClassAttendance.fnkSectionId = tblSections.pmkSectionId '
          . 'WHERE tblClassAttendance.fldAttend = 0';
$parameters = array();

if ($thisDatabaseReader->querySecurityOk($query, 1, 0, 0, 0, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $records = $thisDatabaseReader->select($query, $parameters);
}

$insertQuery = 'UPDATE tblClassAttendance SET fldAttend = 1 WHERE pmkAttendanceId in (';
foreach($records as $attendance) {
  $classTime = .75*(strtotime($attendance['fldEnd']) - strtotime($attendance['fldStart']))/60;
  if (($classTime) < $attendance['fldTimeInClass']) {
    $check = true;
    $insertQuery .= $attendance['pmkAttendanceId'] . ",";
  }
}
if($check) {
  $insertQuery = substr($insertQuery, 0, -1);
  $insertQuery .= ')';

  $parameter = array();
  if ($thisDatabaseWriter->querySecurityOk($insertQuery, 1, 0, 0, 0, 0)) {
    $records = $thisDatabaseWriter->insert($insertQuery, $parameter);
  }
}
unset($records);
?>
