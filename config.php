<?php

function db_connect(){
   global $database , $link;
   /// MySQL Login
   $dbhost   ="localhost";
   $username ="root";
   $password ="root"; 
   // Default db
   $database = "default_db";
   $link = mysqli_connect($dbhost,$username,$password,$database) or die("Error " . mysqli_error($link));
   return $link;
 }
 
 db_connect();
?>