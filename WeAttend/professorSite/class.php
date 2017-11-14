<!DOCTYPE html>
<html lang="en">
  <?php
    include 'header.php';
  ?>
  <body>
    <?php
      include 'nav.php';
    ?>

    <div class="container-fluid">
      <h1>
        <?php
          $sectionKey = $_GET["sectionId"];
          echo $CLASS_LIST[$sectionKey]['fldClassSubject'] . ' ' . sprintf("%'.03d",$CLASS_LIST[$sectionKey]['fldCourseNum']) . ' ' .$CLASS_LIST[$sectionKey]['fldSection'];
          if ($CLASS_LIST[$sectionKey]['fldTakeAttendance'] == 0) {
            echo '</h1>';
            echo '<h2>You are not currently taking attendance for this class</h2>';
            echo '<form "form-inline" action="scripts/toggleAttendance.php?sectionId='
                  . urlencode($sectionKey). '&turn=on" method="POST"><h3>Start Taking Attendance:</h3>'
                  . '<button class="btn btn-primary" name="submit" type="submit">Submit</button>'
                  . '</form>';
            exit;
          }

        ?>
      </h1>
      <!--Get Attendence Form-->
      <form class="form-inline" action=<?php echo '"scripts/getAttendance.php?sectionId='. urlencode($sectionId). '"'?> method="POST">
        <h3>Attendance:</h3>
        <div class="input-group">
          <span class="input-group-addon">Date:</span>
          <input type="date" name="attendanceDate" class="form-control">
          <div class="input-group-btn">
            <button class="btn btn-primary" name="submitAttend" type="submit">Submit</button>
          </div>
        </div>
      </form>
      <!--Manual Attendence Form -->
      <form class="form-inline" action=<?php echo '"scripts/addManualAttendance.php?sectionId='. urlencode($sectionKey). '"'?> method="POST">
        <h3>Manually Add Attendence</h3>
        <div class="input-group">
          <span class="input-group-addon">Student NetID:</span>
          <input type="text" name="studentId" class="form-control" placeholder="jdoe">
        </div>
        <div class="input-group">
          <span class="input-group-addon">Date:</span>
          <input type="date" name="attendanceDate" class="form-control">
        </div>
        <div class="input-group">
          <div class="input-group-btn">
            <button class="btn btn-primary" name="submit" type="submit">Submit</button>
          </div>
        </div>
      </form>
      <!--Upload Roster Form -->
      <form class="form-inline" action=<?php echo '"scripts/sendClassRoster.php?sectionId=', urlencode($sectionKey), '"'?> method="POST" enctype="multipart/form-data">
        <h3>Upload Student Roster:</h3>
        <div class="input-group">
          <label class="input-group-btn">
            <span class="btn btn-default">
              Browse&hellip; <input type="file" name="Upload" id="Upload" style="display:none;" onchange="$('#upload-file-info').val($(this).val());">
            </span>
          </label>
          <input type="text" class="form-control" id="upload-file-info" readonly>
          <label class="input-group-btn">
            <span class="btn btn-primary">
              Submit<input type="submit" name="submit" style="display:none;">
            </span>
          </label>
        </div>
        <span class="help-block">Only .csv or .txt files</span>
      </form>
      <!--Turn attendance off-->
      <form "form-inline" action=<?php echo '"scripts/toggleAttendance.php?sectionId='. urlencode($sectionKey) . '&turn=off"'?> method="POST">
        <h3>Stop Taking Attendance:</h3>
        <button class="btn btn-primary" name="submit" type="submit">Submit</button>
      </form>
    </div>
  </body>
</html
