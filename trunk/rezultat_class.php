<?php

$username ="";
$password ="";
$dbhost   ="";


require_once("../log.php");
connect_db();

echo "<a href='index.php'>Home</a>";

if(!@$tabela  )   exit;



$lista_colona = array();

if(@$database && @$tabela){
      mysql_select_db ($database);


      $sql = "SELECT * FROM $tabela";
      $result = mysql_query($sql);
      $i = 0;

      $html="

<?php

class $tabela{\n\n" ;

      while ($i < mysql_num_fields($result)) {

          //echo "Information for column $i:<br>\n";
          if($meta = mysql_fetch_field($result)){
            array_push( $lista_colona ,$meta);
          }
          $i++;
      }
      mysql_free_result($result);
      $html .= varSTR($lista_colona,$tabela,$frm);
      $html .= constrSTR($lista_colona,$tabela,$frm);
      $html .= "/////////     F u n k c i j e   ////////////////////////////\n\n";
      $html .= functionsSTR($lista_colona,$tabela,$frm);
      $html .= "/////////     S   Q   L      ///////////////////////////////\n\n";
      $html .= insertSQLSTR($lista_colona,$tabela,$frm);
      $html .= updateSQLSTR($lista_colona,$tabela,$frm);
      $html .= toStringSTR($lista_colona,$tabela,$frm);

      $html .= "

} // end class $tabela
?>

      ";

echo highlight_string ($html,true);
}







/**
* Generise var ime_variable = 'start_value';
*/

 function varSTR($colone,$tab,$form){
   $str1 ="";

   foreach($colone as $col){
      if (@$form[$col->name][vars]!= ""){
         $str1 .= "\tvar \$$col->name ";
         if(@$form[$col->name]['startval']!= "")
               $str1 .= " \t= '". $form[$col->name]['startval'] ."'";
         $str1 .= " ;\n";

         }
      }
   return $str1;
}



/**
* Generise konstruktor metodu;
*/

 function constrSTR($colone,$tab,$form){
   $str ="
\tfunction $tab (   ";

   $in_vars  = ""; // variable parametri
   $dod_vars = ""; // dodjeljivanje lokalnim vrijednostima poslate parametre
   $sql_vars = ""; // dodjeljivanje lokalnim vrijednostima iz db
   $my_id    = 'id';
   foreach($colone as $col){
      if($col->primary_key != 0)
         $my_id = $col->name ;
      if (@$form[$col->name][vars]!= ""){
         $in_vars .= "\n\t\t\t\$$col->name ,";
         $dod_vars .= "\t\t\$this->$col->name \t= \t\$$col->name;\n";
         $sql_vars .= "\t\t\t\$this->$col->name \t= \$row['$col->name'];\n";
         }
      }

   $in_vars = substr($in_vars  , 0 ,-1); // skloni zadnji ,

   $str .= $in_vars ." ){\n";
   $str .= $dod_vars ."\n\t} //end $tab constructor\n\n\n";



   $str .= "
\tfunction $tab(\$id){
\t\t\$sql = \"SELECT *  FROM $tab WHERE $my_id='\$id'\";
\t\t\$result = mysql_query(\$sql);
\t\tif(\$row    = mysql_fetch_array(\$result)){
$sql_vars
\t\t}
\n\t} //end $tab constructor\n\n\n";

   return $str;
}



/**
* Generise ADD / GET / SET metode;
*/


 function functionsSTR($colone,$tab,$form){
   $str ="";


   foreach($colone as $col){

      if (@$form[$col->name][add]!= ""){ // A D D
         $str .= "\tfunction add_" . ucfirst($col->name) ."(\$value){\n\t\t";
         $str .= "\$this->$col->name += \$value ; \n\t}\n\n\n";
      }


      if (@$form[$col->name][get]!= ""){ // G E T
         $str .= "\tfunction get_" . ucfirst($col->name) ."(){\n\t\t";
         $str .= "return \$this->$col->name ; \n\t}\n\n\n";
      }


      if (@$form[$col->name][set]!= ""){ // S E T
         $str .= "\tfunction set_" . ucfirst($col->name) ."(\$value){\n\t\t";
         $str .= "\$this->$col->name = \$value ; \n\t}\n\n\n";
      }


      }

   return $str;
}




/**
* Generise SQL insert komandu - metodu koja ovaj objekt ubacuje u databazu;
*/


 function insertSQLSTR($colone,$tab,$form){

   $str  ="\t/*function insertDBfromObject */\n";
   $str .="\tfunction insertDBfromObject(){\n";
   $str .="\t\t\$sql = \" INSERT INTO  $tab (";

   $vals = " )\n\t\t Values ( ";

   foreach($colone as $col){
      if($col->primary_key != 0)
         $my_id = $col->name ;
      if (@$form[$col->name][vars]!= "" && $col->primary_key == 0){ //
         $str .= "\n\t\t\t$col->name ,";
         $vals.= "\n\t\t\t'\$this->$col->name' ," ;
         }
      }
   $str  = substr($str  , 0 ,-1); // skloni zadnji ,
   $vals = substr($vals  , 0 ,-1); // skloni zadnji ,

   $str .= $vals . ") \";\n";

   $str .="\n\t\t@mysql_query(\$sql);\n\n";
   $str .="\t\tif(mysql_errno() == 1062){ // Duplikat\n".
          "\t\t\t\$this->updateDBfromObject();\n\t\t}\n";
   $str .="\n\t}\n\n\n";

   return $str;
}



/**
* Generise SQL update metodu koja ovaj objekt vraca u databazu;
*/


 function updateSQLSTR($colone,$tab,$form){

   $str  ="\t/* function updateDBfromObject */\n";
   $str .="\tfunction updateDBfromObject(){\n";
   $str .="\t\t\$sql = \" UPDATE $tab SET \n";


   foreach($colone as $col){
      if($col->primary_key != 0)
         $my_id = $col->name ;
      if (@$form[$col->name][vars]!= "" && $col->primary_key == 0){ //
         $str .= "\n\t\t\t$col->name = '\$this->$col->name' ," ;
         }
      }
   $str = substr($str  , 0 ,-1); // skloni zadnji ,

   $str .="\n\t\t\tWHERE $my_id='\$this->$my_id' \";";

   $str .="\n\t\t@mysql_query(\$sql);";
   $str .="\n\t}\n\n\n";

   return $str;
}




/**
* Generise toString() metodu;
*/

 function toStringSTR($colone,$tab,$form){
   $str ="\tfunction toString(){\n";
   foreach($colone as $col){
      if (@$form[$col->name][vars]!= ""){
         $str .= "\t\techo '$col->name :'. \$this->$col->name .'<br>';\n";
         }
      }
   return $str .  "\t}// end of toString()\n\n\n";
}




?>