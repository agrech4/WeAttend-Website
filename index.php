<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Index Test</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <?php
      print '<!-- begin including lobraries -->';
      include 'lib/constants.php';
      print '<!-- libraries complete -->';
    ?>
  </head>
  <body>
    <?php
      include 'nav.php';
    ?>

    <div class="container-fluid">
      <h1>Welcome!</h1>
      <p>Keep track of attendence with WeAttend<sup>TM</sup></p>
    </div>
  </body>
</html>
