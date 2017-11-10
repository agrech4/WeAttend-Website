<!-- Navbar -->
<?php
  $parentFolder = end(explode("/",$PATH_PARTS['dirname']));
  echo "<!--" . $parentFolder . "-->";
  $homeDir = "";
  if($parentFolder == "scripts") {
    $homeDir = "../";
  }
?>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href=<?php echo '"' . $homeDir . 'index.php"';?>>WeAttend</a>
    </div>
    <ul class="nav navbar-nav">
      <li <?php if(FILE_NAME == 'index'){echo ' class="active"';}?>><a href=<?php echo '"' . $homeDir . 'index.php"'?>>Class List</a></li>
      <li <?php if(FILE_NAME == 'class'){echo ' class="dropdown active"';}else{echo ' class="dropdown"';}?>><a class="dropdown-toggle" data-toggle="dropdown" href="#">My Classes<span class="caret"></span></a>
        <ul class="dropdown-menu">
          <?php
            foreach($CLASS_LIST as $class) {
              echo '<li><a href="' . $homeDir . 'class.php?sectionId=', urlencode($class['pmkSectionId']), '">';
              echo $class["fldClassSubject"] . " " . sprintf("%'.03d",$class["fldCourseNum"]) . " " . $class["fldSection"];
              echo '</a></li>';
            }
          ?>
        </ul>
      </li>
    </ul>
    <p class="navbar-right navbar-text"><?php echo $USER_NET_ID;?> &nbsp&nbsp</p>
  </div>
</nav>
