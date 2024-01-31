<?php

// Support DEBUG cookie
if ($_COOKIE['debug'] == 'true') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(-1);
} else {
    error_reporting(0);
}


require_once("vendor/autoload.php");
spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.php';
});

$ini = parse_ini_file("config.ini", true)["am"];

date_default_timezone_set('America/New_York');

try {
    $pdo = new PDO(
        'mysql:host=' . $ini['db_host'] . ';dbname=' . $ini['db_name'] . ';charset=utf8mb4',
        $ini['db_username'],
        $ini['db_password'],
        array(
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
                            PDO::ATTR_PERSISTENT => false
                        )
    );
} catch (Exception $e) {
    die($e);
}

$config = array(
    'dbo' => $pdo,
    'appName' => 'Master Availability Manager'
);

// Setup SAML
$baseUrl = "https://dev.rybel-llc.com:447/";
$keycloakUrl = "https://dev.rybel-llc.com:8443/realms/Rybel";

$samlHelper = new Rybel\backbone\SamlAuthHelper($baseUrl, 
                            $keycloakUrl, 
                            file_get_contents("../certs/idp.cert"), 
                            file_get_contents('../certs/public.crt'), 
                            file_get_contents('../certs/private.pem'),
                            $_COOKIE['debug'] == 'true');
