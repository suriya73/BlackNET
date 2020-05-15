<?php
include_once 'session.php';
include_once 'classes/Clients.php';
include_once 'getcontery.php';

$client = new Clients;
$allClients = $client->getClients();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'components/meta.php';?>
  <title>BlackNET - Main Interface</title>
  <?php include_once 'components/css.php';?>
  <link href="asset/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <link href="asset/vendor/responsive/css/responsive.dataTables.css" rel="stylesheet">
  <link href="asset/vendor/responsive/css/responsive.bootstrap4.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="asset/vendor/jvector/css/jvector.css">
</head>

<body id="page-top">
  <?php include_once 'components/header.php';?>
  <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">

        <?php if ($_SESSION['login_user'] == "admin"): ?>
          <?php $utils->show_dismissible_alert('<b> Warning!</b> You are loging in as "admin" please change your <b>username</b> for better security.', "warning", "exclamation-triangle");?>
        <?php endif;?>

        <?php if ($user->isTwoFAEnabled($_SESSION['login_user']) == "off"): ?>
          <?php $utils->show_dismissible_alert('<b> Warning!</b> Your account is not protected by two-factor authentication. Enable two-factor authentication now from <a href="authsettings.php" class="alert-link">here</a>.', "warning", "exclamation-triangle");?>
        <?php endif;?>

        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Slaves Menu</a>
          </li>
        </ol>

        <?php include_once 'components/stats.php';?>

        <form method="POST" action="sendcommand.php" id="Form1" name="Form1">
          <?php $utils->show_input("csrf", $utils->sanitize($_SESSION['csrf']));?>

          <?php include_once 'components/clientsList.php';?>

          <div class="row">
            <?php include_once 'components/commands.php';?>
            <div class="col">
              <div class="card mb-3">
                <div class="card-header">
                  <i class="fas fa-map-marker-alt"></i>
                  Map Visualization
                </div>
                <div class="card-body">
                  <div class="map-container">
                    <div id="clientmap" name="clientmap" class="jvmap-smart"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include_once 'components/footer.php';?>

  <?php include_once 'components/js.php';?>

  <script src="asset/vendor/datatables/jquery.dataTables.js"></script>
  <script src="asset/vendor/datatables/dataTables.bootstrap4.js"></script>
  <script src="asset/vendor/responsive/dataTables.responsive.js"></script>
  <script src="asset/vendor/responsive/responsive.bootstrap4.js"></script>
  <script src="asset/js/demo/datatables-demo.js"></script>
  <script src="asset/vendor/jvector/js/core.js"></script>
  <script src="asset/vendor/jvector/js/world.js"></script>
  <script>
    $('.alert').alert();

    $('#select-all').click(function(event) {
      if (this.checked) {
        $(':checkbox').each(function() {
          this.checked = true;
        });

      } else {

        $(':checkbox').each(function() {
          this.checked = false;
        });
      }
    });

    document.addEventListener("DOMContentLoaded", function() {
      $.getJSON('counter.php', {}, function(data) {
        var dataC = eval(data);
        var clients = [];
        $.each(dataC.countries, function() {
          clients[this.id] = this.value;
        });

        $('#clientmap').vectorMap({
          map: 'world_mill',
          backgroundColor: 'transparent',
          series: {
            regions: [{
              values: clients,
              scale: ['#e6e6e6', '#007bff'],
              normalizeFunction: 'polynomial'
            }]
          },
          regionStyle: {
            hover: {
              fill: '#0056b3',
              cursor: 'pointer'
            }
          },

          onRegionTipShow: function(e, el, code) {
            if (typeof clients[code] != 'undefined') {
              el.html(el.html() + ' (' + clients[code] + ' Clients)');
            }
          }
        });
      });
    });
  </script>
</body>

</html>