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
              <th>Title</th>
              <th>Current Enrolled</th>
              <th>Time</th>
              <th>Days</th>
              <th>Building</th>
              <th>Room</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach($CLASS_LIST as $key => $class) {
                echo '<tr>';
                //Class
                echo '<td><a href="class.php?cls=', urlencode($key), '">';
                echo $class[" Subj"];
                echo ' ';
                echo $class["#"];
                echo ' ';
                echo $class["Sec"];
                echo '</a></td>';
                //Title
                echo '<td>';
                echo $class["Title"];
                echo '</td>';
                //Current Enrolled
                echo '<td>';
                echo $class["Current Enrollment"];
                echo '</td>';
                //Time
                echo '<td>';
                echo $class["Start Time"];
                echo '-';
                echo $class["End Time"];
                echo '</td>';
                //Days
                echo '<td>';
                echo $class["Days"];
                echo '</td>';
                //Building
                echo '<td>';
                echo $class["Bldg"];
                echo '</td>';
                //Room
                echo '<td>';
                echo $class["Room"];
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
