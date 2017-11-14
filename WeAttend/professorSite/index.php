<!DOCTYPE html>
<html lang="en">
  <?php include 'header.php';?>
  <body>
    <?php include 'nav.php';?>

    <div class="container-fluid">
      <h1>Class List</h1>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Class</th>
              <th>Time</th>
              <th>Days</th>
              <th>Building</th>
              <th>Room</th>
              <th>Taking Attendance</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach($CLASS_LIST as $class) {
                echo '<tr>';
                //Class
                echo '<td><a href="class.php?sectionId=', urlencode($class["pmkSectionId"]), '">';
                echo $class["fldClassSubject"];
                echo ' ';
                echo sprintf("%'.03d",$class["fldCourseNum"]);
                echo ' ';
                echo $class["fldSection"];
                echo '</a></td>';
                //Time
                echo '<td>';
                echo date('G:i',strtotime($class["fldStart"]));
                echo '-';
                echo date('G:i',strtotime($class["fldEnd"]));
                echo '</td>';
                //Days
                echo '<td>';
                echo $class["fldDays"];
                echo '</td>';
                //Building
                echo '<td>';
                echo $class["fldBuildingArea"];
                echo '</td>';
                //Room
                echo '<td>';
                echo $class["fldRoom"];
                echo '</td>';
                //Taking attendance
                echo '<td>';
                if($class["fldTakeAttendance"] == 1){
                  echo 'Yes';
                } else {
                  echo 'No';
                }
                echo '</td>';
                echo '</tr>';
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>
