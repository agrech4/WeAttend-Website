<!-- Navbar -->
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">WeAttend</a>
    </div>
    <ul class="nav navbar-nav">
      <li <?php if($FILE_NAME == 'index'){echo ' class="active"';}?>><a href="index.php">Home</a></li>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">My Classes<span class="caret"></span></a>
        <ul class="dropdown-menu">
          <?php
            foreach($CLASS_LIST as $class) {
              echo '<li><a href="#">';
              echo $class[" Subj"];
              echo ' ';
              echo $class["#"];
              echo '</a></li>';
            }
          ?>
        </ul>
      </li>
      <li><a href="#">Class List</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
    </ul>
    <p class="navbar-text navbar-right">jdoe</p>
  </div>
</nav>
