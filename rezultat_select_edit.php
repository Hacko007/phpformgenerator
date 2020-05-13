<?php

/*
* Input:
*
*		LANG - string - path to label constants 
*
* 	database - string
*
*		tabela - string
*
* 	frm - 2D array : 
*				first index is column name - 
*				second index is properties for that column 
*				
*				Ie:
*				Array(5){
*					["address_id"]=>
*						  array(5) {
*						    ["show"]=>					    string(2) "on"
*						    ["type"]=>					    string(6) "HIDDEN"
*						    ["col_size"]=>		    	string(2) "40"
*						    ["read_only"]=>			    string(2) "on"
*						    ["select_db"]=>			    string(18) "information_schema"
*						  }
*					["address"]=>
*					  array(4) {
*					    ["show"]=>				    string(2) "on"
*					    ["type"]=>				    string(5) "INPUT"
*					    ["col_size"]=>		    string(2) "40"
*					    ["select_db"]=>	    string(18) "information_schema"
*					  }
*				  ...
*			   }
*
*				
* 	sel_edt_id - string - name of column used for Select-Value 
*
* 	sel_edt_value - arrary with cloumn names used for Select-Display
*		Ie:
*		array(4) {
*					  [0]=>  string(6) "address"
*					  [1]=>  string(3) "zip"
*					  [2]=>  string(4) "city"
*					  [3]=>  string(6) "State"
*					}
*  
*
*/

		require_once("config.php");
		require_once("lib_functions.php");      
      
      if(@$_POST['database'] == '')   	$_POST['database'] = $database ;// set default db
      	
      @$link->select_db ($_POST['database']);
      
      
      //echo "<pre>";  var_dump($_POST['sel_edt_value']);
      

echo "<a href='index.php'>Home</a>";


if(!@$_POST['tabela']  )   exit;

if(@$_POST['LANG']  ) {	require_once($_POST['LANG']); }


/// Convert names into list of $row[col_name]
$display_cols = "";

function GetRowStr($item, $key){
 		global $display_cols;
 		 $display_cols .= " \$row[$item] ";
}


if( @$_POST['sel_edt_value']){
		array_walk ($_POST['sel_edt_value'] , 'GetRowStr');
	}




$lista_colona = array();

