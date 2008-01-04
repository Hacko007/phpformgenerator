<?php

/// MySQL Login
$dbhost   ="localhost";
$username ="root";
$password ="root";

// Default db
$database = "default_db"; 


 function connect_db(){
      global $dbhost, $username, $password , $database;
      @mysql_connect ($dbhost, $username, $password);
      if($database == '')$database = "default_db";      
   }


/// coment this and use your login

connect_db();

?>