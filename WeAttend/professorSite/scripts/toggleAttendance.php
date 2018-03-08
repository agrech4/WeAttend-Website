<?php

/**
 * Author: Alex Grech IV (alexiv42@gmail.com)
 * This file toggles whether or not attendance is being taken for this class.
*/

  $sectionId = htmlentities($_GET["sectionId"], ENT_QUOTES, "UTF-8");

  $turn = '';
  if (isset($_GET["turn"])) {
    $turn = htmlentities($_GET["turn"], ENT_QUOTES, "UTF-8");
  }

  if ($turn == "on") {
    $toggleAttendanceQuery = "UPDATE tblSections SET fldTakeAttendance = 1 WHERE pmkSectionId = " . $sectionId;
  } elseif ($turn == "off") {
    $toggleAttendanceQuery = "UPDATE tblSections SET fldTakeAttendance = 0 WHERE pmkSectionId = " . $sectionId;
  }

  $parameters = array();
  //$records = $thisDatabaseWriter->testSecurityQuery($toggleAttendanceQuery, $parameters);
  if ($thisDatabaseWriter->querySecurityOk($toggleAttendanceQuery, 1, 0, 0, 0, 0)) {
    $records = $thisDatabaseWriter->insert($toggleAttendanceQuery, $parameters);
  }
  unset($records);
  //
  // if(!$records){
  //   echo "<script type=\"text/javascript\">
 	// 				alert(\"Something went wrong.\");
 	// 				window.location = \"../class.php?sectionId=" . urlencode($sectionId) . "\"
 	// 				</script>";
  // } else{
  //   echo "<script type=\"text/javascript\">
 	// 				alert(\"Attendance has been turned " . $turn . ".\");
 	// 				window.location = \"../class.php?sectionId=" . urlencode($sectionId) . "\"
 	// 				</script>";
  // }
// }
