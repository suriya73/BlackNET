<?php
class Utils
{
    public function sanitize($value)
    {
        $data = trim($value);
        $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");
        $data = filter_var($data, FILTER_SANITIZE_STRING);
        return $data;
    }

    public function unSanitize($value)
    {
        $data = htmlspecialchars_decode($value, ENT_QUOTES);
        $data = trim($data);
        $data = strip_tags($data);
        return $data;
    }

    public function show_alert($message, $style = "primary", $icon = null)
    {
        if ($icon != null) {
            $icon = '<span class="fa fa-' . $this->sanitize($icon) . '"></span>';
        } else {
            $icon = "";
        }
        echo '<div class="alert alert-' . $this->sanitize($style) . '">' . $icon . " " . $this->sanitize($message) . '</div>';
        return;
    }

    public function show_dismissible_alert($message, $style = "primary", $icon = null)
    {
        if ($icon != null) {
            $icon = '<span class="fa fa-' . $this->sanitize($icon) . '"></span>';
        } else {
            $icon = "";
        }
        echo '<div class="alert alert-' . $this->sanitize($style) . ' alert-dismissible fade show"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' . $icon . " " . $message . '</div>';
        return;
    }

    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    public function show_input($name, $value)
    {
        echo '<input type="text" value="' . $this->sanitize($value) . '" name="' . $this->sanitize($name) . '" hidden />';
        return;
    }

    public function callAPI($method, $url, $data)
    {
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }

                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }

                break;
            default:
                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }

        }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'APIKEY: 111111111111111111111',
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        return $result;
    }

    public function base64_encode_url($string)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($string));
    }

    public function base64_decode_url($string)
    {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $string));
    }
}
