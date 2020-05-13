<?php
      require_once('config.php');
      
   /////////  I N S E R T  //////////////////////
   if(@$_POST['ADD'] && @$_POST['user_id'] == ""){
  echo    $sqlIns = " Insert INTO bemuf_user_profil 
	(		login ,
		forum_glavni ,
		forum_odbor ,
		forum_sestre ,
		forum_gigovici ,
		forum_flickor_kan ,
		clanstvo ,
		kalendar ,
		novosti_na_bemufu ,
		knjiga_gostiju_admin ,
		fotoalbum_admin ,
		tekstovi ,
		citati ,
		linkovi_admin ,
		kviz_admin ,
		zapisnici_sa_sastanka ,
		anketa_admin ,
		clanovi_odbora_admin ,
		redakcija ,
		inventar  )
 Values (		'$_POST[login]' ,
		'$_POST[forum_glavni]' ,
		'$_POST[forum_odbor]' ,
		'$_POST[forum_sestre]' ,
		'$_POST[forum_gigovici]' ,
		'$_POST[forum_flickor_kan]' ,
		'$_POST[clanstvo]' ,
		'$_POST[kalendar]' ,
		'$_POST[novosti_na_bemufu]' ,
		'$_POST[knjiga_gostiju_admin]' ,
		'$_POST[fotoalbum_admin]' ,
		'$_POST[tekstovi]' ,
		'$_POST[citati]' ,
		'$_POST[linkovi_admin]' ,
		'$_POST[kviz_admin]' ,
		'$_POST[zapisnici_sa_sastanka]' ,
		'$_POST[anketa_admin]' ,
		'$_POST[clanovi_odbora_admin]' ,
		'$_POST[redakcija]' ,
		'$_POST[inventar]'  ) ";
     // $link->query($sqlIns);
   }
   
   ///////// U P D A T E  ////////////////////
   if(@$_POST['ADD'] && @$_POST['user_id'] != ""){
     echo $sqlUpd = " UPDATE bemuf_user_profil SET 
	login = '$_POST[login]' ,
	forum_glavni = '$_POST[forum_glavni]' ,
	forum_odbor = '$_POST[forum_odbor]' ,
	forum_sestre = '$_POST[forum_sestre]' ,
	forum_gigovici = '$_POST[forum_gigovici]' ,
	forum_flickor_kan = '$_POST[forum_flickor_kan]' ,
	clanstvo = '$_POST[clanstvo]' ,
	kalendar = '$_POST[kalendar]' ,
	novosti_na_bemufu = '$_POST[novosti_na_bemufu]' ,
	knjiga_gostiju_admin = '$_POST[knjiga_gostiju_admin]' ,
	fotoalbum_admin = '$_POST[fotoalbum_admin]' ,
	tekstovi = '$_POST[tekstovi]' ,
	citati = '$_POST[citati]' ,
	linkovi_admin = '$_POST[linkovi_admin]' ,
	kviz_admin = '$_POST[kviz_admin]' ,
	zapisnici_sa_sastanka = '$_POST[zapisnici_sa_sastanka]' ,
	anketa_admin = '$_POST[anketa_admin]' ,
	clanovi_odbora_admin = '$_POST[clanovi_odbora_admin]' ,
	redakcija = '$_POST[redakcija]' ,
	inventar = '$_POST[inventar]'  
 WHERE  user_id = '$_POST[user_id]'  ";

    //  $link->query($sqlUpd);
   }

   

   ///////// D E L E T E ////////////////////////////////
   if(@$_POST['DELLIST'] && @$_POST['edit_user_id']){
   echo   $sqlDel = " DELETE FROM bemuf_user_profil WHERE  user_id = '$_POST[edit_user_id]' ";
      //$link->query($sqlDel);
   }

   
///////  Popuni variable  ///////////////////////////////////////

