<?php

/// MySQL Login
$dbhost   ="localhost";
$username ="root";
$password ="root";

// Default db
$database = "knjizara"; 


 function connect_db(){
      global $dbhost, $username, $password , $database;


      @mysql_connect ($dbhost, $username, $password);
      if($database == '')$database = "knjizara";
      @mysql_select_db ($database);
   }


/// coment this and use your login

connect_db();

?>