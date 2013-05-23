<?php
include_once("../session.php");
include_once("../header.php");
include_once('class-template.php');

/*****************************************************/

$sql_template = "select * from ".$prefix."template where name='$theme'";
$row_template = $db->get_a_line($sql_template);
/********************************************************************/
$body=explode(';',$row_template['custom_body']);
foreach($body as $key => $itmes )
{
	$property = explode(':',$itmes);
	if($property[0]=='background-color')
		$body_background_color=$property[1];
	else if($property[0]=='font-size')
		$body_font_size	=$property[1];
	else if($property[0]=='color')
		$body_font_color	=$property[1];
	else if($property[0]=='font-family')
		$body_font_family	=$property[1];		
	
}
/********************************************************************/
$header=explode(';',$row_template['custom_header']);
foreach($header as $key => $itmes )
{

	$property = explode(':',$itmes);
	if($property[0]=='background-color')
		$header_background_color=$property[1];
	else if($property[0]=='font-size')
		$header_font_size	=$property[1];
	else if($property[0]=='color')
		$header_font_color	=$property[1];
	else if($property[0]=='font-family')
		$header_font_family	=$property[1];		
	
}


/********************************************************************/

$content=explode(';',$row_template['custom_content']);
foreach($content as $key => $itmes )
{
	
	$property = explode(':',$itmes);
	if($property[0]=='background-color')
		$content_background_color=$property[1];
	else if($property[0]=='font-size')
		$content_font_size	=$property[1];
	else if($property[0]=='color')
		$content_font_color	=$property[1];
	else if($property[0]=='font-family')
		$content_font_family	=$property[1];		
	
}

/********************************************************************/

$footer=explode(';',$row_template['custom_footer']);

foreach($footer as $key => $itmes )
{
	
	$property = explode(':',$itmes);
	if($property[0]=='background-color')
		$footer_background_color=$property[1];
	else if($property[0]=='font-size')
		$footer_font_size	=$property[1];
	else if($property[0]=='color')
		$footer_font_color	=$property[1];
	else if($property[0]=='font-family')
		$footer_font_family	=$property[1];		
	
}
#################### ACTION ##########################
if($_POST['save']){
	$body='';
	$header='';
	$content='';
	$footer='';
		if(!empty($_POST['background_color_general']))
		{
		    $body.="background-color:$_POST[background_color_general];";
		   	$body.="background-image:none;";
		}
		if(!empty($_POST['font_size_general']))
			$body.="font-size:$_POST[font_size_general];";
		if(!empty($_POST['font_color_general']))
			$body.="color:$_POST[font_color_general];";
		if(!empty($_POST['font_family_general']))	
			$body.="font-family:$_POST[font_family_general];";
		
			

		if(!empty($_POST['background_color_header']))
		{
			$header.="background-color:$_POST[background_color_header];";
			$header.="background-image:none;";
		}
		if(!empty($_POST['font_size_header']))
			$header.="font-size:$_POST[font_size_header];";
		if(!empty($_POST['font_color_header']))
			$header.="color:$_POST[font_color_header];";
		if(!empty($_POST['font_family_header']))
			$header.="font-family:$_POST[font_family_header];";
			

			
		if($_POST['background_color_content'])
		{
			$content.="background-color:$_POST[background_color_content];";
			$content.="background-image:none;";
		}
		if($_POST['font_size_content'])
			$content.="font-size:$_POST[font_size_content];";
		if($_POST['font_color_content'])
			$content.="color:$_POST[font_color_content];";
		if($_POST['font_family_content'])	
			$content.="font-family:$_POST[font_family_content];";
		

			
		if($_POST['background_color_footer'])
		{
			$footer="background-color:$_POST[background_color_footer];";
			$footer.="background-image:none;";
		}
		if($_POST['font_size_footer'])
			$footer.="font-size:$_POST[font_size_footer];";
		if($_POST['font_color_footer'])
			$footer.="color:$_POST[font_color_footer];";
		if($_POST['font_family_footer'])		
			$footer.="font-family:$_POST[font_family_footer];";
		
		//	echo "$body<br>$header<br>$content<br>$footer";
		
		$msg=save_data($db,$prefix,$body,$header,$content,$footer,$theme);
		header("location:change-css.php?theme=$theme&msg=$msg");
		//exit();
		
		}
else if($_POST['reset']){		
	$body='';
	$header='';
	$content='';
	$footer='';
    $msg=save_data($db,$prefix,$body,$header,$content,$footer,$theme);
	header("location:change-css.php?theme=$theme&msg=$msg");
	exit();
	
	}
	else if($_POST['type']=='CSS') {
	$msg=save_file($_POST['file'],$_POST['content']);
	header("location:change-css.php?theme=$theme&type=CSS&msg=$msg");
	exit();
}

