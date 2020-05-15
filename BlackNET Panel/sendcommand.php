<?php
include_once 'session.php';
include_once 'classes/Clients.php';
include_once 'classes/POST.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'components/meta.php';?>
  <title>BlackNET - Execute Command</title>
  <?php include_once 'components/css.php';?>
  <style type="text/css">
  </style>
</head>

<body id="page-top">
  <?php include_once 'components/header.php';?>
  <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Command Menu</a>
          </li>
        </ol>
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-bolt"></i>
            Command Menu
          </div>
          <div class="card-body">
            <form method='POST'>
              <div class="container container-special">
                <?php include_once 'components/commandController.php';?>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include_once 'components/footer.php';?>

  <?php include_once 'components/js.php';?>

  <script>
  jQuery(document).ready(function ($) {
    $("select[name=attacktype]").change(function () {
      $("select[name=attacktype] option:selected").each(function () {
        var value = $(this).val();
        if (value == "TCP Attack") {
          $("#port, #portlabel, #portdesc").show();
        } else {
          $("#port, #portlabel, #portdesc").hide();
        }
      });
    });
  });
</script>
</body>

</html>