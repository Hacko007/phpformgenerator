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
      
  	
      @mysql_select_db ($_REQUEST['database']);
      

		      $sql = "SHOW COLUMNS FROM $_REQUEST[tabela]";
		      $result = mysql_query($sql);
		     		

				$str ="";
		     while($row    = mysql_fetch_array($result)){		     
				 		 	$str .= "$row[Field]\n";
		      }
		    
		    echo trim($str);  
		
		

?>