function save_file($file,$content)
{
	
	
if(isset($content))
  {
  	
  	//$filechange = '/home/rapidresidualpro/admin/managetemplate/change-css.php';
  	//chmod($filechange,'0777'); 
  	//echo "File Permission: ".$configmod = substr(sprintf('%o', fileperms($filechange)), -4);exit;
  	if (is_writable($file)) {
  	 $content=stripslashes($content);
  	  $handle = fopen($file,'w');
  	  if (fwrite($handle, $content) === FALSE)
	 	 $msg='c';
	 else
	 	 $msg='d';  
  	}
  	else
  	$msg='e'; 
  }
  else 
  $msg='n';
  

	
	return $msg;
}
function save_data($db,$prefix,$body,$header,$content,$footer,$template)
{


	$sql="update ".$prefix."template set
	custom_body ='$body',
	custom_header ='$header',
	custom_content ='$content',
	custom_footer ='$footer'
	where name='$template'";

	$db->insert($sql);


	return 'd';
}
################## MESSAGE ##################
switch($msg)
{
	case 'd':
		$Message ='<div class="success"><img src="../../images/tick.png" align="absmiddle"> CSS is customized</div>';
		break;
	case 'e':
		$Message ='<div class="error"><img src="../../images/crose.png" align="absmiddle"> Unable to write a file</div>';
		break;
	case 'c':
		$Message ='<div class="error"><img src="../../images/crose.png" align="absmiddle"> Can not write a file</div>';
		break;
	case 'n':
		$Message ='<div class="error"><img src="../../images/crose.png" align="absmiddle"> No content found</div>';
		break;
}
$type='CSS';
if($type == 'CSS'){
	$PATH=$_SERVER[DOCUMENT_ROOT]."/templates/";	
	$file = $PATH."$theme/css/template.css"; //File to edit	
	$configmod = substr(sprintf('%o', fileperms($file)), -4);
	 if($configmod != '0777')
	 {
	 	if(!chmod($file,777)) 
 		$Message ='<div class="error"><img src="../../images/crose.png" align="absmiddle"> Unable to change permission of this file automatically.Please change permission of this file by using FTP software</div>';
		$Message.= '<div class="error"><img src="../../images/crose.png" align="absmiddle"> '. $file .'  has  '.$configmod .' permissions. But required Permission is 0777 to access.</div>';
	 }
}

?>
<?php echo $Message;?>
<?php function encodeHTML($sHTML)
{
	$sHTML=ereg_replace("&","&amp;",$sHTML);
	$sHTML=ereg_replace("<","&lt;",$sHTML);
	$sHTML=ereg_replace(">","&gt;",$sHTML);
	return $sHTML;
}

?>
<link
	rel="stylesheet" href="css/farbtastic.css" type="text/css" />
<style type="text/css" media="screen">
.colorwell {
	border: 1px solid #f4f4f4;
	text-align: center;
	cursor: pointer;
	width: 100px;
	padding: 4px;
}

body .colorwell-selected {
	border: 1px solid #c4c4c4;
	font-weight: bold;
}
</style>
<script
	type="text/javascript" src="js/farbtastic.js"></script>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
    $('#demo').hide();
    var f = $.farbtastic('#picker');
    var p = $('#picker').css('opacity', 1);
    var selected;
    $('.colorwell')
      .each(function () { f.linkTo(this); $(this).css('opacity', 1); })
      .focus(function() {
        if (selected) {
          $(selected).css('opacity',1).removeClass('colorwell-selected');
        }
        f.linkTo(this);
        p.css('opacity', 1);
        $(selected = this).css('opacity', 1).addClass('colorwell-selected');
      });
  });
 </script>

<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong style="text-transform: capitalize"><?php echo $theme;?> Template  CSS</strong></p>