if(@$_POST['database'] && @$_POST['tabela']){

$html_with_real_data =  $html ="<html><head>
<meta content='text/html; charset=utf-8' http-equiv='Content-Type'>

<link rel='stylesheet' href='style.css' type='text/css'/>



      </head>
      <body>
      <table border='0' cellspacing='2' cellpadding='5'>
         <form name=myform method=post>

         <SELECT name='edit_".@$_POST['sel_edt_id']."'>
";

$html .= "

         <?php


           \$sql = \"SELECT ".@$_POST['sel_edt_id']. "," . @implode(',', @$_POST['sel_edt_value']) ."  FROM ". @$_POST['tabela'] ."\";
           \$result = \$link->query(\$sql);

           while(\$row    = \$result->fetch_array(MYSQLI_BOTH)){
              echo  \"<OPTION value='\". htmlspecialchars (\$row['". @$_POST['sel_edt_id']."'], ENT_QUOTES) .\"'\";
              echo   (\$row['". @$_POST['sel_edt_id']."']== @\$_POST['edit_". @$_POST['sel_edt_id']."']) ? \" SELECTED\" : \"\" ;
              echo   \">$display_cols </OPTION>\\n\";
           }

         ?>
";


  				@$sql = "SELECT ".@$_POST['sel_edt_id']. "," . @implode(',', @$_POST['sel_edt_value']) ."  FROM ". @$_POST['tabela'] . " LIMIT 10";
          $result = $link->query($sql);

           while($row    = $result->fetch_array(MYSQLI_BOTH)){
              $html_with_real_data .=   "<OPTION value='" .@$row[@$_POST['sel_edt_id']] . "'"; 
              $html_with_real_data .=    ($row[@$_POST['sel_edt_id']]== @$_POST['edit_' . @$_POST['sel_edt_id'] ]) ? " SELECTED" : "" ;
              $html_with_real_data .=    '>';
               eval(" \$html_with_real_data .=    \"$display_cols\"; ") ;
              $html_with_real_data .=    " </OPTION>\n";
           }

$html_with_real_data .=  $html_extra ="
         </SELECT>



<input type=submit
			 id='EDITLIST'
       name='EDITLIST'
       value='$LB_VIEWCHANGE_BTN'
       >



<input type=submit
       id='DELLIST'
       name='DELLIST'
       value='$LB_DELETE_BTN'
       onclick='return confirm($LB_DELETE_BTN_CONFIRM );'
       >

</form>
<p>


<input type=button
       id='ADDTOLIST'
       name='ADDTOLIST'
       value='$LB_ADDTOLIST_BTN'
       onclick='location.href = \"\"'
       title='$LB_ADDTOLIST_BTN_TITLE'
       >

<form method=post>

      ";

$html .= $html_extra;

      
      
      $link->select_db ($_POST['database']);

      $sql = "SELECT * FROM ".@$_POST['database'] .".". @$_POST['tabela'] ;
      $result = $link->query($sql);
      $i = 0;


      while ($i < $result->field_count) {

          //echo "Information for column $i:<br>\n";
          if($meta = $result->fetch_field()){
          	
          	$sql_c = "SHOW COLUMNS FROM $_POST[tabela] LIKE '$meta->name'";
      		$result_c = $link->query($sql_c);
      		if($column = $result_c->fetch_object()) {
				          	
	            $po = print_opciju($meta,$_POST['frm'], $column);
	            $html .= $po;
	            $html_with_real_data .= $po;
	            array_push( $lista_colona ,$meta);
         	}
          }
          $i++;
      }
      $result->free_result();
      $primary_kay = "id";
      echo "<br>";
	    foreach($lista_colona as $col){         
         if( IsPrimaryKey( $col->flags )){
               $primary_kay =  $col->name;               
               break;
          }          
       }

      $php_str ="<?php
      //require_once('config.php');
      ".
      sqlInsertSTR($lista_colona,$_POST['tabela'],@$_POST['frm']) .
      sqlUpdateSTR($lista_colona,$_POST['tabela'],@$_POST['frm']) .
      sqlDeleteSTR($lista_colona,$_POST['tabela'],@$_POST['frm'], "edit_".@$_POST['sel_edt_id']) .
      sqlRow2Var($lista_colona,$_POST['tabela'],@$_POST['frm'], "edit_".@$_POST['sel_edt_id']) .
      "   ?> ";

      $str = "
       <tr>
         <td></td>
         <td>
         <input type='submit' id='ADD' name='ADD' value='$LB_ADD_BTN'>
         <input type='reset'  id='RESET' value='$LB_RESET_BTN'></td></tr>
	</form>
</table>";

 			$html .= $str;
	    $html_with_real_data .= $str;

      $html2 = htmlentities($html);
      echo "
      <tr><td> </td><td></td></tr>
      <form>
</table>
$html_with_real_data
<hr>
<br>
$LB_SOURCE_CODE
<br>
<textarea name='' cols='120' rows='30'>$php_str

$html2
</textarea>
";

}







/**
* Generise INSERT SQL rijecenicu potrebnu za ubacivanje novih rijeci
*/

