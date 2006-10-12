<?php


/*
*
* Get array with values  for columns with SET or ENUM type
*
* $column_object - Get value for this column by queary
  				$sql_c = "SHOW COLUMNS FROM [table] LIKE '[columnname]'";
      		$result_c = mysql_query($sql_c);
        		$column_object = mysql_fetch_object($result_c);         		

* @return -  array ('val1','val2',...) or false if no set or enum
*/
function GetArrayOfColumnSetValues($column_object){
	
	$rasult = false;
	
	if(ereg(('set|enum'), $column_object->Type)){
       	$column_object->Type = ereg_replace("set\(|enum\(|\)|'",'', $column_object->Type);
       	$rasult = array();
      	$rasult = explode ( "," , $column_object->Type);       	       		
	}
	
	return $rasult;
	
}



function IsSetOrEnum($column_object) {	return ereg(('set|enum'), $column_object->Type);	 }

?>