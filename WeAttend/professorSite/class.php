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
          $classKey = $_GET["cls"];
          echo $CLASS_LIST[$classKey][' Subj'] . ' ' . $CLASS_LIST[$classKey]['#'] . ' ' .$CLASS_LIST[$classKey]['Sec'];
        ?>
      </h1>
      <form action="upload.php" method="post" enctype="multipart/form-data">
        Select CSV to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload File" name="submit">
      </form>
      <p>

      </p>
    </div>
  </body>
</html
