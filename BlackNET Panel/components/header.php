  <nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
    <a class="navbar-brand mr-1" href="index.php"><img src="favico.png" width="30" height="30" alt="">BlackNET</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item <?php if (strpos(urlencode(htmlentities($_SERVER['REQUEST_URI'])), "index.php")) {
                              echo ("active");
                            } ?>">
          <a class="nav-link" href="index.php"><span class="fa fa-home"></span> Home</a>
        </li>
        <li class="nav-item <?php if (strpos(urlencode(htmlentities($_SERVER['REQUEST_URI'])), "viewlogs.php")) {
                              echo ("active");
                            } ?>">
          <a class="nav-link" href="viewlogs.php"><span class="fa fa-clipboard-check"></span> View Logs</a>
        </li>
        <li class="nav-item <?php if (strpos(urlencode(htmlentities($_SERVER['REQUEST_URI'])), "settings.php")) {
                              echo ("active");
                            } ?>">
          <a class="nav-link" href="settings.php"><span class="fa fa-wrench"></span> Network Settings</a>
        </li>
        <li class="nav-item <?php if (strpos(urlencode(htmlentities($_SERVER['REQUEST_URI'])), "changePassword.php")) {
                              echo ("active");
                            } ?>">
          <a class="nav-link" href="changePassword.php"><span class="fa fa-user"></span> User Settings</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="modal" data-target="#logoutModal"><span class="fa fa-sign-out-alt"></span> Logout</a>
        </li>
      </ul>
    </div>
  </nav>