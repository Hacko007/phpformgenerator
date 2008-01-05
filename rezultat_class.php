<?php
echo "<a href='index.php'>Home</a>";

require_once("config.php");
require_once("lib_functions.php");


//if(!empty($_GET)) extract($_GET);
//if(!empty($_POST)) extract($_POST);



if(!@$_POST['tabela']  )   exit();

$tabela = @$_POST['tabela'];
$frm = @$_POST['frm'];

$lista_colona = array();

if(@$_POST['database'] ){
      mysql_select_db ($_POST['database']);
      $sql = "SELECT * FROM $tabela";
      $result = mysql_query($sql);
      $i = 0;

      $html="

<?php

class " .ucfirst ( $tabela ) ." {\n\n" ;

      while ($i < mysql_num_fields($result)) {

          //echo "Information for column $i:<br>\n";
          if($meta = mysql_fetch_field($result)){
            array_push( $lista_colona ,$meta);
          }
          $i++;
      }
      mysql_free_result($result);
      $html .= varSTR($lista_colona,$tabela,$frm);
      $html .= "\n\n";
      $html .= constrSTR($lista_colona,$tabela,$frm);
      $html .= "/////////     F u n c t i o n s   ////////////////////////////\n\n";
      $html .= functionsSTR($lista_colona,$tabela,$frm);
      $html .= "/////////     D a t a   A c c e s s      ///////////////////////////////\n\n";
      $html .= insertSQLSTR($lista_colona,$tabela,$frm);
      $html .= updateSQLSTR($lista_colona,$tabela,$frm);
      $html .= DeleteSQL_str($lista_colona,$tabela,$frm);      
      $html .= toStringSTR($lista_colona,$tabela,$frm);

      $html .= "

} // end class " .ucfirst ( $tabela ) ." 
?>";

echo highlight_string ($html,true);
}



/*
* Gets where part for uniqu rows 
* in format:
( primary_key1 = '$this->primary_key1' ) 
[ AND ( primary_key2 = '$this->primary_key2' )  ... ]
*/
function GetUniqueWhere($colone){
    $where = "";

    foreach($colone as $col){
        
        /// Get WHERE part
            if($col->primary_key != 0){
                    $my_id = $col->name ;
                        if($where != "") $where .= " AND\n";
                    $where .=  " ( $my_id='\$this->$my_id' ) ";
                }                  
        }
    return $where;
}


/**
* Generise var ime_variable = 'start_value';
*/

 function varSTR($colone,$tab,$form){
   $str1 ="";
	 //
	 
	 
   foreach($colone as $col){
      if (@$form[$col->name][vars]!= ""){
         $str1 .= "\tpublic \$$col->name ";
         if(@$form[$col->name]['startval']!= "")
               $str1 .= " \t= '". $form[$col->name]['startval'] ."'";
         $str1 .= " ;\n";
				
				 $col_obj = '';
				 if(IsSetOrEnum2($col->name, $tab) && ($col_obj = GetColumnObject($col->name, $tab)) ){
				 	$str1 .= GetEnumConstants($col->name , GetArrayOfColumnSetValues($col_obj));
				 	}	
         }
      }
   return $str1;
}

function GetEnumConstants($column_name , $array_with_enum_values){
		$str = "\tpublic $". strtoupper($column_name) ."_ENUM = array ( \n";	
		$i = 1;
		$count = count($array_with_enum_values );
		foreach($array_with_enum_values as $key => $val){
			$str .= "\t\t'$val'";
			$str .= ($i++  < $count) ? ",\n" : "\n";
			}
			return $str . "\t\t) ; \n\n" ;
}

/**
* Generise konstruktor metodu;
*/

 function constrSTR($colone,$tab,$form){

   $in_vars  = ""; // variable parametri
   $dod_vars = ""; // dodjeljivanje lokalnim vrijednostima poslate parametre
   $sql_vars = ""; // dodjeljivanje lokalnim vrijednostima iz db
   $my_id    = 'id';



   $str = "
\t/* $tab constructor */
\tpublic function __construct  () {} //end $tab __construct\n\n\n";

   $pk_vars = "";
   
   foreach($colone as $col){
           if($col->primary_key != 0){
                if( $pk_vars != "" ) $pk_vars .= ",";
                $pk_vars .=  ' $' . $col->name . ' ' ;
            }
      if (@$form[$col->name][vars]!= ""){
         $in_vars .= "\n\t\t\t\$$col->name ,";
         $dod_vars .= "\t\t\$this->$col->name \t= \t\$$col->name;\n";
         $sql_vars .= "\t\t\t\$this->$col->name \t= \$row['$col->name'];\n";
         }
      }

   $in_vars = substr($in_vars  , 0 ,-1); // skloni zadnji ,


   $where =  GetUniqueWhere($colone);

   $str .= "
\t/* $tab GetById */
\tpublic function GetById (  $pk_vars ){
\t\t\$sql = \"SELECT *  FROM $tab WHERE $where \";
\t\t\$result = mysql_query(\$sql);
\t\tif(\$row    = mysql_fetch_array(\$result)){
$sql_vars
\t\t}
\n\t} //end $tab"."->GetById\n\n\n";

   return $str;
}



