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
        ?>
      </h1>
      <!--Manual Attendence Form -->
      <form class="form-inline">
        <h3>Manually Add Attendence</h3>
        <div class="input-group">
          <span class="input-group-addon">Student NetID:</span>
          <input type="text" class="form-control" placeholder="jdoe">
        </div>
        <div class="input-group">
          <span class="input-group-addon">Date:</span>
          <input type="datetime-local" class="form-control">
        </div>
        <div class="input-group">
          <div class="input-group-btn">
            <button class="btn btn-primary" type="submit">Submit</button>
          </div>
        </div>
      </form>
      <!--Upload Roster Form -->
      <form class="form-inline" action=<?php echo '"scripts/upload.php?sectionId=', urlencode($sectionKey), '"'?> method="POST" enctype="multipart/form-data">
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
    </div>
  </body>
</html
