<?php
include_once 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include_once 'components/meta.php';?>
    <title>BlackNET - Example Template</title>
    <?php include_once 'components/css.php';?>
  </head>

  <body id="page-top">
    <?php include_once 'components/header.php';?>

    <div id="wrapper">
      <div id="content-wrapper">
        <div class="container-fluid">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="#">Example Template</a>
            </li>
          </ol>
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-cube"></i>
              BlackNET Template
            </div>

            <div class="card-body">
              <div class="container container-special"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include_once 'components/footer.php';?>

    <?php include_once 'components/js.php';?>
  </body>
</html>
