<?php

		require_once("config.php");
		require_once("lib_functions.php");



      mysql_connect ($dbhost, $username, $password) or die("fel med connect");
      
      if(! isset($_POST['database'])){
      	$_POST['database'] = $database ;// set default db
      }
      	
      mysql_select_db ($_POST['database']);
      
		echo ' <form name=myform method="POST"> ';



		$db_list = mysql_list_dbs();
		echo "<SELECT name=database onchange='tabela.value =\"\"; submit()'>";
		while ($row = mysql_fetch_object($db_list)) {
		    echo "<OPTION value='$row->Database'";
		    echo (@$_POST['database'] == $row->Database) ? " SELECTED": "";
		    echo ">$row->Database</OPTION>";
		}
		echo "</SELECT>";



		
		echo "<SELECT name=tabela id=tabela onchange='submit()'>";
		  $result = mysql_list_tables($_POST['database']);
		
		  while($row    = mysql_fetch_array($result)){
		     echo "<OPTION value='$row[0]'";
		    echo (@$_POST['tabela'] == $row[0]) ? " SELECTED": "";
		    echo ">$row[0]</OPTION>";
		  }
		echo "</SELECT>";

?>
<input type=submit name='OBRAZAC' value='Generisi obrazac'>

<input type=submit name='OBRAZAC_SEL_EDIT' value='Generisi SELECT - EDIT obrzac'>


<input type=submit name='OBRAZAC_CLASS' value='Generisi klassu'>


<p>
Pregled u vidu tabele:<br>
Red:
<SELECT name='red'>
<OPTION value='1'>1</OPTION>
<OPTION value='2'>2</OPTION>
<OPTION value='3'>3</OPTION>
<OPTION value='4'>4</OPTION>
<OPTION value='5'>5</OPTION>
<OPTION value='6'>6</OPTION>
<OPTION value='7'>7</OPTION>
<OPTION value='8'>8</OPTION>
<OPTION value='9'>9</OPTION>
<OPTION value='10' selected>10</OPTION>
<OPTION value='11'>11</OPTION>
<OPTION value='12'>12</OPTION>
<OPTION value='13'>13</OPTION>
<OPTION value='14'>14</OPTION>
<OPTION value='15'>15</OPTION>
<OPTION value='16'>16</OPTION>
<OPTION value='17'>17</OPTION>
<OPTION value='18'>18</OPTION>
<OPTION value='19'>19</OPTION>
<OPTION value='20'>20</OPTION>
</SELECT>


Colona:<SELECT name='colona'>
<OPTION value='1'>1</OPTION>
<OPTION value='2'>2</OPTION>
<OPTION value='3'>3</OPTION>
<OPTION value='4'>4</OPTION>
<OPTION value='5'>5</OPTION>
<OPTION value='6'>6</OPTION>
<OPTION value='7'>7</OPTION>
<OPTION value='8'>8</OPTION>
<OPTION value='9'>9</OPTION>
<OPTION value='10' selected>10</OPTION>
<OPTION value='11'>11</OPTION>
<OPTION value='12'>12</OPTION>
<OPTION value='13'>13</OPTION>
<OPTION value='14'>14</OPTION>
<OPTION value='15'>15</OPTION>
<OPTION value='16'>16</OPTION>
<OPTION value='17'>17</OPTION>
<OPTION value='18'>18</OPTION>
<OPTION value='19'>19</OPTION>
<OPTION value='20'>20</OPTION>
</SELECT>


Nacin:<SELECT name='typ_pregleda'>
<OPTION value='one'>Samo jedna</OPTION>
<OPTION value='all'>Sve postove</OPTION>
</SELECT>


<input type=submit name='TAB' value='Generisi pregled'>
<hr noshade size=1 height=1>
</form>


<?php











// F O R M generisanje

if(@$_POST['database'] && @$_POST['tabela'] && @$_POST['OBRAZAC']){
      mysql_select_db ($_POST['database']);

      echo "<table width='550' border='1' cellspacing='2' cellpadding='5'>
      <form name=tohtml method=post  action='rezultat.php'>

      <input type=hidden name='database'  value='$_POST[database]'>
      <input type=hidden name='tabela'    value='$_POST[tabela]'>
      ";

      $sql = "SELECT * FROM $_POST[tabela]";
      $result = mysql_query($sql);
      $i = 0;
      while ($i < mysql_num_fields($result)) {

          //echo "Information for column $i:<br>\n";
          $meta = mysql_fetch_field($result);
          if (!$meta) {
              echo "No information available<br>\n";

          }else{
          print_opciju($meta);
          }
          $i++;
      }
      mysql_free_result($result);




      echo "
      <tr><td> </td><td> <input type=submit  value='Generisi HTML'></td></tr>
      </form>
      </table>";

}










// F O R M - SELECT - EDIT generisanje

