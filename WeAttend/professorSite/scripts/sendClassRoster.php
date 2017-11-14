<?php

include '../header.php';
include '../nav.php';

//setup database connection
include '../lib/constants.php';
include '../' . LIB_PATH . '/Connect-With-Database.php';

if (isset($_POST["submit"]) and isset($_GET['sectionId'])) {

  $sectionId = (int) htmlentities($_GET["sectionId"], ENT_QUOTES, "UTF-8");

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
    if (!$records) {
      print($insertQuery);
      echo "<script type=\"text/javascript\">
            alert(\"Invalid File:Please Upload CSV File.\");
					  window.location = \"../class.php?sectionId=" . urlencode($sectionId) . "\"
					  </script>";
    } else {
      echo "<script type=\"text/javascript\">
					  alert(\"CSV File has been successfully imported.\");
					  window.location = \"../class.php?sectionId=" . urlencode($sectionId) . "\"
					  </script>";
    }
    fclose($file);
  }
}