<div class="formborder">
<div style="width:100%;float:left;padding:10px;border_bottom:1px solid #f4f4f4;">
<a href="manage.php?theme=<?php echo $theme?>">View HTML</a>
<a href="change-css.php?theme=<?php echo $theme?>">View CSS</a>
</div>
<?php if($type == 'CSS'){
$PATH=$_SERVER[DOCUMENT_ROOT]."/templates/";	
$file = $PATH."$theme/css/template.css"; //File to edit	


?>
<form action="change-css.php?theme=<?php echo $theme?>" method="post" name="from">	
<textarea name="content"  cols="135" rows="30" class="inputbox" style="font-size: 12px; font-family: verdana;margin-left:10px; line-height: 22px; color: rgb(204, 0, 0);">
	<?php echo encodeHTML(file_get_contents($file));?>
</textarea>
<input type="hidden" name="file" value="<?php echo $file?>" >
<input type="hidden" name="type" value="CSS">
<input type="submit" name="submit" value="Save Content">
	
	
	</form>		
<?php } else {  ?>

<table cellpadding="0" cellspacing="4" border="0" width="100%">
	<tr>
		<td style="padding: 10px;" width="70%">
		<div>
		<form action="change-css.php?theme=<?php echo $theme?>" method="post"
			name="from">
		<fieldset><legend>General Settings</legend>
		<table cellpadding="5" cellspacing="5" background="0">
			<tr>
				<td>Background Color</td>
				<td><input type="text"  name="background_color_general" value="<?php  echo $body_background_color?>" /></td>
			</tr>
			<tr>
				<td>Font Family</td>
				<td><select name="font_family_general">
					<option value="Arial"<?php if($body_font_family=='Arial') echo 'selected'?>>Arial</option>
					<option value="Helvetica" <?php if($body_font_family=='Helvetica') echo 'selected'?>>Helvetica</option>
					<option value="sans-serif"<?php if($body_font_family=='sans-serif') echo 'selected'?>>Sans Serif</option>
					<option value="Times New Roman"	<?php if($body_font_family=='Times New Roman') echo 'selected'?>>Times New Roman</option>
					<option value="Tahoma"	<?php if($body_font_family == 'Tahoma') echo 'selected'?>>Tahoma</option>
					<option value="Courier New"	<?php if($body_font_family == 'Courier New') echo 'selected'?>>Courier New</option>
					
					
					<option value="Georgia"
					<?php if($body_font_family == 'Georgia') echo 'selected'?>>Georgia</option>
					<option value="Verdana"
					<?php if($body_font_family == 'Verdana') echo 'selected'?>>Verdana</option>
					<option value="Geneva"><?php if($body_font_family=='Geneva') echo 'selected'?>Geneva</option>
				</select></td>
			</tr>
			<tr>
				<td>Font Color</td>
				<td><input type="text" name="font_color_general"
					
					value="<?php  echo $body_font_color?>" /></td>
			</tr>
			<tr>
				<td>Font Size</td>
				<td><input type="text" id="font_size_general"
					name="font_size_general"
					value="<?php  echo $body_font_size?>"
					size="4" /> px</td>
			</tr>
		</table>
		</fieldset>
		<fieldset><legend>Header Section</legend>
		<table cellpadding="5" cellspacing="5" background="0">
			<tr>
				<td>Background Color</td>
				<td><input type="text" name="background_color_header" value="<?php  echo $header_background_color?>" /></td>
			</tr>
			<tr>
				<td>Font Family</td>
				<td>
				<select name="font_family_header">
					<option  
					<?php if($header_font_family=='') echo 'selected'?>> </option>
					<option value="Arial"
					<?php if($header_font_family=='Arial') echo 'selected'?>>Arial</option>
					<option value="Helvetica"
					<?php if($header_font_family=='Helvetica') echo 'selected'?>>Helvetica</option>
					<option value="sans-serif"
					<?php if($header_font_family=='sans-serif') echo 'selected'?>>Sans
					Serif</option>
					<option value="Times New Roman"
					<?php if($header_font_family=='Times New Roman') echo 'selected'?>>Times
					New Roman</option>
					<option value="Tahoma"	<?php if($header_font_family == 'Tahoma') echo 'selected'?>>Tahoma</option>
					<option value="Courier New"
					<?php if($header_font_family=='Courier New') echo 'selected'?>>Courier
					New</option>
					<option value="Georgia"
					<?php if($header_font_family == 'Georgia') echo 'selected'?>>Georgia</option>
					<option value="Verdana"
					<?php if($header_font_family=='Verdana') echo 'selected'?>>Verdana</option>
					<option value="Geneva"><?php if($header_font_family=='Geneva') echo 'selected'?>Geneva</option>
				</select></td>
			</tr>
			<tr>
				<td>Font Color</td>
				<td><input type="text" name="font_color_header"	value="<?php echo $header_font_color?>" /></td>
			</tr>
			<tr>
				<td>Font Size</td>
				<td><input type="text" name="font_size_header"
					value="<?php  echo $header_font_size?>"
					size="5"> px</td>
			</tr>
		</table>
		</fieldset>

		<fieldset><legend>Content Section</legend>
		<table cellpadding="5" cellspacing="5" background="0">
			<tr>
				<td>Background Color</td>
				<td><input type="text"  name="background_color_content"	value="<?php echo $content_background_color?>" /></td>
			</tr>
			<tr>
				<td>Font Family</td>
				
				<td><select name="font_family_content">
					<option 
					<?php if($content_font_family=='') echo 'selected'?>> </option>
					<option value="Arial"
					<?php if($content_font_family == 'Arial') echo 'selected'?>>Arial</option>
					<option value="Helvetica"
					<?php if($content_font_family == 'Helvetica') echo 'selected'?>>Helvetica</option>
					<option value="sans-serif"
					<?php if($content_font_family == 'sans-serif') echo 'selected'?>>Sans	Serif</option>
					<option value="Times New Roman"
					<?php if($content_font_family == 'Times New Roman') echo 'selected'?>>Times
					New Roman</option>
					<option value="Tahoma"	<?php if($content_font_family == 'Tahoma') echo 'selected'?>>Tahoma</option>
					<option value="Courier New"
					<?php if($content_font_family == 'Courier New') echo 'selected'?>>Courier
					New</option>
					<option value="Georgia"
					<?php if($content_font_family == 'Georgia') echo 'selected'?>>Georgia</option>
					<option value="Verdana"
					<?php if($content_font_family == 'Verdana') echo 'selected'?>>Verdana</option>
					<option value="Geneva"><?php if($content_font_family == 'Geneva') echo 'selected'?>Geneva</option>
				</select></td>
			</tr>
			<tr>
				<td>Font Color</td>
				<td><input type="text"  name="font_color_content" value="<?php  echo $content_font_color?>" /></td>
			</tr>
			<tr>
				<td>Font Size</td>
				<td><input type="text" name="font_size_content"
					value="<?php  echo $content_font_size?>"
					size="5"> px</td>
			</tr>
		</table>
		</fieldset>

		<fieldset><legend>Footer Section</legend>
		<table cellpadding="5" cellspacing="5" background="0">
			<tr>
				<td>Background Color</td>
				<td><input type="text"  name="background_color_footer" value="<?php  echo $footer_background_color;?>" /></td>
			</tr>
			<tr>
				<td>Font Family</td>
				<td><select name="font_family_footer">
					<option 
					<?php if($footer_font_family=='') echo 'selected'?>> </option>
					<option value="Arial"
					<?php if($footer_font_family=='Arial') echo 'selected'?>>Arial</option>
					<option value="Helvetica"
					<?php if($footer_font_family=='Helvetica') echo 'selected'?>>Helvetica</option>
					<option value="sans-serif"
					<?php if($footer_font_family=='sans-serif') echo 'selected'?>>Sans
					Serif</option>
					<option value="Times New Roman"
					<?php if($footer_font_family=='Times New Roman') echo 'selected'?>>Times
					New Roman</option>
					<option value="Tahoma"	<?php if($footer_font_family == 'Tahoma') echo 'selected'?>>Tahoma</option>
					<option value="Courier New"
					<?php if($footer_font_family=='Courier New') echo 'selected'?>>Courier
					New</option>
					<option value="Georgia"
					<?php if($footer_font_family == 'Georgia') echo 'selected'?>>Georgia</option>
					<option value="Verdana"
					<?php if($footer_font_family=='Verdana') echo 'selected'?>>Verdana</option>
					<option value="Geneva"><?php if($footer_font_family=='Geneva') echo 'selected'?>Geneva</option>
				</select></td>
			</tr>
			<tr>
				<td>Font Color</td>
				<td><input type="text" name="font_color_footer" value="<?php echo $footer_font_color?>" /></td>
			</tr>
			<tr>
				<td>Font Size</td>
				<td><input type="text" name="font_size_footer"
					value="<?php   echo $footer_font_size?>"
					size="5"> px</td>
			</tr>
		</table>
		</fieldset>

		
		 <input type="submit" name="save" value="Save Content">
		 <input type="submit" name="reset" value="Reset Content">	
			</form>
		
		</td>
		<td valign="top">
		<h3>Avaliable Colors</h3>
		<div class="tokens" style="width: auto"> <div id="picker" style="float: right;"></div></div>
		<div style=" float: left;  margin: 15px 42px; width: 100%;">
		<input type="text" id="color1" name="color"	class="colorwell" value="#000000" />
		</div>
		</td>

	</tr>

</table>
<?php }  ?>

</div>










</div>
<div class="content-wrap-bottom"></div>
</div>
					<?php include_once("../footer.php");?>
