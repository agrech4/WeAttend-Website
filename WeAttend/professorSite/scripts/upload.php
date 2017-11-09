<?php
//setup database connection
include '../lib/constants.php';
include '../' . LIB_PATH . '/Connect-With-Database.php';
if (isset($_POST["submit"])) {
    $filename = $_FILES["Upload"]["tmp_name"];
    $netidCol = 10;

    if ($_FILES["Upload"]["size"] > 0) {
        $file = fopen($filename, "r");
        if(($cols = fgetcsv($file, 10000, ",")) !== FALSE) {
            $colNum = -1;
            foreach($cols as $col){
                $colNum = $colNum + 1;
                if(strtolower($col) == "netid"){
                    $netidCol = $colNum;
                }
            }
        }

        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            echo $getData[$netidCol];
            echo '<br>';
//
//            $sql = "INSERT into employeeinfo (emp_id,firstname,lastname,email,reg_date)
//                   values ('" . $getData[0] . "','" . $getData[1] . "','" . $getData[2] . "','" . $getData[3] . "','" . $getData[4] . "')";
//            $result = mysqli_query($con, $sql);
//            if (!isset($result)) {
//                echo "<script type=\"text/javascript\">
//							alert(\"Invalid File:Please Upload CSV File.\");
//							window.location = \"index.php\"
//						  </script>";
//            } else {
//                echo "<script type=\"text/javascript\">
//						alert(\"CSV File has been successfully Imported.\");
//						window.location = \"index.php\"
//					</script>";
//            }
        }

        fclose($file);
    }
}
?>