function sqlInsertSTR($colone,$tab,$form){
	global $primary_kay;

   $str1 ="Insert INTO $tab \n\t(\n";
   $str2 =")\n \tValues (\n";

   foreach($colone as $col){
      if ((   IsPrimaryKey( $col->flags) && $col->type == 3) 
      		||  (!IsPrimaryKey( $col->flags)  && @$form[$col->name][show] != "")){
         $str1 .= "\t\t$col->name ,\n";         
         $str2 .= "\t\t'\". addslashes(  \$_POST['$col->name']) .\"' ,\n";          
         }
      }

   // izbrisi zadnji zarez
   $str1 = substr($str1 , 0 ,-2);
   $str2 = substr($str2 , 0 ,-2);

   // spoj u jedno i returniraj
   return "
   /////////  I N S E R T  //////////////////////
   if(@\$_POST['ADD'] && @\$_POST['$primary_kay'] == \"\"){
      \$sqlIns = \" $str1 $str2 ) \";
      \$link->query(\$sqlIns);
   }
   ";

}








/**
* Generise UPDATE SQL rijecenicu potrebnu za izmjenu u databazi
*/

function sqlUpdateSTR($colone,$tab,$form){
	global $primary_kay;

   $str1 ="UPDATE $tab SET ";
   $str2 ="\tWHERE ";

   foreach($colone as $col){
      if ( ! IsPrimaryKey( $col->flags)  && @$form[$col->name][show]!= ""){
         $str1 .= "\n\t$col->name = '\". addslashes(  \$_POST['$col->name']) .\"' ,";

         }else if(IsPrimaryKey( $col->flags) ){
            $str2 .= " $col->name = '\". addslashes(  \$_POST['$col->name']) .\"' ,";
         }
      }

   // izbrisi zadnji zarez
   $str1 = substr($str1 , 0 ,-1);
   $str2 = substr($str2 , 0 ,-1);

   // spoj u jedno i returniraj
   return "
   ///////// U P D A T E  ////////////////////
   if(@\$_POST['ADD'] && @\$_POST['$primary_kay'] != \"\"){
      \$sqlUpd = \" $str1 \n $str2 \";

      \$link->query(\$sqlUpd);
   }

   ";

}











/**
* Generise DELETE SQL rijecenicu potrebnu za brisanje iz db
*/

function sqlDeleteSTR($colone,$tab,$form,$edit_id){
	global $primary_kay;
   $str1 =" DELETE FROM $tab WHERE  $primary_kay = '\". addslashes(  \$_POST['$edit_id']) .\"' ";

   return "

   ///////// D E L E T E ////////////////////////////////
   if(@\$_POST['DELLIST'] && @\$_POST['$edit_id']){
      \$sqlDel = \"$str1\";
      \$link->query(\$sqlDel);
   }

   ";
}












/**
* Generise PHP za ROW[f] = $f  za svako f iz tabele
*/

