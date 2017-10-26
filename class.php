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
          $classKay = $_GET["cls"];
          echo $CLASS_LIST[$classKey][' Subj'] . $CLASS_LIST[$classKey]['#'] . $CLASS_LIST[$classKey][' Sec'];
        ?>
      </h1>
    </div>
  </body>
</html
