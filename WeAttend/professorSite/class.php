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
      <?php
        $sectionId = $_GET["sectionId"];
        if (isset($_POST['submitGetAttend'])) {
          include 'scripts/updateAttendance.php';
          include 'scripts/getAttendance.php';
        }
        if (isset($_POST['submitManAttend'])) {
          include 'scripts/addManualAttendance.php';
        }
        if (isset($_POST['submitUpload'])) {
          include 'scripts/sendClassRoster.php';
        }
        echo '<h1>';
        echo $CLASS_LIST[$sectionId]['fldClassSubject'] . ' ' . sprintf("%'.03d",$CLASS_LIST[$sectionId]['fldCourseNum']) . ' ' .$CLASS_LIST[$sectionId]['fldSection'];
        echo '</h1>';
        if ($CLASS_LIST[$sectionId]['fldTakeAttendance'] == 0) {
          echo '<h2>You are not currently taking attendance for this class</h2>';
          echo '<form "form-inline" action="class.php?sectionId='
                . urlencode($sectionId). '&turn=on" method="POST"><h3>Start Taking Attendance:</h3>'
                . '<button class="btn btn-primary" name="submitToggle" type="submit">Submit</button>'
                . '</form>';
          exit;
        }
        if (isset($message)){
          echo '<div class="alert alert-' . $type . ' alert-dismissable">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
                  . $message .
                '</div>';
        }
        if (isset($_POST['submitGetAttend']) and $success) {
          $fileName = $CLASS_LIST[$sectionId]['fldClassSubject'] . sprintf("%'.03d",$CLASS_LIST[$sectionId]['fldCourseNum'])
                      . $CLASS_LIST[$sectionId]['fldSection'] . '_' . $date . '.csv';
          $tableHeaders = array('Student','Time In','Time Out','Total Time','Attendance');
          echo '<h3>Attendance for ' . date('M j, Y',strtotime($date)) . '</h3>
          <div class="table-responsive">
            <table class="table table-striped table-bordered">
              <thead>
                <tr>';
          foreach ($tableHeaders as $header) {
            echo '<th>' . $header . '</th>';
          }
          echo '</tr>
              </thead>
              <tbody>';
          foreach ($stuAttendance as $student) {
            echo '<tr>';
            echo '<td>' . $student['fnkStuNetId'] . '</td>';
            echo '<td>' . date('G:i',strtotime($student['fldTimeIn'])) . '</td>';
            echo '<td>' . date('G:i',strtotime($student['fldTimeOut'])) . '</td>';
            echo '<td>' . $student['fldTimeInClass'] . ' min</td>';
            echo '<td>';
            if ($student['fldAttend']) {
              echo 'Yes';
            } else {
              echo 'No';
            }
            echo '</td>';
            echo '</tr>';
          }
          echo '</tbody>
              </table>
            </div>
            <a class="btn btn-primary" role="button" target="_blank" href="scripts/download.php?'
            . http_build_query(array('fileName' => $fileName, 'headers' => $tableHeaders, 'attendanceRecords' =>$stuAttendance))
            . '">Download</a>';
        }
      ?>
      <!--Get Attendence Form-->
      <form class="form-inline" action=<?php echo '"class.php?sectionId='. urlencode($sectionId). '"'?> method="POST">
        <h3>Get Attendance:</h3>
        <div class="input-group">
          <span class="input-group-addon">Date:</span>
          <input type="date" name="attendanceDate" class="form-control">
          <div class="input-group-btn">
            <button class="btn btn-primary" name="submitGetAttend" type="submit">Submit</button>
          </div>
        </div>
      </form>
      <!--Manual Attendence Form -->
      <form class="form-inline" action=<?php echo '"class.php?sectionId='. urlencode($sectionId). '"'?> method="POST">
        <h3>Manually Add Attendence</h3>
        <div class="input-group">
          <span class="input-group-addon">Student NetID:</span>
          <input type="text" name="studentId" class="form-control" placeholder="jdoe">
          <span class="input-group-addon">Date:</span>
          <input type="date" name="attendanceDate" class="form-control">
          <div class="input-group-btn">
            <button class="btn btn-primary" name="submitManAttend" type="submit">Submit</button>
          </div>
        </div>
      </form>
      <!--Upload Roster Form -->
      <form class="form-inline" action=<?php echo '"class.php?sectionId=', urlencode($sectionId), '"'?> method="POST" enctype="multipart/form-data">
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
              Submit<input type="submit" name="submitUpload" style="display:none;">
            </span>
          </label>
        </div>
        <span class="help-block">Only .csv or .txt files</span>
      </form>
      <!--Turn attendance off-->
      <form "form-inline" action=<?php echo '"class.php?sectionId='. urlencode($sectionId) . '&turn=off"'?> method="POST">
        <h3>Stop Taking Attendance:</h3>
        <button class="btn btn-primary" name="submitToggle" type="submit">Submit</button>
      </form>
    </div>
  </body>
</html
