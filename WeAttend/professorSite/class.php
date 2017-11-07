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

      <!--Upload Roster Form -->
      <div class="row">
        <div class="col-sm-6">
          <form action=<?php echo '"upload.php?sectionId=', urlencode($sectionKey), '"'?> method="post" enctype="multipart/form-data">
            <h3>Upload Student Roster:</h3>
            <div class="form-group">
              <div class="input-group">
                <label class="input-group-btn">
                  <span class="btn btn-default">
                    Browse&hellip; <input type="file" style="display:none;" onchange="$('#upload-file-info').val($(this).val());">
                  </span>
                </label>
                <input type="text" class="form-control" id="upload-file-info" readonly>
                <label class="input-group-btn">
                  <span class="btn btn-primary">
                    Submit<input type="submit" style="display:none;">
                  </span>
                </label>
              </div>
              <span class="help-block">
                Only .csv or .txt files
              </span>
              <div class="form-group">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html
