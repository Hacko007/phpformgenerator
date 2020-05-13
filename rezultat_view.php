<?php

require_once("config.php");
 

echo "<a href='index.php'>Home</a>";

if(!@$_POST['tabela'])   exit;


// Sortiraj prvo po red - colona - pozicija
      uasort ($_POST['views'],"cmp");


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


if(@$_POST['database'] && @$_POST['tabela']){
      mysqli_select_db ($link, $_POST['database']);


      $sql = "SELECT * FROM $_POST[tabela]";
      $result = $link->query($sql);
      $i = 0;
      $t_red = 1; // temp red
      $t_col = 1; // temp colona

      $html="<table width='450' border='1' cellspacing='2' cellpadding='5'>
         <form name=myform>
      ";

$html_form = $html_show = "";
$ifsort = "\n	\$where_sort = (@\$_GET['sort']) ? ' ORDER BY ' . \$_GET['sort'] : '';	";

if(@$_POST['typ_pregleda'] == "all"){

   $html_form .= "<?php
      $ifsort
      \$sql = \"SELECT * FROM $_POST[tabela] \$where_sort\";
      \$result = \$link->query(\$sql);
      \$is_first_row = false;
      while(\$row    = \$result->fetch_array()){
      ?>
      ";
   }else{
      $html_form .= "<?php
      $ifsort
      \$sql = \"SELECT * FROM $_POST[tabela] \$where_sort\";
      \$result = \$link->query(\$sql);
      \$is_first_row = false;
      if(\$row    = \$result->fetch_array()){
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

foreach($_POST['views'] as $v => $k){
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

   echo "<form>  <input type=button value='Show again'
            onclick='vidi.innerHTML= ta.value'>
       <div id='vidi'>   $html_show </div>\n\n\n\n\n\n<br><br>PHP-code:";
   echo "<textarea id=ta cols='80' rows='16'>";
   echo htmlentities ( $html_show);
   echo "</textarea><br>  
        ";

   echo "<br><textarea id=ta2 cols='80' rows='16'>";
   echo htmlentities ( $html_form);
   echo "</textarea> </form>

      ";
}

?>