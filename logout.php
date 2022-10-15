<?php
include "partials/session.php";

session_destroy();
header("Location: /");
