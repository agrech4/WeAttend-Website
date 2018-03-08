<?php

/**
 * Author: Alex Grech IV (alexiv42@gmail.com)
 * This file retrieves the student roster for a class from the database.
*/

$selectRosterQuery = 'SELECT tblStudentSection.fldStuNetId FROM tblStudentSection '
                    . 'WHERE tblStudentSection.fnkSectionId = ' . $sectionId;
$parameters = array();
if ($thisDatabaseReader->querySecurityOk($selectRosterQuery, 1, 0, 0, 0, 0)) {
    $records = $thisDatabaseReader->select($selectRosterQuery, $parameters);
}
if (!isset($records)) {
  $success = false;
  $type = 'danger';
  $message = 'Could not get student roster. Please try again.';
} elseif (empty($records)) {
  $success = false;
  $type = 'warning';
  $message = 'No records for this section.';
} else {
  $success = true;
  $type = 'success';
  $message = 'Success! Loaded student roster.';
  $stuRoster = $records;
}
unset($records);
?>
