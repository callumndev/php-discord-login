<?php
include "partials/session.php";
require_once "lib/Requests/src/Autoload.php";
WpOrg\Requests\Autoload::register();


$baseAuthorizationURL = "https://discord.com/oauth2/authorize";
$tokenURL             = "https://discord.com/api/oauth2/token";
$redirectURI          = "http://localhost:3000/login.php";
$clientID             = "1030584104552505395";
$clientSecret         = "-UMJUTRPNPzUWrAV1096y_GqOjxyaOkf";
$scopes               = ["identify", "guilds"];



function getErrorDescription($e): string
{
    return match ($e) {
        "access_denied" => "The resource owner or authorization server denied the request",
        default => "Unknown error",
    };
}



// Check if the user has already logged in
if (LOGGED_IN) {
?>
    <h1>already logged in</h1>
    <p>redirecting to home...</p>
<?php
    header("Refresh:3; url=index.php");
    die();
}


// Code will be set if it's a response from discord
if (
    isset($_GET["code"]) ||
    isset($_GET["error"])
) {
    // Handle error responses
    if (isset($_GET["error"])) {
        echo getErrorDescription($_GET["error"]);
        echo "<br><a href='/'>go home</a>";
        die();
    }

    // Success
    $headers = array(
        'Accept' => 'application/json',
        'Content-Type' => 'application/x-www-form-urlencoded'
    );
    $data = array(
        "client_id" => $clientID,
        "client_secret" => $clientSecret,
        "grant_type" => "authorization_code",
        "code" => $_GET['code'],
        "redirect_uri" => $redirectURI,
    );
    $request = WpOrg\Requests\Requests::post($tokenURL, $headers, $data);
    $response = json_decode($request->body);

    // Validate HTTP code
    if ($request->status_code != 200) {
        echo "Invalid HTTP status code ". $request->status_code;
        echo "<br><a href='/'>go home</a>";
        die();
    }

    // Validate the code
    if (isset($response->error)) {
        echo $response->error_description;
        echo "<br><a href='/'>go home</a>";
        die();
    }

    // Store access token in session
    $_SESSION["token"] = $response->access_token;
    header("Location: /");
    die();
}


// The user is not logged in, start Discord OAuth2 process
$params = array(
    "client_id" => $clientID,
    "redirect_uri" => $redirectURI,
    "response_type" => "code",
    "scope" => join(" ", $scopes)
);

// Redirect to Discord authorization URL
header("Location: " . $baseAuthorizationURL . "?" . http_build_query($params));
die();
