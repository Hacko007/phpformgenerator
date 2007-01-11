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
  
    	
      mysql_select_db ($_REQUEST['database']);
      

			$result = mysql_list_tables($_REQUEST['database']);
		
		

			$str ="";
		  while($row    = mysql_fetch_array($result)){		     
				 	$str .= "$row[0]\n";
		   }
		    
		   echo trim($str);  
		

?>