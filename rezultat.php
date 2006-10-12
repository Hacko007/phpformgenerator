<?php

$username ="";
$password ="";
$dbhost   ="";


require_once("../log.php");
connect_db();

echo "<a href='index.php'>Home</a>";

if(!@$tabela  )   exit;

?>


<?php

/*
foreach($frm as $v => $k){
   echo "<hr> $v => $k <br>\n";
   foreach($k as $v2 => $k2)
      echo " $v2 => $k2 <br>\n";
   echo "<hr>";
   }



id => Array
show => on
type => INPUT
col_size => 40
read_only => on
--------------------------------------------------------------------------------
naslov => Array
show => on
type => TEXTAREA
col_size => 40
row_size => 4

*/
?>


</form>


<?php


$lista_colona = array();

if(@$database && @$tabela){
      mysql_select_db ($database);


      $sql = "SELECT * FROM $tabela";
      $result = mysql_query($sql);
      $i = 0;

      $html="<table width='450' border='1' cellspacing='2' cellpadding='5'>
         <form name=myform>
      ";

      while ($i < mysql_num_fields($result)) {

          //echo "Information for column $i:<br>\n";
          if($meta = mysql_fetch_field($result)){
            $html .= print_opciju($meta,$frm);
            array_push( $lista_colona ,$meta);
          }
          $i++;
      }
      mysql_free_result($result);
      $php_str ="
      <?php  /* \n".
      sqlInsertSTR($lista_colona,$tabela,$frm) .
      sqlUpdateSTR($lista_colona,$tabela,$frm) .
      sqlDeleteSTR($lista_colona,$tabela,$frm) .
      sqlRow2Var($lista_colona,$tabela,$frm) .
      "  */ ?> ";

      $html .= "
      <tr><td> </td><td><input type=submit name='ADD' value='Posalji'><input type=reset value='Izbrisi'></td></tr>
               </form>
               </table>";

      $html2 = htmlentities($html);
      echo "
      <tr><td> </td><td></td></tr>
      <form>
      </table>
$html
<hr>
      <textarea name='' cols='80' rows='20'>

      $php_str

      $html2
      </textarea>
      ";

}


/**
* Generise INSERT SQL rijecenicu potrebnu za ubacivanje novih rijeci
*/

function sqlInsertSTR($colone,$tab,$form){
   $str1 ="Insert INTO $tab (";
   $str2 =")\n Values (";

   foreach($colone as $col){
      if ($col->primary_key == 0 && @$form[$col->name][show]!= ""){
         $str1 .= "$col->name ,";
         $str2 .= "'\$$col->name' ,";
         }
      }

   // izbrisi zadnji zarez
   $str1 = substr($str1 , 0 ,-1);
   $str2 = substr($str2 , 0 ,-1);

   // spoj u jedno i returniraj
   return "\$sqlIns = \" $str1 $str2 ) \";\n\n";

}


/**
* Generise UPDATE SQL rijecenicu potrebnu za izmjenu u databazi
*/

function sqlUpdateSTR($colone,$tab,$form){
   $str1 ="UPDATE $tab SET ";
   $str2 ="WHERE ";

   foreach($colone as $col){
      if ($col->primary_key == 0 && @$form[$col->name][show]!= ""){
         $str1 .= "\n\t$col->name = '\$$col->name' ,";

         }else if($col->primary_key == 1 ){
            $str2 .= " $col->name = '\$$col->name' ,";
         }
      }

   // izbrisi zadnji zarez
   $str1 = substr($str1 , 0 ,-1);
   $str2 = substr($str2 , 0 ,-1);

   // spoj u jedno i returniraj
   return "\$sqlUpd = \" $str1 \n $str2 \";\n\n";

}

/**
* Generise DELETE SQL rijecenicu potrebnu za brisanje iz db
*/

function sqlDeleteSTR($colone,$tab,$form){
   $str1 ="DELETE FROM $tab WHERE ";
   foreach($colone as $col){
      if($col->primary_key == 1 ){
            $str1 .= " $col->name = '\$$col->name' ,";
         }
      }
   // izbrisi zadnji zarez
   return "\$sqlDel = \"". substr($str1 , 0 ,-1) . "\";\n\n";
}



/**
* Generise PHP za ROW[f] = $f  za svako f iz tabele
*/

function sqlRow2Var($colone,$tab,$form){
   $str1 ="\n";
   foreach($colone as $col){
      if(@$form[$col->name][show]!= ""){
            $str1 .= "\t\$$col->name =  \$row['$col->name'] ;\n";
         }
      }
   return $str1;
}






/**
* Za svaku izabranu opciju napravi HTML koji je opisan
*/
function print_opciju($meta,$form){
   $str = "";
   if(@$form[$meta->name][show]!= ""){

      $str .= "\n<tr><td>$meta->name</td><td>";


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
      $str .=  "<input type=RADIO
                       name='$meta->name'
                       value='<?php echo @\$$meta->name ; ?>'><?php echo @\$$meta->name ; ?>1
               <input type=RADIO
                       name='$meta->name'
                       value='<?php echo @\$$meta->name ; ?>'>
                       <?php echo @\$$meta->name ; ?>2\n";
    break;

    case "HIDDEN":
      return  "<input type=hidden
                       name='$meta->name'
                       value='<?php echo @\$$meta->name ; ?>'>\n";
     break;



    case "SELECT":
      $str .=  "
      <SELECT name='$meta->name'>
         <OPTION value=''></OPTION>
      </SELECT>\n";
    break;

      }

      $str .= "</td></tr>\n";

          return $str;

   }
   return "";
}

?>
