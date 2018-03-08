<?php

/**
 * Author: Alex Grech IV (alexiv42@gmail.com)
 * This file downloads an array of student attendance information as a csv.
*/

  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  $fileName = $_GET['fileName'];
  $headers = $_GET['headers'];
  $attendanceRecords = $_GET['attendanceRecords'];
  //download CSV
  header('Content-Type: application/csv');
  header('Content-Disposition: attachment; filename="' . $fileName . '";');

  //open the "output" stream
  //see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
  $f = fopen('php://output', 'w');

  fputcsv($f, $headers);

  foreach ($attendanceRecords as $line) {
    fputcsv($f, array_unique($line));
  }

?>
