<?php

    /**
    * Configuration file
    *
    */    

// Set DEBUG to true to see plenty of error messages
  //define("DEBUG",false);
define("DEBUG",true);

// Location of the log file
    //define("LOGFILENAME","log/log.txt");
    
    
// Add more error messages from PHP
    ini_set('display_startup_errors',1);
    ini_set('display_errors',1);
    error_reporting(-1);
    
// Mysql parameters

    define("SERVERNAME","localhost");
    define("USERNAME","iandryPhp");
    define("PASSWORD","iandryPhp");
    define("DATABASE", "VideoAccount");
    
    


?>
