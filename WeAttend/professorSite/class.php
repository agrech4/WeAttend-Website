<!DOCTYPE html>
<?php

/**
 * Author: Alex Grech IV (alexiv42@gmail.com)
 * This file shows forms for the selected class, which allow the following:
 * Showing attendance data for a specific date;
 * Adding manual attendance for a student on a date;
 * Upload a student roster;
 * View current roster;
 * Toggle whether or not attendance is being taken for the given class.
*/

  include 'header.php';
  include 'nav.php';
?>
<div class="container" style="padding-bottom: 10em">
  <?php
    $sectionId = $_GET["sectionId"];
    // check which php scripts to include
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
    if (isset($_POST['submitRoster'])) {
      include 'scripts/getRoster.php';
    }
    // Prints out class info
    echo '<h1>';
    echo $CLASS_LIST[$sectionId]['fldClassSubject'] . ' ' . sprintf("%'.03d",$CLASS_LIST[$sectionId]['fldCourseNum']) . ' ' .$CLASS_LIST[$sectionId]['fldSection'];
    echo '</h1>';
    // Page if attendance is not being taken for the chosen class
    if ($CLASS_LIST[$sectionId]['fldTakeAttendance'] == 0) {
      echo '<h2>You are not currently taking attendance for this class</h2>';
      echo '<form "form-inline" action="class.php?sectionId='
            . urlencode($sectionId). '&turn=on" method="POST"><h3>Start Taking Attendance:</h3>'
            . '<button class="btn btn-primary" name="submitToggle" type="submit">Submit</button>'
            . '</form>';
      exit;
    }
    // Print Out Feedback Message
    if (isset($message)){
      echo '<div class="alert alert-' . $type . ' alert-dismissable">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
              . $message .
            '</div>';
    }
    // Print Out Attendance Data
    if (isset($_POST['submitGetAttend']) and $success) {
      $fileName = $CLASS_LIST[$sectionId]['fldClassSubject'] . sprintf("%'.03d",$CLASS_LIST[$sectionId]['fldCourseNum'])
                  . $CLASS_LIST[$sectionId]['fldSection'] . '_' . $date . '.csv';
      $tableHeaders = array('Student','Last Time In','Last Time Out','Total Time','Present');
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
        //fix time in class if the time out field is empty
        if (!empty($student['fldTimeIn'])) {
          if (empty($student['fldTimeOut'])) {
            $today = date_create('now');
            $classEndTime = new DateTime();
            date_timestamp_set($classEndTime, strtotime($CLASS_LIST[$sectionId]['fldEnd'],strtotime($date)));
            if ($today > $classEndTime) {
              $student['fldTimeOut'] = $CLASS_LIST[$sectionId]['fldEnd'];
              $student['fldTimeInClass'] += round((strtotime($CLASS_LIST[$sectionId]['fldEnd']) - strtotime($student['fldTimeIn']))/60);
            } else {
              $student['fldTimeInClass'] += round((time() - strtotime($student['fldTimeIn']))/60);
            }
          }
        }
        echo '<tr>';
        echo '<td>' . $student['fnkStuNetId'] . '</td>';
        echo '<td>' . (!empty($student['fldTimeIn'])?date('G:i',strtotime($student['fldTimeIn'])):'--') . '</td>';
        echo '<td>' . (!empty($student['fldTimeOut'])?date('G:i',strtotime($student['fldTimeOut'])):'--') . '</td>';
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
    // Print out Student Roster
    if (isset($_POST['submitRoster']) and $success) {
      echo '<h3>Student Roster</h3>
            <ul class="list-group list-inline">';
      foreach($stuRoster as $student) {
        echo '<li class="list-group-item list-inline-item" style="width: 10em; text-align:center">' . $student['fldStuNetId'] . '</li>';
      }
      echo '</ul>';
    }
  ?>
  <!--Get Attendence Form-->
  <form class="form-inline" action=<?php echo '"class.php?sectionId='. urlencode($sectionId). '"'?> method="POST">
    <h3>Get Attendance:</h3>
    <div class="input-group">
      <span class="input-group-addon">Date:</span>
      <input type="date" name="attendanceDate" class="form-control" placeholder="mm/dd/yyyy">
      <div class="input-group-btn">
        <button class="btn btn-primary" name="submitGetAttend" type="submit">Submit</button>
      </div>
    </div>
  </form>
  <!--Manual Attendence Form -->
  <form class="form-inline" action=<?php echo '"class.php?sectionId='. urlencode($sectionId). '"'?> method="POST">
    <h3>Manually Add Attendence:</h3>
    <div class="input-group">
      <span class="input-group-addon">Student NetID:</span>
      <input type="text" name="studentId" class="form-control" placeholder="jdoe">
      <span class="input-group-addon">Date:</span>
      <input type="date" name="attendanceDate" class="form-control" placeholder="mm/dd/yyyy">
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
          Browse&hellip; <input type="file" name="Upload" id="Upload" style="display:none;" onchange="$('#upload-file-info').val((($(this).val()).split('\\')).pop());">
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
  <!--Show current roster-->
  <form class="form-inline" action<?php echo '"class.php?sectionId='. urlencode($sectionId) . '"'?> method="POST">
    <button class="btn btn-primary btn-large" name="submitRoster" type="submit">Show Current Roster</button>
  </form>
  <!--Turn attendance off-->
  <form class="form-inline" action=<?php echo '"class.php?sectionId='. urlencode($sectionId) . '&turn=off"'?> method="POST">
    <button class="btn btn-danger btn-large" name="submitToggle" type="submit" style="margin-top: 1em">Stop Taking Attendance</button>
  </form>
</div>
<?php
  include 'footer.php';
?>