if(@$_POST['database'] && @$_POST['tabela'] && @$_POST['OBRAZAC_SEL_EDIT'] ){
      mysql_select_db ($_POST['database']);

      echo "<table  border='1' cellspacing='2' cellpadding='5'>
      <form name=tohtml method=post  action='rezultat_select_edit.php'>

      <input type=hidden name='database'  value='$_POST[database]'>
      <input type=hidden name='tabela'    value='$_POST[tabela]'>

      <tr><td>Prikazi</td><td colspan=2>izaberi SELECT</td><td>Tip formulara</td></tr>
      ";

		
		

     
		
		      $sql = "SELECT * FROM $_POST[tabela]";
		      $result = mysql_query($sql);
		      $i = 0;
		      while ($i < mysql_num_fields($result)) {
		
		          //echo "Information for column $i:<br>\n";
		          $meta = mysql_fetch_field($result);
		          
		          $sql_c = "SHOW COLUMNS FROM $_POST[tabela] LIKE '$meta->name'";
      			$result_c = mysql_query($sql_c);
      
      			if($column = mysql_fetch_object($result_c)) {
			
			          if (!$meta) {
			              echo "No information available<br>\n";
			
			          }else{
			               print_select_edit_opciju($meta , $column);
			          }
		        	}
		          $i++;
		      }
      
   	
      mysql_free_result($result);




      echo "
      <tr><td> </td><td> <input type=submit  value='Generisi HTML'></td></tr>
      </form>
      </table>";

}





// C L A S S  generisanje

if(@$_POST['database'] && @$_POST['tabela'] && @$_POST['OBRAZAC_CLASS'] ){
      mysql_select_db ($_POST['database']);

      echo "<table  border='1' cellspacing='2' cellpadding='5'>
      <form name=tohtml method=post  action='rezultat_class.php'>

      <input type=hidden name='database'  value='$_POST[database]'>
      <input type=hidden name='tabela'    value='$_POST[tabela]'>

      <tr><td>Variabla </td><td colspan=3>ADD/GET/SET</td><td>Pocetna vrijednost</td></tr>
      ";

      $sql = "SELECT * FROM $_POST[tabela]";
      $result = mysql_query($sql);
      $i = 0;
      while ($i < mysql_num_fields($result)) {

          //echo "Information for column $i:<br>\n";
          $meta = mysql_fetch_field($result);
          if (!$meta) {
              echo "No information available<br>\n";

          }else{
               print_add_get_set($meta);
          }
          $i++;
      }
      mysql_free_result($result);




      echo "
      <tr><td> </td><td> <input type=submit  value='Generisi HTML'></td></tr>
      </form>
      </table>";

}






// V I E W generisanje

