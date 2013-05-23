<?php
include_once("../session.php");
include_once("../header.php");
include_once('class-template.php');

$PATH=$_SERVER[DOCUMENT_ROOT]."/templates/";
$obj_template= new Template_information($PATH);
$dir=$obj_template->ReadFolderDirectory($PATH);
$file="";
$type='HTML';
if(!empty($type)){
	switch($type)
	{
		case 'HTML':
			$file = $PATH."$theme/index.html"; //File to edit
			break;
		case 'CSS':
			$file = $PATH."$theme/css/template.css"; //File to edit
			break;
		default:
			break;
			$file = $PATH."$theme/index.html"; //File to edit		
	}
}
else
 $file = $PATH."$theme/index.html"; //File to edit	

#################### ACTION ##########################

switch($_POST['action']){
		
		case 'save':
		$file=$_POST['file'];
	    $content=$_POST['pcontent'];
		$msg=save_file($file,$content);
		header("location:manage.php?theme=$theme&msg=$msg");
		exit();
		break;
	
	
				
}

function save_file($file,$content)
{
	
	
if(isset($content))
  {
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
 $configmod = substr(sprintf('%o', fileperms($file)), -4);
 
 if($configmod != '0777')
 {
 	if(!chmod($file,777)) 
 	$Message ='<div class="error"><img src="../../images/crose.png" align="absmiddle"> Unable to change permission of this file automatically.Please change permission of this file by using FTP software</div>';
 	$Message.= '<div class="error"><img src="../../images/crose.png" align="absmiddle"> '. $file .'  has  '.$configmod .' permissions. But required Permission is 0777 to access.</div>';
 }
################## MESSAGE ##################
switch($msg)
{
	case 'd':
		 $Message ='<div class="success"><img src="../../images/tick.png" align="absmiddle"> Template Changes Saved Successfully</div>';
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
?>
<?php echo $Message;?>
<?php
//   if(trim(substr(base_convert(fileperms($file), 10, 8), 4))!="0777"){
//      echo '<div class="error"><img src="../../images/crose.png" align="absmiddle"> Permission denied : Set Permission 777 for updation</div>';
//   }
?>

<?php function encodeHTML($sHTML)
{
$sHTML=ereg_replace("&","&amp;",$sHTML);
$sHTML=ereg_replace("<","&lt;",$sHTML);
$sHTML=ereg_replace(">","&gt;",$sHTML);
return $sHTML;
}

?>
<script>
    $(document).ready(function() {
        $("#token").hide();
       $("#labeltoken").click(function () {
           $("#token").addClass("slider");
            $("#token").slideToggle('slow',function(){
               
            });
        });
    });
</script>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
 <div class="labeltoken" id="labeltoken">
        <div class="labelinner">
            <div class="rotate">Placeholders</div>
        </div>
     </div>
<p><strong style="text-transform:capitalize"><?php echo $theme;?> Template HTML</strong></p>

<div class="formborder">
<div style="width:100%;float:left;padding:10px;border_bottom:1px solid #f4f4f4;">
<a href="manage.php?theme=<?php echo $theme?>">View HTML</a>
<a href="change-css.php?theme=<?php echo $theme?>">View CSS</a>
</div>
   
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td style="padding:10px;">
	<div>
	<form action="manage.php?theme=<?php echo $theme?>" method="post" name="from">	
		
	
		
		<textarea  cols="135" rows="30" class="inputbox" id="pcontent" name="pcontent">
			<?php echo encodeHTML(file_get_contents($file));?>
		</textarea>

<?php if(empty($type)){?>	
	<script>
	var oEdit1 = new InnovaEditor("oEdit1");

   var oEdit1 = new InnovaEditor("oEdit1");
    oEdit1.width="700px";
    oEdit1.height="450px";
	oEdit1.toolbarMode = 1;
	
	
	var oEdit1 = new InnovaEditor("oEdit1");

    oEdit1.width = 630;
    oEdit1.height = 500;

   
    oEdit1.groups = [
    ["grpEdit1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
    ["grpEdit2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
    ["grpEdit3", "", ["TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog", "ImageDialog", "YoutubeDialog"]],
    ["grpEdit4", "", ["CharsDialog", "Line", "BRK",  "CustomTag"]],
    ["grpEdit5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo", "FullScreen"]]
    ];

   
    /*Enable Custom File Browser */
	oEdit1.fileBrowser = "/admin/Editor/assetmanager/asset.php"; //Command to open the Asset Manager add-on.

    /*Define "CustomTag" dropdown */
    oEdit1.arrCustomTag=	[["Settings Keywords","{$settings_keywords}"],
    						 ["Settings Description","{$settings_description}"],
    						 ["Settings Sitename","{$settings_sitename}"],
							 ["Extra Meta","{$settings_meta}"],
    						 ["Custom CSS Property ","{$template_<?php echo $theme?>_css}"],
    						 
    						 ["Menus","{$menu_main}"],
					       	 ["Content","{$content}"],
					      // ["Left Panel","{$left_panel}"],
					 		 ["Right Panel","{$right_panel}"],
					    	 ["Settings Tracking","{$settings_tracking}"]
					 		];//Define custom tag selection

    /*Apply stylesheet for the editing content*/
    oEdit1.css = "/admin/Editor/styles/default.css";

   
    oEdit1.mode="XHTML"; //Editing mode. Possible values: "HTMLBody" (default), "XHTMLBody", "HTML", "XHTML"
	oEdit1.REPLACE("pcontent");
	</script>
<?php }?>	
<div style="width:100%;float:left">	
	<input type="hidden" name="file" value="<?php echo $file?>" >
	<input type="hidden" name="action" value="save">
	<input type="submit" name="submit" value="Save Content">
</div>			
	
	</form>
	</td>
	
	
</tr>

</table>


</div>

<div class="tokens" id="token">
       <span style="color:red">Important Token</span>
       <ul>
        <li>$settings_keywords}<br /><small>Placeholder show Keyword in your templates</small></li>
        <li>{$settings_description} <br /><small>Placeholder show Website Meta Description in your templates</small></li>
        <li>{$settings_sitename}<br /><small>Placeholder show Website Name in your templates</small></li>
        <li>{$settings_meta}<br /><small>To show additional meta in template</small></li>
        <li>{$template_xxx_css}<br /><small>Placeholder allow you to add your custom CSS using Template Name in your templates</small></li>
        <li>{$menu_main}<br /><small>Placeholder allow you to add your Menus in your templates, You get this placeholder from Menu manager</small></li>
        <li>{$content}<br /><small>Placeholder allow you to add all the content in your template like Text,Images, Videos, Audio etc</small></li>
        <li>{$right_panel}/{$siderbar}<br /><small>Placeholder allow you to add Blog and Member area right section. </small></li>
        <li>{$settings_tracking}<br /><small>Placeholder allow you to add Google Analytics and other tracking JavaScripts. </small></li>
       </ul>
   </div>








</div>
<div class="content-wrap-bottom"></div>
</div>
<?php include_once("../footer.php");?>
