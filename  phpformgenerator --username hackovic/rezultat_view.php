<?php

$username ="";
$password ="";
$dbhost   ="";


require_once("../log.php");
connect_db();

echo "<a href='index.php'>Home</a>";

if(!@$tabela  )   exit;


// Sortiraj prvo po red - colona - pozicija
      uasort ($views,"cmp");


   // sortiraj polja
   function cmp ($a, $b) {

       if ($a['red'] == $b['red']) return cmpCol($a,$b);
       return ($a['red'] < $b['red']) ? -1 : 1;
   }
   // Uporedi colone
   function cmpCol($a,$b){
      if ($a['col'] == $b['col']) return cmpPoz($a,$b);
      return ($a['col'] < $b['col']) ? -1 : 1;
      }

   // Uporedi Pozicije
   function cmpPoz($a,$b){
      if ($a['pozicija'] == $b['pozicija']) return 0;
      return ($a['pozicija'] < $b['pozicija']) ? -1 : 1;
   }



$lista_colona = array();


if(@$database && @$tabela){
      mysql_select_db ($database);


      $sql = "SELECT * FROM $tabela";
      $result = mysql_query($sql);
      $i = 0;
      $t_red = 1; // temp red
      $t_col = 1; // temp colona

      $html="<table width='450' border='1' cellspacing='2' cellpadding='5'>
         <form name=myform>
      ";

$html_form = $html_show = "";
$ifsort = "\n	\$where_sort = (@\$_GET['sort']) ? ' ORDER BY ' . \$_GET['sort'] : '';	";

if(@$typ_pregleda == "all"){

   $html_form .= "<?php
      $ifsort
      \$sql = \"SELECT * FROM $tabela \$where_sort\";
      \$result = mysql_query(\$sql);
      \$is_first_row = false;
      while(\$row    = mysql_fetch_array(\$result)){
      ?>
      ";
   }else{
      $html_form .= "<?php
      $ifsort
      \$sql = \"SELECT * FROM $tabela \$where_sort\";
      \$result = mysql_query(\$sql);
      \$is_first_row = false;
      if(\$row    = mysql_fetch_array(\$result)){
      ?>
      ";
   }



   $html_form .=  " <tr class='<?php
      if(\$is_first_row){
      		echo 'row_style';
      		\$is_first_row = false;
	}else{
      		echo 'row2_style';
      		\$is_first_row = true;
	}
      ?>'><td colspan=1>\n";
   $html_show .=  "<tr  class='row_style'><td colspan=1>\n";

   $html_header ="<tr class='header_style'>\n";

foreach($views as $v => $k){
   if(@$k['prikazi'] =='' )
      continue;

      if($t_red < $k['red']){
         $html_form .=  "</td></tr>\n";
         $html_show .=  "</td></tr>\n";

         $html_form .=  "<tr rowspan=1><td colspan=1>\n";
         $html_show .=  "<tr rowspan=1><td colspan=1>\n";

         $t_red = $k['red'];
         $t_col = 1;
         }

      if($t_col < $k['col']){
         $html_form .=  "</td><td colspan=1>\n";
         $html_show .=  "</td><td colspan=1>\n";
         $t_col = $k['col'];
         }

         $html_form .= "  <?php echo \$row['$v'] ;?>\n";
         $html_show .=  "[ $v ] \n &nbsp;&nbsp;&nbsp;"; // ime polja

	 $html_header .= "<td>	  <a href='?sort=$v'> " . ucfirst($v) ."</a>\t\t</td>\n";

   }

   $html_header .= "</tr>";

   $html = "<?php


   require_once('config.php');

?>
<html>
      <head>
      <meta content='text/html; charset=utf-8' http-equiv='Content-Type'>

      <style type='text/css'>
<!--
.header_style {
	/* border: 1px solid #003399; */
	background-color: #CCCCCC;
	padding: 3px 10px 3px 30px;
}
.header_style>td {
	/* border: 1px solid #003399; */
	background-color: #CCCCCC;
	padding: 10px;
}

.row_style {
	/* border: 1px solid #00CCFF; */
	background-color: #EEEEEE;

.row2_style {
	/* alternative row style */
	background-color: #EEEEFF;

}
-->
</style>


      </head>
      <body>";

   $html_form ="$html <table border=0 cellspacing=2>\n\n $html_header \n\n $html_form";
   $html_show= "$html <table border=0 cellspacing=2>\n\n $html_header \n\n $html_show";

   $html_form .=   "\n</tr><?php } ?></table>\n";
   $html_show .=   "</td></tr></table>";

   echo "<form>   <div id='vidi'>   $html_show </div>\n\n\n\n\n\n";

   echo "<textarea id=ta cols='80' rows='16'>";
   echo htmlentities ( $html_show);
   echo "</textarea><br>  <input type=button value='Vidi ponovo'
            onclick='vidi.innerHTML= ta.value'>
        ";

   echo "<br><textarea id=ta2 cols='80' rows='16'>";
   echo htmlentities ( $html_form);
   echo "</textarea> </form>

      ";
exit();
}

?>