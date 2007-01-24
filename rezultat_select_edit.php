<?php


		require_once("config.php");
		require_once("lib_functions.php");

      @mysql_connect ($dbhost, $username, $password) or die("fel med connect");
      
      if(@$_POST['database'] == '')
      	$_POST['database'] = $database ;// set default db
      	
      @mysql_select_db ($_POST['database']);
      
      
      
     // var_dump($_POST['frm']);
      

echo "<a href='index.php'>Home</a>";


if(!@$_POST['tabela']  )   exit;





$lista_colona = array();

if(@$_POST['database'] && @$_POST['tabela']){

      $html="<html>
      <head>
      <meta content='text/html; charset=utf-8' http-equiv='Content-Type'>

      <style type='text/css'>
<!--
table{
border: 1px solid #003399;
}
.lable_class {
	/* border: 1px solid #003399; */
	background-color: #CCCCCC;
	padding: 3px 10px 3px 30px;
}
.form_class {
	/* border: 1px solid #00CCFF; */
	background-color: #EEEEEE;

}
-->
</style>


      </head>
      <body>
      <table border='0' cellspacing='2' cellpadding='5'>
         <form name=myform method=post>

         <SELECT name='edit_".@$_POST['sel_edt_id']."'>

         <?php


           \$sql = \"SELECT ".@$_POST['sel_edt_id']. "," . @$_POST['sel_edt_value'] ."  FROM ". @$_POST['tabela'] ."\";
           \$result = mysql_query(\$sql);

           while(\$row    = mysql_fetch_array(\$result)){
              echo  \"<OPTION value='\$row[". @$_POST['sel_edt_id']."]'\";
              echo   (\$row['". @$_POST['sel_edt_id']."']== @\$_POST['edit_". @$_POST['sel_edt_id']."']) ? \" SELECTED\" : \"\" ;
              echo   \">\$row[". @$_POST['sel_edt_value']."]</OPTION>\";
           }

         ?>

         </SELECT>



<input type=submit
       name='EDITLIST'
       value='Izmijeni'
       style='background-color:yellow;width:100pt;border: blue 1pt solid'>



<input type=submit
       name='DELLIST'
       value='Izbrisi'
       onclick='return confirm(\"Ovim potezom cete izbrisati izabranu opciju!\\n\\n Zelite li to da uradite?\");'
       style='background-color:red;color:white;border: blue 1pt solid'>

</form>
<p>


<input type=button
       name='ADDTOLIST'
       value='Prazan formular'
       onclick='location.href = \"\"'
       title='Dodaj novo na listu'
       style='background-color:green;color:#aaffaa;width:100pt;border: blue 1pt solid'>

<form method=post>

      ";


      
      
      mysql_select_db ($_POST['database']);

      $sql = "SELECT * FROM ".@$_POST[database] .".". @$_POST[tabela] ;
      $result = mysql_query($sql);
      $i = 0;


      while ($i < mysql_num_fields($result)) {

          //echo "Information for column $i:<br>\n";
          if($meta = mysql_fetch_field($result)){
          	
          	$sql_c = "SHOW COLUMNS FROM $_POST[tabela] LIKE '$meta->name'";
      		$result_c = mysql_query($sql_c);
      		if($column = mysql_fetch_object($result_c)) {
				          	
	            $html .= print_opciju($meta,$_POST['frm'], $column);
	            
	            array_push( $lista_colona ,$meta);
         	}
          }
          $i++;
      }
      mysql_free_result($result);
      $primary_kay = "id";
	   foreach($lista_colona as $col){
      if($col->primary_key == 1 ){
            $primary_kay =  $col->name;
         }
      }

      $php_str ="<?php
      //require_once('config.php');
      ".
      sqlInsertSTR($lista_colona,$_POST['tabela'],@$_POST['frm']) .
      sqlUpdateSTR($lista_colona,$_POST['tabela'],@$_POST['frm']) .
      sqlDeleteSTR($lista_colona,$_POST['tabela'],@$_POST['frm'], "edit_".@$_POST[sel_edt_id]) .
      sqlRow2Var($lista_colona,$_POST['tabela'],@$_POST['frm'], "edit_".@$_POST[sel_edt_id]) .
      "   ?> ";

      $html .= "
       <tr>
         <td> </td>
         <td>
         <input type=submit name='ADD' value='Posalji'>
         <input type=reset value='Izbrisi'></td></tr>
	</form>
</table>";

      $html2 = htmlentities($html);
      echo "
      <tr><td> </td><td></td></tr>
      <form>
</table>
$html
<hr>
<textarea name='' cols='80' rows='20'>$php_str

$html2
</textarea>
";

}







/**
* Generise INSERT SQL rijecenicu potrebnu za ubacivanje novih rijeci
*/

function sqlInsertSTR($colone,$tab,$form){
	global $primary_kay;

   $str1 ="Insert INTO $tab \n\t(";
   $str2 =")\n Values (";

   foreach($colone as $col){
      if ($col->primary_key == 0 && @$form[$col->name][show]!= ""){
         $str1 .= "\t\t$col->name ,\n";
         $str2 .= "\t\t'\$_POST[$col->name]' ,\n";
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
      mysql_query(\$sqlIns);
   }
   ";

}








/**
* Generise UPDATE SQL rijecenicu potrebnu za izmjenu u databazi
*/

function sqlUpdateSTR($colone,$tab,$form){
	global $primary_kay;

   $str1 ="UPDATE $tab SET ";
   $str2 ="WHERE ";

   foreach($colone as $col){
      if ($col->primary_key == 0 && @$form[$col->name][show]!= ""){
         $str1 .= "\n\t$col->name = '\$_POST[$col->name]' ,";

         }else if($col->primary_key == 1 ){
            $str2 .= " $col->name = '\$_POST[$col->name]' ,";
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

      mysql_query(\$sqlUpd);
   }

   ";

}











/**
* Generise DELETE SQL rijecenicu potrebnu za brisanje iz db
*/

function sqlDeleteSTR($colone,$tab,$form,$edit_id){
	global $primary_kay;
   $str1 =" DELETE FROM $tab WHERE  $primary_kay = '\$_POST[$edit_id]' ";

   return "

   ///////// D E L E T E ////////////////////////////////
   if(@\$_POST['DELLIST'] && @\$_POST['$edit_id']){
      \$sqlDel = \"$str1\";
      mysql_query(\$sqlDel);
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

\t\$sql = \"SELECT * FROM $tab WHERE  $primary_kay = '\$_POST[$edit_id]'\";
\t\$result = mysql_query(\$sql);

\tif(\$row    = mysql_fetch_array(\$result)){

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
                       value='<?php echo @\$$meta->name ; ?>'>\n";
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
							$str .=  "	<OPTION value='$set_val'  <?php echo (@\$$meta->name == '$set_val' || @\$$meta->name == '' ) ? \"SELECTED\" : \"\" ?>>$set_val</OPTION>  \n";
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
          \$result = mysql_query(\$sql);

           while(\$row  = mysql_fetch_array(\$result)){
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
