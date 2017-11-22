<?php

  $filename = $_FILES["Upload"]["tmp_name"];
  $netidCol = 10;

  if ($_FILES["Upload"]["size"] > 0) {
    $file = fopen($filename, "r");
    if (($cols = fgetcsv($file, 10000, ",")) !== FALSE) {
      $colNum = -1;
      foreach ($cols as $col) {
        $colNum = $colNum + 1;
        if (strtolower($col) == "netid") {
          $netidCol = $colNum;
        }
      }
    }

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
//    $records = $thisDatabaseWriter->testSecurityQuery($insertQuery, $netIDarray);
    if ($thisDatabaseWriter->querySecurityOk($insertQuery, 0, 0, 8, 0, 1)) {
//      $insertQuery = $thisDatabaseWriter->sanitizeQuery($insertQuery);
      $records = $thisDatabaseWriter->insert($insertQuery, $parameter);
    }
    fclose($file);
    if (!$records) {
      $success = false;
      $type = 'danger';
      $message = 'Something went wrong.';
    } else {
      $success = true;
      $type = 'success';
      $message = 'Success! Students added to roster.';
    }
  }
