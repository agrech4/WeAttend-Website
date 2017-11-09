<?php

include '../header.php';
include '../nav.php';

//setup database connection
include '../lib/constants.php';
include '../' . LIB_PATH . '/Connect-With-Database.php';
if (isset($_POST["submit"]) and isset($_GET['sectionId'])) {
    $sectionId = (int) htmlentities($_GET["sectionId"], ENT_QUOTES, "UTF-8");
    $stuNetId = $_POST["studentId"];
    $date = $_POST["attendanceDate"];
    $insertQuery = "INSERT INTO tblClassAttendance (fldDate, fldTimeInClass, fldTimeIn, fldTimeOut, fldAttend, fnkSectionId, fnkStuNetId)
                   VALUES ";
//
//     $parameter = array();
//     //$records = $thisDatabaseWriter->testSecurityQuery($insertQuery, $netIDarray);
//     if ($thisDatabaseWriter->querySecurityOk($insertQuery, 0, 0, 8, 0, 1)) {
// //     $insertQuery = $thisDatabaseWriter->sanitizeQuery($insertQuery);
//       $records = $thisDatabaseWriter->insert($insertQuery, $parameter);
//     }
//
//     if (!$records) {
//       print($insertQuery);
//
//                 echo "<script type=\"text/javascript\">
// 							alert(\"Invalid File:Please Upload CSV File.\");
// 							window.location = \"../index.php\"
// 						  </script>";
//         } else {
//             echo "<script type=\"text/javascript\">
// 						alert(\"CSV File has been successfully Imported.\");
// 						window.location = \"../index.php\"
// 					</script>";
//         }
//         fclose($file);
//     }
}