/**
* Generise ADD / GET / SET metode;
*/


 function functionsSTR($colone,$tab,$form){
   $str ="";


   foreach($colone as $col){

      if (@$form[$col->name][add]!= ""){ // A D D
         $str .= "\tpublic function add" . ucfirst($col->name) ."(\$value){\n\t\t";
         $str .= "\$this->$col->name += \$value ; \n\t}\n\n\n";
      }


      if (@$form[$col->name][get]!= ""){ // G E T
         $str .= "\tpublic function get" . ucfirst($col->name) ."(){\n\t\t";
         $str .= "return \$this->$col->name ; \n\t}\n\n\n";
      }


      if (@$form[$col->name][set]!= ""){ // S E T
         $str .= "\tpublic function set" . ucfirst($col->name) ."(\$value){\n\t\t";
         
         if(IsSetOrEnum2($col->name, $tab)){
        		$str .= "if(in_array(\$value , $" . strtoupper($col->name) ."_ENUM) ) \n" 
									 ."\t\t\t\$this->$col->name = \$value ; \n\t}\n\n\n";
        	}else{
         		$str .= "\$this->$col->name = \$value ; \n\t}\n\n\n";
         }
      }


      }

   return $str;
}




/**
* Generise SQL insert komandu - metodu koja ovaj objekt ubacuje u databazu;
*/


 function insertSQLSTR($colone,$tab,$form){

   $str  ="\t/*function DB_Insert */\n";
   $str .="\tpublic function DB_Insert (){\n";
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
   $str  = substr($str  , 0 ,-1); // remove last ,
   $vals = substr($vals  , 0 ,-1); // remove last ,

   $str .= $vals . ") \";\n";

   $str .="\n\t\t@mysql_query(\$sql);\n\n";
   $str .="\t\tif(mysql_errno() == 1062){ // Duplicate primary key\n".
          "\t\t\t\$this->updateDBfromObject();\n\t\t}\n";
   $str .="\n\t}\n\n\n";

   return $str;
}



/**
* Generate SQL for update for all fields in table for this row into 
* database
*/


 function updateSQLSTR($colone,$tab,$form){

   $str  ="\t/* function DB_Update */\n";
   $str .="\tpublic function DB_Update(){\n";
   $str .="\t\t\$sql = \" UPDATE $tab SET \n";

   $where = "";
   
   foreach($colone as $col){
      
          /// Get WHERE part
          if($col->primary_key != 0){
                $my_id = $col->name ;
                if($where != "") $where .= " AND\n";
                $where .=  " ( $my_id='\$this->$my_id' ) ";
           }
            
            if (@$form[$col->name][vars]!= "" && $col->primary_key == 0){ //
                $str .= "\n\t\t\t$col->name = '\$this->$col->name' ," ;
            }
      }
   $str = substr($str  , 0 ,-1); // remove last ,

   $str .="\n\t\t\tWHERE $where \";";

   $str .="\n\t\t@mysql_query(\$sql);";
   $str .="\n\t}\n\n\n";

   return $str;
}


/**
* Generate SQL that removes current row from database
*/


 function DeleteSQL_str($colone,$tab,$form){

   $str  ="\t/* function DB_Delete */\n";
   $str .="\tpublic function DB_Delete(){\n";
   $str .="\t\t\$sql = \" DELETE FROM  $tab WHERE \n";
   
   /// Get WHERE part of SQL
   $where = "";
   foreach($colone as $col){
           if($col->primary_key != 0){
                $my_id = $col->name ;
                if($where != "") $where .= " AND ";
                $where .=  " ( $my_id='\$this->$my_id' ) ";
         }
   }
   $str .="\t\t\tWHERE $where \";";
   $str .="\n\t\t@mysql_query(\$sql);";
   $str .="\n\t}\n\n\n";
   return $str;
}



/**
* Generate toString() metodu;
*/

 function toStringSTR($colone,$tab,$form){
   $str ="\tpublic function toString(){\n";
   $str .="\t\techo 'Class:' . __CLASS__ . '<br/>';\n";
   foreach($colone as $col){
      if (@$form[$col->name][vars]!= ""){
         $str .= "\t\techo '$col->name :'. \$this->$col->name .'<br/>';\n";
         }
      }
   return $str .  "\t}// end of toString()\n\n\n";
}




?>