<?php
ini_set('enable_post_data_reading','0');
include "session.php";
include "header.php";
//$GetFile = file("../html/admin/mindex.html");
//$Content = join("", $GetFile);

/*function encodeHTML($sHTML)
{
	$sHTML=ereg_replace("&","&amp;",$sHTML);
	$sHTML=ereg_replace("<","&lt;",$sHTML);
	$sHTML=ereg_replace(">","&gt;",$sHTML);
	return $sHTML;
}*/

if (isset($_POST['submit']))
{
	$member_main		= addslashes($_POST["member_main"]);
	$jv_main			= addslashes($_POST["jv_main"]);
	$affiliate_main		= addslashes($_POST["affiliate_main"]);
	$memb_menu 			= $_POST["member_menu"];
	$jv_menu 			= $_POST["jv_menu"];
	$aff_menu 			= $_POST["aff_menu"];

	$set = "member_main  = '{$member_main}', ";
	$set .= "jv_main = '{$jv_main}', ";
	$set .= "affiliate_main	= '{$affiliate_main}',";
	$set .= "member_menu_id = '{$memb_menu}', ";
	$set .= "affiliate_menu_id = '{$aff_menu}', ";
	$set .= "jv_menu_id = '{$jv_menu}'";

	$q = "update ".$prefix."misc_pages set $set where id='1'";
	
	$db->insert($q);
	$msg = "<div class='success'><img src='/images/tick.png' align='absmiddle'>Index Pages Successfully Edited.</div>";
}

$mysql = "select * from ".$prefix."misc_pages where id='1'";
$rslt = $db->get_a_line($mysql);
$member_main = stripslashes($rslt["member_main"]);
$memb_menus = stripslashes($rslt["member_menu_id"]);
$jv_main = stripslashes($rslt["jv_main"]);
$jv_menus = stripslashes($rslt["jv_menu_id"]);
$affiliate_main = stripslashes($rslt["affiliate_main"]);
$aff_menus = stripslashes($rslt["affiliate_menu_id"]);

// Getting menu items
	$qry_menu = "select * from ".$prefix."menus where published = '1' ORDER BY id ASC";
	$res_menu = $db->get_rsltset($qry_menu);
// Member menu combo box
	$member_menu = "<select name='member_menu' id='member_menu'><option value='0'>Select menu</option>";
	for($i = 0; $i < count($res_menu); $i++){
		$member_menu_name = $res_menu[$i]['menu_name'];
		$member_menu_id = $res_menu[$i]['id'];
		if($member_menu_id == $memb_menus){
			$mem_selected = "selected = 'selected'";
		}else{
			$mem_selected = "";	
		}	
		
		$member_menu .= "<option value='".$member_menu_id."' ".$mem_selected.">".$member_menu_name."</option>";	
	}
	$member_menu .= "</select>";

// JV menu combo box
	$jv_menu = "<select name='jv_menu' id='jv_menu'><option value='0'>Select menu</option>";
	for($j = 0; $j < count($res_menu); $j++){
		$jv_menu_name = $res_menu[$j]['menu_name'];
		$jv_menu_id = $res_menu[$j]['id'];
		if($jv_menu_id == $jv_menus){
			$jv_selected = "selected = 'selected'";
		}else{
			$jv_selected = "";	
		}	
		$jv_menu .= "<option value='".$jv_menu_id."' ".$jv_selected.">".$jv_menu_name."</option>";	
	}
	$jv_menu .= "</select>";

// Affiliate menu combo box
	$aff_menu = "<select name='aff_menu' id='aff_menu'><option value='0'>Select menu</option>";
	for($k = 0; $k < count($res_menu); $k++){
		
		$aff_menu_name = $res_menu[$k]['menu_name'];
		$aff_menu_id = $res_menu[$k]['id'];
		if($aff_menu_id == $aff_menus){
			$aff_selected = "selected = 'selected'";
		}else{
			$aff_selected = "";	
		}	
		$aff_menu .= "<option value='".$aff_menu_id."' ".$aff_selected.">".$aff_menu_name."</option>";	
	
	}
	$aff_menu .= "</select>";
	

