<?php


/*
ajax/GetSelectTablesForDB.php

In parameters : 

_REQUEST[]
 	database 
 

Returns AJAX 
all tables in $_POST['database']

table1
table2
table3
.
.
.

*/


		require_once("../config.php");
		require_once("../lib_functions.php");


      if(! isset($_REQUEST['database'])){
      	$_REQUEST['database'] = $database ;// set default db
      }
  
    	
    $link->select_db ($_REQUEST['database']);
    $result = $link->query("SHOW TABLES FROM " . $_REQUEST['database']);
	$str ="";
	 while($row    = $result->fetch_array(MYSQLI_BOTH)){		     
	 	$str .= "$row[0]\n";
	}
		    
	echo trim($str);  
		

?>