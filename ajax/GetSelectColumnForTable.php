<?php

/*
In parameters : 

_REQUEST[]
 database 
 tabela

 

Returns AJAX 

col1
col2
col3
.
.
.

*/


		require_once("../config.php");
		require_once("../lib_functions.php");


      if(! isset($_REQUEST['database'])){
      	@$_REQUEST['database'] = $database ;// set default db
      }
  
     if(! isset($_REQUEST['tabela'])){      	
      	exit();
      }
      
  	
      $link->select_db ($_REQUEST['database']);
      

		      $sql = "SHOW COLUMNS FROM $_REQUEST[tabela]";
		      $result = $link->query($sql);
		     		

				$str ="";
		     while($row    = $result->fetch_array()){		     
				 		 	$str .= "$row[Field]\n";
		      }
		    
		    echo trim($str);  
		
		

?>