function sqlRow2Var($colone,$tab,$form,$edit_id){
	global $primary_kay;

   $str1 ="\n

///////  Popuni variable  ///////////////////////////////////////

if(@\$_POST['$edit_id']){

\t\$sql = \"SELECT * FROM $tab WHERE  $primary_kay = '\". addslashes(  \$_POST['$edit_id']) .\"'\";
\t\$result = \$link->query(\$sql);

\tif(\$row    = \$result->fetch_array(MYSQLI_BOTH)){

   ";
   foreach($colone as $col){
      if(@$form[$col->name][show]!= ""){
            $str1 .= "\t\t\$$col->name =  \$row['$col->name'] ;\n";
         }
      }

   $str1 .= "\n\t}\n}else{\n"   ;

      foreach($colone as $col){
         if(@$form[$col->name][show]!= ""){
               $str1 .= "\t\t\$$col->name =  '' ;\n";
            }
      }


   $str1 .= "\n}"   ;
   return $str1;
}






/**
* Za svaku izabranu opciju napravi HTML koji je opisan
*/
function print_opciju($meta,$form , $column){
   $str = "";
   if(@$form[$meta->name][show]!= ""){

      $str .= "\n<tr><td class='lable_class'>". ucfirst ( str_replace ('_' , ' ' ,$meta->name) ) ."</td><td class='form_class'>";


    switch (@$form[$meta->name][type]) {


    case "TEXTAREA":
      $str .=  "<textarea name='$meta->name'
                                cols='".$form[$meta->name]['col_size']."'
                                rows='".$form[$meta->name]['row_size']."'><?php
                                echo @\$$meta->name ;
                                ?></textarea>
";
      break;



    case "INPUT":
      $str .=  "<input type=text
                       size='".$form[$meta->name]['col_size']."'
                       name='$meta->name'
                       value='<?php echo htmlspecialchars( @\$$meta->name , ENT_QUOTES); ?>'>\n";
    break;








    case "CHECKBOX":
      $str .=  "<input type=CHECKBOX
                       name='$meta->name'
                       value='<?php echo @\$$meta->name ; ?>'>
                       <?php echo @\$$meta->name ; ?>\n";
    break;


    case "RADIO":
    	if( IsSetOrEnum($column) && ($set_vals = GetArrayOfColumnSetValues($column)) ){
				foreach($set_vals as $set_val){
					if($column->Default == $set_val){
						$str .=  "<input type=RADIO
			                       name='$meta->name'
			                       value='$set_val'<?php echo (@\$$meta->name == '$set_val' || @\$$meta->name == '' ) ? \" CHECKED\" : \"\" ?>><b>$set_val</b>
			               ";
					}else{
			      $str .=  "<input type=RADIO
			                       name='$meta->name'
			                       value='$set_val'<?php echo (@\$$meta->name == '$set_val') ? \" CHECKED\" : \"\" ?>>$set_val
			               ";
			            }
			   }

    		
    		}else{
		      $str .=  "<input type=RADIO
		                       name='$meta->name'
		                       value='<?php echo @\$$meta->name ; ?>'><?php echo @\$$meta->name ; ?>1
		               <input type=RADIO
		                       name='$meta->name'
		                       value='<?php echo @\$$meta->name ; ?>'>
		                       <?php echo @\$$meta->name ; ?>2\n";
         }
    break;

    case "HIDDEN":
      return  "<input type=hidden
                       name='$meta->name'
                       value='<?php echo @\$$meta->name ; ?>'>\n";
     break;



    case "SELECT":
       	if( IsSetOrEnum($column) && ($set_vals = GetArrayOfColumnSetValues($column)) ){
       			$str .=  "\n<SELECT name='$meta->name'>     \n" ;  			
					foreach($set_vals as $set_val){
						if($column->Default == $set_val){
							$str .=  "	<OPTION value='$set_val' <?php echo (@\$$meta->name == '$set_val' || @\$$meta->name == '' ) ? \"SELECTED\" : \"\" ?>>$set_val</OPTION>  \n";
						}else{
			      		$str .=  "	<OPTION value='$set_val' <?php echo (@\$$meta->name == '$set_val') ? \"SELECTED\" : \"\" ?>>$set_val</OPTION>  \n";
			      }
			   	}
					$str .= "\n</SELECT>\n\n";    		
    		}else{
			
      $str .=   "
      <SELECT name='$meta->name'>
         <?php
         
  			\$sql = \"SELECT ". @$form[$meta->name]['select_value_col'] .",".
  			 										@$form[$meta->name]['select_display_col'] . " 
  			 	FROM ". @$form[$meta->name]['select_db'] .".". @$form[$meta->name]['select_tablename'] ." 
  				ORDER BY ". @$form[$meta->name]['select_display_col'] ." \";
          \$result = \$link->query(\$sql);

           while(\$row  = \$result->fetch_array(MYSQLI_BOTH)){
              echo  \"<OPTION value='\$row[".@$form[$meta->name]['select_value_col']."]'\";
              echo   (\$row['".@$form[$meta->name]['select_value_col']."']== @\$$meta->name) ? \" SELECTED\" : \"\" ;
              echo   \">\$row[".@$form[$meta->name]['select_display_col']."]</OPTION>\";
           }
 ?>
      </SELECT>\n";
   }
    break;

      }

      $str .= "</td></tr>\n";

          return $str;

   }
   return "";
}

?>
