<?php


/*
	Create DIV that will give user oportunity to 
	choose table/columns that will build SQL for SELECT tag

*/



function ShowExtrasForSelectSQL($column_name){

		global $database;

      
      if(! isset($_POST['database'])){
      	$_POST['database'] = $database ;// set default db
      }

		
		/// SELECT tag ID for AJAX
		///
		/// This value will be recived in result_select_edit.php and there should right
		/// SELECT TAG be build
		$db_sel_id 			= "frm[$column_name][select_db]";
		$tab_sel_id 			= "frm[$column_name][select_tablename]";
		$col_value_sel_id = "frm[$column_name][select_value_col]";
		$col_displ_sel_id = "frm[$column_name][select_display_col]";
		
		
    ?>
    
    
    <div id='selecttagsql_<?php  echo  $column_name  ; ?>' style='visibility : hidden;'>
      
		
		
		<table border=0 cellspacing=0 height=20>
			
			<tr><td valign=bottom>Table:

		
		
<SELECT 
			name='<?php  echo  $db_sel_id  ; ?>'
			id='<?php  echo  $db_sel_id  ; ?>'
			onchange='
				makeSelectRequest("<?php  echo  $tab_sel_id  ; ?>","ajax/GetSelectTablesForDB.php?database=" +this.value);
				'>

<?php

		    @mysql_select_db ($_POST['database']);		
		    $db_list = mysql_list_dbs();
				while ($row = mysql_fetch_object($db_list)) {
				    echo "<OPTION>$row->Database</OPTION>
				    ";
				    
				}
		
?>
</SELECT>.</td>
		
<td valign=bottom><span>
	
<select
			name='<?php   echo   $tab_sel_id  ?>' 
			id='<?php   echo   $tab_sel_id  ?>' 
			onchange='			
			makeSelectRequest("<?php  echo  $col_value_sel_id  ; ?>","ajax/GetSelectColumnForTable.php?database="+ document.getElementById("<?php  echo  $db_sel_id  ; ?>").value +"&tabela="+this.value);
			makeSelectRequest("<?php  echo  $col_displ_sel_id  ; ?>","ajax/GetSelectColumnForTable.php?database="+ document.getElementById("<?php  echo  $db_sel_id  ; ?>").value +"&tabela="+this.value);			
			'>
	
</select></span></td>
<td valign=bottom>Value:<span><select   name="<?php   echo   $col_value_sel_id  ?>" id="<?php   echo   $col_value_sel_id  ?>"></select></span></td>
<td valign=bottom>Display:<span><select name="<?php   echo   $col_displ_sel_id  ?>" id="<?php   echo   $col_displ_sel_id  ?>"></select></span></td>


</tr>
</table>

</div>


<?php   

}

?>