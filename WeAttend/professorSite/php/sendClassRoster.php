<?php

/**
 * Author: Alex Grech IV (alexiv42@gmail.com)
 * This file takes a csv or text file and uses it to populate the student roster
 * for a class on the database.
*/

$fileName = $_FILES["Upload"]["tmp_name"];
$fileType = pathinfo($_FILES["Upload"]["name"],PATHINFO_EXTENSION);

if($fileType == 'txt' || $fileType == 'csv') {

  if ($_FILES["Upload"]["size"] > 0) {
    $file = fopen($fileName, "r");
    if (($cols = fgetcsv($file, 10000, ",")) !== FALSE) {
      $colNum = -1;
      foreach ($cols as $col) {
        $colNum = $colNum + 1;
        if (strtolower($col) == "netid") {
          $netidCol = $colNum;
        }
      }
    }
    if (isset($netidCol)) {
      while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
        $netIDarray[] = $getData[$netidCol];
      }


      $insertQuery = "INSERT INTO tblStudentSection (fnkSectionId, fldStuNetId) VALUES ";
      $netIdSize = sizeof($netIDarray);
      for ($i = 0; $i < $netIdSize; $i++) {
        while ($i !== $netIdSize - 1) {
          $insertQuery .= "(" . $sectionId . ",'" . $netIDarray[$i] . "'), ";
          $i++;
        }
      }
      $insertQuery .= "(" . $sectionId . ", '" . $netIDarray[$netIdSize - 1] . "');";

      $parameter = array();
      if ($thisDatabaseWriter->querySecurityOk($insertQuery, 0, 0, 2*$netIdSize, 0, 1)) {
        $deleteQuery = "DELETE FROM tblStudentSection WHERE tblStudentSection.fnkSectionId = " . $sectionId;
        if ($thisDatabaseWriter->querySecurityOk($deleteQuery, 1, 0, 0, 0, 0)) {
          $delRecords = $thisDatabaseWriter->delete($deleteQuery, $parameter);
        }
        $records = $thisDatabaseWriter->insert($insertQuery, $parameter);
      }
      fclose($file);
      if (!$records) {
        $success = false;
        $type = 'danger';
        $message = 'Something went wrong. Please try again.';
      } else {
        $success = true;
        $type = 'success';
        $message = 'Success! Student roster updated.';
      }
      unset($records);
    } else {
      $success = false;
      $type = 'warning';
      $message = 'File is not formatted correctly.';
    }
  }
} else {
  $success = false;
  $type = 'warning';
  $message = 'File was not of correct type. Please only upload .csv or .txt files';
}
?>
