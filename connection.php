<?php

require("config.php");

function HWdbconnect()
    {
        // database connection
        try {
            $conn = new PDO("mysql:host=".SERVERNAME.";dbname=".DATABASE."; charset=utf8", USERNAME, PASSWORD);
            $conn->exec('SET NAMES utf8');
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // $conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
            $conn->setAttribute(PDO::ATTR_PERSISTENT, true);

            // debug("Connected successfully");
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

function login($conn)
{
    // Extract the action mode in POST then GET
    
 
    if (array_key_exists("mode", $_POST)) {
        $mode=$_POST["mode"];
        // debug("There is a mode in POST");
    } else {
        if (array_key_exists("mode", $_GET)) {
            $mode=$_GET["mode"];
        } else {
            $mode="showTasks";
        }
    }
    $_SESSION["mode"]=$mode;
    
    // debug("Session mode set to $mode");
    // Check if we are already logged. If so, return the action mode
    
    if (!empty($_SESSION["login"])) {
        // debug("Already logged");
        return $_SESSION["mode"];
    }

    // Check if we are trying to log in
    
    if ($_SESSION["mode"]=="login") {
        // @debug("checking for login ".$_POST['username']." ".$_POST['password']);

        $id=checkLogin($_POST['username'], $_POST["password"], $conn);
        // debug("received value $id");

        // wrong username/password
        if(is_null($id)){
            $_SESSION["wrong"]=true;
            return "login";
        }
        
        // debug("login successful");
        
        
        $_SESSION["login"]=true;
        $_SESSION['wrong']=false;
        $_SESSION["userId"]=$id;
        $_SESSION["username"]=$_POST['username'];
        $_SESSION["mode"]="showTasks";

        // TASK ACTIVE INITIALISATION FOR SPIPOLL
        // initMnhnTaskActive($conn);

        // set a default project if necessary
        if (empty($_SESSION["project"]))
                // $_SESSION['project'] = "Headwork";
                $_SESSION['project'] = "SPIPOLL";
            

        return "showTasks";
    }
        // check if we are trying to create an account
    if ($_SESSION["mode"]=="register") {
        // debug("Detecting registering mode");
        return "register";
    }

    if ($_SESSION["mode"]=="registered") {
        // debug("Detecting registered mode");
        return "registered";
    }
    
    // we are not supposed to reach this point. Anyway:
    return "login";
}

function logout($conn)
{
    if ($_SESSION['username']=="anonymous") {
        cleanDb($conn);
    }
    unset($_SESSION);
    session_destroy();
    // Suppression des cookies de connexion automatique
    setcookie('login', '');
    setcookie('pass_hache', '');
    showLogin($conn);
}

function addUser($conn)
{
    $stmt = $conn->prepare("select userId from Users where name=:username");
    // debug("addUser:username:".$_POST["username"]." !");
    $stmt->bindParam(':username', $_POST["username"], PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount()>0 && $_POST["username"]!="anonymous") {
        //RESTART SWITCH
        showregister($conn, true);
    } else {
        $stmt = $conn->prepare("insert into Users(name,hashed_password) values(:name,:pwd)");
        $stmt->bindParam(':name', $_POST["username"], PDO::PARAM_STR);
        $stmt->bindParam(':pwd', $pwd, PDO::PARAM_STR);
        $pwd=password_hash($_POST["password"], PASSWORD_DEFAULT);
        $stmt->execute() or die(mysql_error());
        $stmt = $conn->prepare("select * from Users where name=:username AND hashed_password=:pwd");
        $stmt->bindParam(':username', $_POST["username"], PDO::PARAM_STR);
        $stmt->bindParam(':pwd', $pwd, PDO::PARAM_STR);
        $stmt->execute();
        $result =$stmt->setFetchMode(PDO::FETCH_ASSOC);
        $line=$stmt->fetch();
        $_SESSION["login"]=true;
        $_SESSION["userId"]=$line['userId'];
        $_SESSION['username']=$line['name'];
        


        $stmtNewUser = $conn->prepare("select userId,name,hashed_password from Users where name=:username");
        $stmtNewUser->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
        $stmtNewUser->execute();
        $result =$stmtNewUser->setFetchMode(PDO::FETCH_ASSOC);
        $line=$stmtNewUser->fetch();

    }
    
}

function checkLogin($username, $password, $conn)
    {
        global $VIEW;
        $stmt = $conn->prepare("select userId,name,hashed_password from Users where name=:username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        // debug("checking for $username");
        $result =$stmt->setFetchMode(PDO::FETCH_ASSOC);
        $line=$stmt->fetch();
        if ($line){
            // debug("identified as ".$line['id']);
            if (password_verify($password, $line['hashed_password'])) {
                // debug("password ok");
                return $line['userId'];
            }
            // debug("wrong password");
            return null;
        }
        // debug("wrong user");
        return null;
     }