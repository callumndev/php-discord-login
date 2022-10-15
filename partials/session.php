<?php
require_once __DIR__ . "/../lib/Requests/src/Autoload.php";
WpOrg\Requests\Autoload::register();

function getUserInfo($token=null) {
    if (!$token && isset($_SESSION["token"])) {
        $token = $_SESSION["token"];
    } else {
        return null;
    }

    $userHeaders = array('Authorization' => 'Bearer ' . $token);
    $userRequest = WpOrg\Requests\Requests::get("https://discord.com/api/users/@me", $userHeaders);

    return $userRequest->status_code == 200 ? json_decode($userRequest->body) : null;
}


if (session_id() == '') {
    session_start();
}

// Define variables
$user = getUserInfo();
define("LOGGED_IN", $user != null);
