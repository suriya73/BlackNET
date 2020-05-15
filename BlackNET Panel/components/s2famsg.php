<?php
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == "error") {
        $utils->show_alert("Authentication Code is incorrect", "danger", "times-circle");
    }

    if (isset($_GET['code'])) {
        if ($_GET['code'] != "error") {
            if ($_GET['code'] == "enable") {
                $utils->show_alert("2 Factor Authentication has been enabled", "success", "check-circle");
            } elseif ($_GET['code'] == "disable") {
                $utils->show_alert("2 Factor Authentication has been disbaled", "success", "check-circle");

            }
        }
    }

    if ($_GET['msg'] == "csrf") {
        $utils->show_alert("CSRF Token is invalid.", "danger", "times-circle");
    }
}