//$Content = preg_replace($Ptn,"$$1",$Content);
?>
<!-- ###################### Error Message Start ###################### -->
<?php echo $msg?>

<!-- ###################### Error Message End ###################### -->
<!-- ###################### Content Area Start ###################### -->
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong>Member  Area Index Pages</strong></p>
<div class="formborder"><br />
<br />
<form action="mindex.php" method="post" name="form">
  <table width="95%" align="center" border="0">
<tr>
  <td width="100%" align="left" class="tbtext">&nbsp;</td>
</tr>
<tr>
  <td align="left" class="tbtext"><b>Member Menu:</b></td>
</tr>
<tr>
  <td align="left" class="tbtext"><?php echo $member_menu?></td>
</tr>
<tr>
  <td width="100%" align="left" class="tbtext">&nbsp;</td>
</tr>
<tr>
  <td align="left" class="tbtext"><b>Member Index:
  </b></td>
</tr>
<tr>
  <td align="left" class="tbtext">
	<textarea name="member_main" cols="100" rows="8" class="inputbox" id="member_main"><?php echo $member_main?></textarea>
	<script type="text/javascript" language="JavaScript">
	var oEdit1 = new InnovaEditor("oEdit1");
    oEdit1.width = 700;
    oEdit1.height = 450;
	
   
    oEdit1.groups = [
    ["grpEdit1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
    ["grpEdit2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
    ["grpEdit3", "", ["TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog", "ImageDialog", "YoutubeDialog"]],
    ["grpEdit4", "", ["CharsDialog", "Line", "BRK","CustomTag","HTML5Video"]], ["grpEdit5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo", "FullScreen"]]
    ];
   if (oEdit1.fileBrowser != "") {
        oEdit1.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,650,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    } else { /* If file browser not used, reduce the HTML5 Video dialog height */
        oEdit1.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,330,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    }
    /*Enable Custom File Browser */
	
	oEdit1.css = "/admin/Editor/scripts/bootstrap/css/bootstrap.min.css";	
	oEdit1.fileBrowser = "/admin/Editor/assetmanager/asset.php";
    /*Define "CustomTag" dropdown */
    oEdit1.arrCustomTag=[["First Name","{\{firstname\}}"]];//Define custom tag selection
    /*Apply stylesheet for the editing content*/
    
    /*Render the editor*/
   
	
	oEdit1.REPLACE("member_main");
	</script>	</td>
  </tr>
<tr>
  <td align="left" class="tbtext">&nbsp;</td>
</tr>

  <td align="left" class="tbtext">&nbsp;</td>
</tr>
<tr>
  <td align="left" class="tbtext"><b>JV Menu:</b></td>
</tr>
<tr>
  <td align="left" class="tbtext"><?php echo $jv_menu?></td>
</tr>
<tr>
  <td width="100%" align="left" class="tbtext">&nbsp;</td>
</tr>

<tr>
  <td align="left" class="tbtext"><b>JV Index:
  </b></td>
  </tr>
<tr>
  <td align="left" class="tbtext">&nbsp;</td>
</tr>
<tr>
  <td align="left" class="tbtext">
	<textarea name="jv_main" cols="100" rows="8" class="inputbox" id="jv_main"><?php echo $jv_main?></textarea>
	<script type="text/javascript" language="JavaScript">
	var oEdit2 = new InnovaEditor("oEdit2");
	
    oEdit2.width = 700;
    oEdit2.height = 450;
   
   oEdit2.groups = [
    ["grpEdit1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
    ["grpEdit2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
    ["grpEdit3", "", ["TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog", "ImageDialog", "YoutubeDialog"]],
    ["grpEdit4", "", ["CharsDialog", "Line", "BRK","CustomTag","HTML5Video"]], ["grpEdit5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo", "FullScreen"]]
    ];
   if (oEdit2.fileBrowser != "") {
        oEdit1.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,650,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    } else { /* If file browser not used, reduce the HTML5 Video dialog height */
        oEdit2.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,330,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    }
    /*Enable Custom File Browser */
    /*Enable Custom File Browser */
		oEdit2.arrCustomButtons = [["Snippets", "modalDialog('/admin/Editor/scripts/bootstrap/snippets.htm',860,530,'Insert Snippets');", "Bootstrap", "btnContentBlock.gif"]];
	oEdit2.css = "/admin/Editor/scripts/bootstrap/css/bootstrap.min.css";	
	
	oEdit2.fileBrowser = "/admin/Editor/assetmanager/asset.php";
    /*Define "CustomTag" dropdown */
    oEdit2.arrCustomTag=[["First Name","{\{firstname\}}"]];//Define custom tag selection
    /*Apply stylesheet for the editing content*/
  
    /*Render the editor*/
   
	
	oEdit2.REPLACE("jv_main");
	</script>	
	</td>
  </tr>
<tr>
  <td align="left" class="tbtext"><b>Affiliate Menu:</b></td>
</tr>
<tr>
  <td align="left" class="tbtext"><?php echo $aff_menu?></td>
</tr>
<tr>
  <td width="100%" align="left" class="tbtext">&nbsp;</td>
</tr>
<tr>
  <td align="left" class="tbtext"><b>Affiliate Index:
  </b></td>
  </tr>
 
  <tr>
	<td align="left" class="tbtext">
        <textarea name="affiliate_main" cols="100" rows="8" class="inputbox" id="affiliate_main"><?php echo $affiliate_main?></textarea>
        <script type="text/javascript" language="JavaScript">
     
	var oEdit3 = new InnovaEditor("oEdit3");
    oEdit3.width = 700;
    oEdit3.height = 450;
	
   
    oEdit3.groups = [
    ["grpEdit1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
    ["grpEdit2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
    ["grpEdit3", "", ["TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog", "ImageDialog", "YoutubeDialog"]],
    ["grpEdit4", "", ["CharsDialog", "Line", "BRK","CustomTag","HTML5Video"]], ["grpEdit5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo", "FullScreen"]]
    ];
   if (oEdit3.fileBrowser != "") {
        oEdit3.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,650,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    } else { /* If file browser not used, reduce the HTML5 Video dialog height */
        oEdit3.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,330,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    }
    /*Enable Custom File Browser */
	oEdit3.arrCustomButtons = [["Snippets", "modalDialog('/admin/Editor/scripts/bootstrap/snippets.htm',860,530,'Insert Snippets');", "Bootstrap", "btnContentBlock.gif"]];
	oEdit3.css = "/admin/Editor/scripts/bootstrap/css/bootstrap.min.css";	
	
	oEdit3.fileBrowser = "/admin/Editor/assetmanager/asset.php";
    /*Define "CustomTag" dropdown */
    oEdit3.arrCustomTag=[["First Name","{\{firstname\}}"]];//Define custom tag selection
    /*Apply stylesheet for the editing content*/
   
    /*Render the editor*/
        oEdit3.REPLACE("affiliate_main");
        </script>
	</td>
</tr>
<tr>
  <td align="left" class="tbtext">&nbsp;</td>
</tr>





<tr>
	<td align="left" class="tbtext">&nbsp;</td>
</tr>
<tr>
	<td align="left">
		<input type="submit" name="submit" value="Update Index Page" class="inputbox">
    </td>
</tr>
<tr>
<td align="left">
<br>
<ul>
  <li><font class=tbtext><b>Member Index </b> is the main index page for the members area.</font></li>
  </ul></td>
</tr>
</table>
</form>

<br />
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>
<!-- ###################### Content Area Close ###################### -->
<?php include "footer.php";?>