if(@$_POST['edit_user_id']){

	$sql = "SELECT * FROM bemuf_user_profil WHERE  user_id = '$_POST[edit_user_id]'";
	$result = $link->query($sql);

	if($row    = $result->fetch_array()){

   		$user_id =  $row['user_id'] ;
		$login =  $row['login'] ;
		$forum_glavni =  $row['forum_glavni'] ;
		$forum_odbor =  $row['forum_odbor'] ;
		$forum_sestre =  $row['forum_sestre'] ;
		$forum_gigovici =  $row['forum_gigovici'] ;
		$forum_flickor_kan =  $row['forum_flickor_kan'] ;
		$clanstvo =  $row['clanstvo'] ;
		$kalendar =  $row['kalendar'] ;
		$novosti_na_bemufu =  $row['novosti_na_bemufu'] ;
		$knjiga_gostiju_admin =  $row['knjiga_gostiju_admin'] ;
		$fotoalbum_admin =  $row['fotoalbum_admin'] ;
		$tekstovi =  $row['tekstovi'] ;
		$citati =  $row['citati'] ;
		$linkovi_admin =  $row['linkovi_admin'] ;
		$kviz_admin =  $row['kviz_admin'] ;
		$zapisnici_sa_sastanka =  $row['zapisnici_sa_sastanka'] ;
		$anketa_admin =  $row['anketa_admin'] ;
		$clanovi_odbora_admin =  $row['clanovi_odbora_admin'] ;
		$redakcija =  $row['redakcija'] ;
		$inventar =  $row['inventar'] ;

	}
}else{
		$user_id =  '' ;
		$login =  '' ;
		$forum_glavni =  '' ;
		$forum_odbor =  '' ;
		$forum_sestre =  '' ;
		$forum_gigovici =  '' ;
		$forum_flickor_kan =  '' ;
		$clanstvo =  '' ;
		$kalendar =  '' ;
		$novosti_na_bemufu =  '' ;
		$knjiga_gostiju_admin =  '' ;
		$fotoalbum_admin =  '' ;
		$tekstovi =  '' ;
		$citati =  '' ;
		$linkovi_admin =  '' ;
		$kviz_admin =  '' ;
		$zapisnici_sa_sastanka =  '' ;
		$anketa_admin =  '' ;
		$clanovi_odbora_admin =  '' ;
		$redakcija =  '' ;
		$inventar =  '' ;

}   ?> 

<html>
      <head>
      <meta content='text/html; charset=utf-8' http-equiv='Content-Type'>

      <style type='text/css'>