if(@$_POST['database'] && @$_POST['tabela'] && @$_POST['TAB']){
      mysql_select_db ($_POST['database']);

      echo "<table width='550' border='1' cellspacing='2' cellpadding='2'>
      <form name=toviewhtml method=post action='rezultat_view.php'>

      <input type=hidden name='database'  value='$_POST[database]'>
      <input type=hidden name='tabela'    value='$_POST[tabela]'>
      <input type=hidden name='typ_pregleda'    value='$_POST[typ_pregleda]'>
      ";

      $sql = "SELECT * FROM $_POST[tabela]";
      $result = mysql_query($sql);
      $i = 0;
      $max =  mysql_num_fields($result);
      echo "<tr style='font-size:8pt' bgcolor='#dfdfdf'>
               <td width='1%'>Br</td>
               <td width='1%'>Red:</td>
               <td width='1%'>Col:</td>
               <td width='1%'>Pozicija</td>
               <td width='1%'>Prikazi</td>
               <td>Polje</td>
           </tr>\n";
      while ($i < $max) {

          //echo "Information for column $i:<br>\n";
          $meta = mysql_fetch_field($result);
          if (!$meta) {
              echo "No information available<br>\n";

          }else{
          print_view_opt($i+1,$meta,$max,$red,$colona);
          }
          $i++;
      }
      mysql_free_result($result);




      echo "
      <tr><td> </td><td colspan=5>
      <input type=submit name='makeview' value='Generisi HTML'></td></tr>
      </form>
      </table>";
   }








   function print_view_opt($redni_br,$meta,$broj_polja,$red=1,$col=1){

         echo (($redni_br%2) == 0 )? "<tr>" :"<tr bgcolor='#efefef'>";
         echo "      <td>$redni_br</td><td>";
         echo          getOptions("views[$meta->name][red]",$red)."</td><td>" .
                       getOptions("views[$meta->name][col]",$col)."</td><td>" .
                       getOptions("views[$meta->name][pozicija]",$broj_polja) ."
               </td><td>
                  <input type=checkbox name='views[$meta->name][prikazi]' checked>
               </td><td>
                     $meta->name </td></tr>\n";


   }




   function getOptions($name,$max){
   $str = "\n<SELECT name='$name'>";
   for($i = 1 ; $i <= $max ;$i++)
      $str .= "\n\t<OPTION value='$i'>$i</OPTION>";
   return $str ."</SELECT>\n";
   }





   function print_opciju($meta){
          echo "<tr><td>";

          echo "<input type=checkbox CHECKED name='frm[$meta->name][show]'>$meta->name
          </td><td>";
          mark_field_type($meta);


          echo "</td></tr>";
/*
          echo "<pre>
                  blob:         $meta->blob
                  max_length:   $meta->max_length
                  multiple_key: $meta->multiple_key
                  name:         $meta->name
                  not_null:     $meta->not_null
                  numeric:      $meta->numeric
                  primary_key:  $meta->primary_key
                  table:        $meta->table
                  type:         $meta->type
                  unique_key:   $meta->unique_key
                  unsigned:     $meta->unsigned
                  zerofill:     $meta->zerofill
                  </pre>";
*/

   }






   function print_select_edit_opciju($meta, $columns){
          echo "<tr><td>";

          echo "<input type=checkbox  name='frm[$meta->name][show]' CHECKED>$meta->name
                </td><td>";

          echo "<input type=radio name='sel_edt_id' value='$meta->name'>$meta->name
                </td><td>";

          echo "<input type=radio name='sel_edt_value' value='$meta->name'>$meta->name
                </td><td>";

          mark_field_type($meta, $columns);
          echo "</td></tr>";


   }



   function mark_field_type($meta, $column){
   
   

   $i1 = $i2 = $i3 = $i4 = $i5 = $i6 = $i7 = $i8 = "";
   switch ($meta->type) {
    case "int":
         if($meta->primary_key ==0)
               $i1 = " SELECTED";
            else
               $i5 = " SELECTED";
        break;
    case "date":
    case "string":
        $i1 = " SELECTED";
        break;
    case "blob":
        $i2 = " SELECTED";
        break;
	}
	
	if(IsSetOrEnum($column)){
		$i1 = $i2 = $i3 = $i4 = $i5 = $i6 = $i7 = $i8 = "";
		$i3 = " SELECTED";
		
		   echo "
		
		      <SELECT name='frm[$meta->name][type]'>
					<OPTION value='SELECT'  $i3>SELECT</OPTION>
		         <OPTION value='RADIO'   $i4>RADIO</OPTION>
		         <OPTION value='HIDDEN'  $i5>HIDDEN</OPTION>
		      </SELECT>
		
		      <INPUT type='HIDDEN' name='frm[$meta->name][col_size]' value='10'>		         
		   ";

		}else{
   
   
   echo "

      <SELECT name='frm[$meta->name][type]'>

         <OPTION value='INPUT'   $i1>INPUT</OPTION>
         <OPTION value='PASSWORD'  >PASSWORD</OPTION>
         <OPTION value='TEXTAREA'$i2>TEXTAREA</OPTION>
         <OPTION value='SELECT'  $i3>SELECT</OPTION>
         <OPTION value='CHECKBOX'$i3>CHECKBOX</OPTION>
         <OPTION value='RADIO'   $i4>RADIO</OPTION>
         <OPTION value='HIDDEN'  $i5>HIDDEN</OPTION>

      </SELECT>



      <SELECT name='frm[$meta->name][col_size]'>
         <OPTION>10</OPTION>
         <OPTION>20</OPTION>
         <OPTION>30</OPTION>
         <OPTION SELECTED>40</OPTION>
         <OPTION>50</OPTION>
         <OPTION>60</OPTION>
         <OPTION>70</OPTION>
      </SELECT>

   ";
		}

   if($i2 != ""){
   echo "
      <SELECT name='frm[$meta->name][row_size]'>
         <OPTION>1</OPTION>
         <OPTION>2</OPTION>
         <OPTION>3</OPTION>
         <OPTION SELECTED>4</OPTION>
         <OPTION>5</OPTION>
         <OPTION>6</OPTION>
         <OPTION>7</OPTION>
      </SELECT>

      ";
   }

   if($meta->primary_key==1){
      echo "<input type=checkbox checked name='frm[$meta->name][read_only]'>ReadOnly ";
      }

   }


//////////// C L A S S //////////////////////////////////////


   function print_add_get_set($meta){
          echo "<tr><td>";

          echo "<input type=checkbox checked name='frm[$meta->name][vars]'>$meta->name
                </td><td>";

          echo "
          <input type=checkbox name='frm[$meta->name][add]' value='$meta->name'>add". ucfirst($meta->name)."


                </td><td>";

         echo " <input type=checkbox name='frm[$meta->name][get]' value='$meta->name'>get". ucfirst($meta->name)."
                </td><td>";


          echo "<input type=checkbox name='frm[$meta->name][set]' value='$meta->name'>set". ucfirst($meta->name)."
                </td><td>";


          echo "

          <input type=text name='frm[$meta->name][startval]'>
          </td></tr>";


   }


?>
