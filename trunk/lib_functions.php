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

function GetColumnObject($column_name, $table_name){
		$result = mysql_query("show columns FROM $table_name like '$column_name'");
		if($column_object = mysql_fetch_object($result)) {
			return 	$column_object;
		}
		return false;
}


function IsSetOrEnum($column_object) {	return ereg(('set|enum'), $column_object->Type);	 }


function IsSetOrEnum2($column, $table){
	
	$result = mysql_query("show columns FROM $table like '$column'");
	if($column_object = mysql_fetch_object($result)) {
		return 	IsSetOrEnum($column_object);
	}
	return false;
}
 

function GetLangages($selected){
		$d = dir("lang/");
		$html='<select id="LANG" name="LANG">';
		while (false !== ($file = $d->read())) {
			  if ($file != '.' && $file != '..' && $file != '.svn') {
			  	$name = str_replace('.php','',$file);		   		
		
					$html .=    "<OPTION value='". 'lang/' . $file ."'";
          $html .=    ($name == $selected ) ? " SELECTED" : "" ;
          $html .=    "> $name </OPTION>";
		   				   		
		  	}
		}
		$d->close();
		$html .='</select>';
		
		return $html;
	}

?>