<!--
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

         <SELECT name='edit_user_id'>

         <?php


           $sql = "SELECT user_id,login  FROM bemuf_user_profil";
           $result = $link->query($sql);

           while($row    = $result->fetch_array()){
              echo  "<OPTION value='$row[user_id]'";
              echo   ($row['user_id']== @$_POST['edit_user_id']) ? " SELECTED" : "" ;
              echo   ">$row[login]</OPTION>";
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
       onclick='return confirm("Ovim potezom cete izbrisati izabranu opciju!\n\n Zelite li to da uradite?");'
       style='background-color:red;color:white;border: blue 1pt solid'>

</form>
<p>


<input type=button
       name='ADDTOLIST'
       value='Prazan formular'
       onclick='location.href = ""'
       title='Dodaj novo na listu'
       style='background-color:green;color:#aaffaa;width:100pt;border: blue 1pt solid'>

<form method=post>

      <input type=hidden
                       name='user_id'
                       value='<?php echo @$user_id ; ?>'>

<tr><td class='lable_class'>Login</td><td class='form_class'><input type=text
                       size='40'
                       name='login'
                       value='<?php echo @$login ; ?>'>
</td></tr>

<tr><td class='lable_class'>Forum glavni</td><td class='form_class'><input type=RADIO
			                       name='forum_glavni'
			                       value='yes'<?php echo (@$forum_glavni == 'yes') ? " CHECKED" : "" ?>>yes
			               <input type=RADIO
			                       name='forum_glavni'
			                       value='no'<?php echo (@$forum_glavni == 'no' || @$forum_glavni == '' ) ? " CHECKED" : "" ?>><b>no</b>
			               </td></tr>

<tr><td class='lable_class'>Forum odbor</td><td class='form_class'><input type=RADIO
			                       name='forum_odbor'
			                       value='yes'<?php echo (@$forum_odbor == 'yes') ? " CHECKED" : "" ?>>yes
			               <input type=RADIO
			                       name='forum_odbor'
			                       value='no'<?php echo (@$forum_odbor == 'no' || @$forum_odbor == '' ) ? " CHECKED" : "" ?>><b>no</b>
			               </td></tr>

<tr><td class='lable_class'>Forum sestre</td><td class='form_class'><input type=RADIO
			                       name='forum_sestre'
			                       value='yes'<?php echo (@$forum_sestre == 'yes') ? " CHECKED" : "" ?>>yes
			               <input type=RADIO
			                       name='forum_sestre'
			                       value='no'<?php echo (@$forum_sestre == 'no' || @$forum_sestre == '' ) ? " CHECKED" : "" ?>><b>no</b>
			               </td></tr>

<tr><td class='lable_class'>Forum gigovici</td><td class='form_class'>
<SELECT name='forum_gigovici'>     
	<OPTION value='yes' <?php echo (@$forum_gigovici == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$forum_gigovici == 'no' || @$forum_gigovici == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Forum flickor kan</td><td class='form_class'>
<SELECT name='forum_flickor_kan'>     
	<OPTION value='yes' <?php echo (@$forum_flickor_kan == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$forum_flickor_kan == 'no' || @$forum_flickor_kan == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Clanstvo</td><td class='form_class'>
<SELECT name='clanstvo'>     
	<OPTION value='yes' <?php echo (@$clanstvo == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$clanstvo == 'no' || @$clanstvo == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Kalendar</td><td class='form_class'>
<SELECT name='kalendar'>     
	<OPTION value='yes' <?php echo (@$kalendar == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$kalendar == 'no' || @$kalendar == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Novosti na bemufu</td><td class='form_class'>
<SELECT name='novosti_na_bemufu'>     
	<OPTION value='yes' <?php echo (@$novosti_na_bemufu == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$novosti_na_bemufu == 'no' || @$novosti_na_bemufu == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Knjiga gostiju admin</td><td class='form_class'>
<SELECT name='knjiga_gostiju_admin'>     
	<OPTION value='yes' <?php echo (@$knjiga_gostiju_admin == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$knjiga_gostiju_admin == 'no' || @$knjiga_gostiju_admin == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Fotoalbum admin</td><td class='form_class'>
<SELECT name='fotoalbum_admin'>     
	<OPTION value='yes' <?php echo (@$fotoalbum_admin == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$fotoalbum_admin == 'no' || @$fotoalbum_admin == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Tekstovi</td><td class='form_class'>
<SELECT name='tekstovi'>     
	<OPTION value='yes' <?php echo (@$tekstovi == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$tekstovi == 'no' || @$tekstovi == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Citati</td><td class='form_class'>
<SELECT name='citati'>     
	<OPTION value='yes' <?php echo (@$citati == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$citati == 'no' || @$citati == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Linkovi admin</td><td class='form_class'>
<SELECT name='linkovi_admin'>     
	<OPTION value='yes' <?php echo (@$linkovi_admin == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$linkovi_admin == 'no' || @$linkovi_admin == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Kviz admin</td><td class='form_class'>
<SELECT name='kviz_admin'>     
	<OPTION value='yes' <?php echo (@$kviz_admin == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$kviz_admin == 'no' || @$kviz_admin == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Zapisnici sa sastanka</td><td class='form_class'>
<SELECT name='zapisnici_sa_sastanka'>     
	<OPTION value='yes' <?php echo (@$zapisnici_sa_sastanka == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$zapisnici_sa_sastanka == 'no' || @$zapisnici_sa_sastanka == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Anketa admin</td><td class='form_class'>
<SELECT name='anketa_admin'>     
	<OPTION value='yes' <?php echo (@$anketa_admin == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$anketa_admin == 'no' || @$anketa_admin == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Clanovi odbora admin</td><td class='form_class'>
<SELECT name='clanovi_odbora_admin'>     
	<OPTION value='yes' <?php echo (@$clanovi_odbora_admin == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$clanovi_odbora_admin == 'no' || @$clanovi_odbora_admin == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Redakcija</td><td class='form_class'>
<SELECT name='redakcija'>     
	<OPTION value='yes' <?php echo (@$redakcija == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$redakcija == 'no' || @$redakcija == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

<tr><td class='lable_class'>Inventar</td><td class='form_class'>
<SELECT name='inventar'>     
	<OPTION value='yes' <?php echo (@$inventar == 'yes') ? "SELECTED" : "" ?>>yes</OPTION>  
	<OPTION value='no'  <?php echo (@$inventar == 'no' || @$inventar == '' ) ? "SELECTED" : "" ?>>no</OPTION>  

</SELECT>

</td></tr>

       <tr>
         <td> </td>
         <td>
         <input type=submit name='ADD' value='Posalji'>
         <input type=reset value='Izbrisi'></td></tr>
	</form>
</table>
