<?php
header( 'content-type: text/html; charset=utf-8' );


require("HTML.php");
require("connection.php");
require("pages.php");
require("actions.php");

session_start();


$conn = HWdbconnect();

$mode = login($conn);
// $mode = "init";
$_SESSION['mode']=$mode;

$VIEW = array("MAIN" => "");


switch ($mode) {

    case "login":
        showLogin($conn);
        break;
    case "register":
        showregister($conn, false);
        break;

    case "registered":
        addUser($conn);
        break;

    case "restart":
        restart($conn);
        break;

    case "logout":
        logout($conn);
        
        break;
    case "init":
        echo "init sd";
        break;
    case "AddVideo":
        addVideo($conn);
        break;
    case "ajout":
        ajout($conn);
        break;
    case "ViewVideo":
        echo "init sd";
        break;
}

if (isset($_SESSION['username'])) {
    // debug("logged");
//    $stmt = $conn->prepare("select wallet from UserProfile where id=:id");
//    $stmt->bindParam(':id', $_SESSION["id"], PDO::PARAM_INT);
//    $stmt->execute();
//    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
//    $line = $stmt->fetch();
    @$var = array("ID" => $_SESSION['username']);
} 



require("template.php");