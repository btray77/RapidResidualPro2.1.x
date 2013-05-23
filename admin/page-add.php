<?php
include "session.php" ;
include "header.php" ;
//$GetFile = file("../html/admin/page-add.html");
//$Content = join("", $GetFile);
function encodeHTML($sHTML)
{
	$sHTML=ereg_replace("&","&amp;",$sHTML);
	$sHTML=ereg_replace("<","&lt;",$sHTML);
	$sHTML=ereg_replace(">","&gt;",$sHTML);
	return $sHTML;
}
if (isset($_POST['submit']))
{
	// Parse form input through Post
	$pcontent		= $db->quote(trim($_POST["pcontent"]));
	$pagename		= $db->quote($_POST["pagename"]);
	$filename		= $db->quote($_POST["filename"]);
	$keywords		= $db->quote($_POST["keywords"]);
	$linkproduct    = $db->quote($_POST['linkproduct']);
	$rss			= $db->quote($_POST["rss"]);
	$comments		= $db->quote($_POST["comments"]);
	$width			= $db->quote($_POST["width"]);
	$description	= $db->quote($_POST["description"]);
	$showurls		= $db->quote($_POST["showurls"]);
	$nofollow		= $db->quote($_POST["nofollow"]);
	// Make sure file name is unique
	$q="select count(*) as cnt from ".$prefix."pages where filename = {$filename}";
	$r=$db->get_a_line($q);
	$count=$r[cnt];
	if($count > 0)
	{
		// file name already exists
		header("Location: page-add.php?err=1&pagename=$pagename&filename=$filename&pcontent=$pcontent");
		exit();
	}
	$date_added	= $db->quote(date("Y-m-d H:i:s"));
	//$date_added = date(" Y m d G:i:s");
	// Set Data to be inserted into database
	$set = "pagename  		= {$pagename}, ";
	$set .= "pcontent		= {$pcontent}, ";
	$set .= "linkproduct	= {$linkproduct}, ";
	$set .= "date_added		= {$date_added}, ";
	$set .= "filename  		= {$filename}, ";
	$set .= "rss  			= {$rss}, ";
	$set .= "comments		= {$comments}, ";
	$set .= "width			= {$width}, ";
	$set .= "showurls  		= {$showurls}, ";
	$set .= "nofollow  		= {$nofollow}, ";
	$set .= "description	= {$description}, ";
	$set .= "type			= 'content', ";
	$set .= "keywords  		= {$keywords}";
	
	// Write to database
	$pid = $db->insert_data_id("insert into ".$prefix."pages set $set") ;
	$msg = "a";
	header("Location: pages.php?msg=$msg");
}
// Get data to populate fields on page
$q = "select * from ".$prefix."products order by pshort";
$r = $db->get_rsltset($q);
for ($i=0; $i < count($r); $i++)
{
	@extract($r[$i]);
	$pid		= $pshort;
	$linkproduct.="<option value='$pid'>$pid</option>";
}
if($err == '1')
{
	$warning = '<div class="top-message" style="color:red;"><img src="../images/crose.png" align="absmiddle"> <b>Filename already in use.</b></div>';
}
// Display page to browser
//$Content = preg_replace($Ptn,"$$1",$Content);
//echo $Content;
?>
<!-- ###################### Error Message Start ###################### -->
<?php echo $warning;?>
<!-- ###################### Error Message End ###################### -->
<!-- ###################### Content Area Start ###################### -->
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner"><p><strong>Add New Custom Content Page</strong></p>
<div class="buttons" >
<a href="pages.php#pagination" >Go Back!</a></div>
<div class="formborder"> <br /> <br />
<form name="addpage" action="page-add.php" method="post" enctype="multipart/form-data" onSubmit="return formCheck(addpage);">
<input type="hidden" name="pageid" value="<?php echo $pageid?>">
<table width="95%" align="center" border="0">
  <td colspan="3" align="left" class="tbtext"><strong>Content Page Settings</strong></td>
  </tr>
<tr>
  <td colspan="3" align="left" class="tbtext">&nbsp;</td>
</tr>
<tr>
	<td width="155" align="left" class="tbtext">
	<b>Page Name:</b>	</td>
	<td colspan="2" align="left" class="tbtext">
	<input name="pagename" type="text" class="inputbox" id="pagename" value="<?php echo $pagename?>" />
	
		<div class="tool">	
		<a href="" class="tooltip" title="Page Name is the name you want to give your page.">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
	</td>
	</tr>
<tr>
	<td width="155" align="left" class="tbtext">
	<b>File Name:</b>	</td>
	<td colspan="2" align="left" class="tbtext">
	  <input name="filename" type="text" class="inputbox" id="filename" value="<?php echo $filename?>" /> 
	  <font color="#FF0000">(No Spaces) </font>
	  <div class="tool">	
		<a href="" class="tooltip" title="File Name is the name to give the page for the system. This will be used to tell what page to display.">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
	  </td>
	</tr>
<tr>
  <td colspan="3" align="left" class="tbtext"><hr size="1" /></td>
</tr>
  	<tr>
    	<td colspan="2" align="left" class="tbtext">
		<b>Page Assignment</b>
		
		
		</td>
  	</tr>
  	<tr>
  	  <td colspan="2" align="left" class="tbtext">&nbsp;</td>
    </tr>
	<tr class=tbtext>
	<td width="155" align="left" class="tbtext"><b>Display This Page To:</b></td>
	<td width="1292" align="left"><select name="linkproduct" class="inputbox" id="linkproduct">
			<option value=0>Select Access Here</option>
			<option value="Site Root Page">-Site Root Page</option>
			<option value="Legal">-Legal</option>
			<option value="All Members In Members Area">-All Members In Members Area</option>
			<option value="All Free Product Members">-All Free Product Members</option>
			<option value="All Paid Product Members">-All Paid Product Members</option>
			<option value="All OTO Product Members">-All OTO Product Members</option>
	  <option disabled>-------------</option>
			<?php echo $linkproduct?>
		</select>
		<div class="tool">	
		<a href="" class="tooltip" title="Display This Page To chooses who will be able to access the page. This is used so you can limit page access to specific membership types or specific products.">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
		</td>
</tr>
    <tr>
  <td colspan="3" align="left"><font class=tbtext><em>(This chooses who will be able to access this page. Only members who have access to the selected product or membership level will be able to see the page.)</em></font></td>
  </tr>
    <tr>
  <td colspan="3" align="left" class="tbtext"><hr size="1" /></td>
</tr>
<tr>
  <td colspan="3" align="left" class="tbtext"><strong>Page Settings</strong></td>
  </tr>
<tr>
  <td colspan="3" align="left" class="tbtext">&nbsp;</td>
</tr>
<tr>
	<td width="155" align="left" class="tbtext">
	<b>Add To RSS Feed:</b>	</td>
	<td class="tbtext" colspan="2" align="left"><input class="inputbox" name="rss" type="radio" value="yes" /> 
	Yes
      <input class="inputbox" name="rss" type="radio" value="no" CHECKED /> 
      No
     <div class="tool">	
		<a href="" class="tooltip" title="Add To RSS Feed will enable or disable adding this content on the RSS feed for your site.">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div> 
      </td>
</tr>
<tr>
	<td width="155" align="left" class="tbtext">
	<b>Allow Comments:</b>	</td>
	<td class="tbtext" colspan="2" align="left"><input class="inputbox" name="comments" type="radio" value="yes" /> 
	Yes
      <input class="inputbox" name="comments" type="radio" value="no" CHECKED /> 
      No
      <div class="tool">	
		<a href="" class="tooltip" title="Allow Comments will enable or disable allowing vistiors to add comments to the bottom of your content page.">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
      </td>
</tr>
<tr>
	<td width="155" align="left" class="tbtext">
	<b>Show URL's:</b>	</td>
	<td class="tbtext" colspan="2" align="left"><input class="inputbox" name="showurls" type="radio" value="yes" <?php echo $showurls1?>/> 
	Yes
      <input class="inputbox" name="showurls" type="radio" value="no" CHECKED /> 
      No
      <div class="tool">	
		<a href="" class="tooltip" title="Show URL's will display the website links of your comment posters.">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
      </td>
</tr>
<tr>
	<td width="155" align="left" class="tbtext">
		<b>Follow URL's:</b>
	</td>
	<td class="tbtext" colspan="2" align="left"><input class="inputbox" name="nofollow" type="radio" value="yes"  <?php echo $nofollow1?> /> 
	Yes
      <input class="inputbox" name="nofollow" type="radio" value="no" CHECKED /> 
      No
      <div class="tool">	
		<a href="" class="tooltip" title="Follow URL's will make the website links of your comment posters no follow links. ">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
      </td>
</tr>
<tr>
  <td colspan="3" align="left" class="tbtext"><strong><em><font color="#FF0000">(The above five settings apply to Site Root pages only.) </font></em></strong></td>
</tr>
<tr>
  <td colspan="3" align="left" class="tbtext">&nbsp;</td>
</tr>
<td colspan="3" align="left" class="tbtext"><b> Description:</b><br />
    <strong>Note</strong>: This is the descriptive text used for the RSS feed  and meta description on Site Root content only. <br />
    (Maximum characters: 300) You have 
    <input class="inputbox" readonly type="text" name="countdown" size="3" value="300"> characters left.
    <div class="tool">	
		<a href="" class="tooltip" title="Description is where you add a short description of your content. This is used as the teaser description for RSS feeds. It is only used for Site Root content.">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
    </td>
  </tr>
<tr>
  <td colspan="2" align="left" class="tbtext">
  <textarea name="description" class="inputbox" cols="100" rows="8" id="description" onKeyDown="limitText(this.form.description,this.form.countdown,300);" 
onKeyUp="limitText(this.form.description,this.form.countdown,300);"><?php echo $description?></textarea>
 </td>
<tr>
	<td width="155" align="left" class="tbtext">
	<b>Keywords:</b>	</td>
	<td colspan="2" align="left" class="tbtext">
	<input name="keywords" type="text" class="inputbox" id="keywords" value="<?php echo $keywords?>" size="80" />
	<div class="tool">	
		<a href="" class="tooltip" title="Keywords is where you can enter custom keywords that will be added to the html template for your custom content page. ">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
	</td>
	</tr>
<tr>
  <td colspan="3" align="left" class="tbtext"><em>(Keywords are used by Site Root content pages only. This helps make your pages SEO friendly.)</em></td>
</tr>
<tr>
  <td colspan="3" align="left" class="tbtext">&nbsp;</td>
</tr>
<tr>
  <td colspan="3" align="left" class="tbtext"><b>Content Page:<br />
      <strong>Note:</strong></b> This page uses your default site templates. Add content or content html only.</td>
  </tr>
  
<tr>
  <td colspan="2" align="left" class="tbtext">
  <div class="tool">	
		<a href="" class="tooltip" title="Content Page is where you put your page content or the html for your page content. This page uses your site's templates, so don't 
	include headers or footers or templates when designing your custom content pages. Only include your actual page content or the html 
	that would normally go between the  tags.">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div></td>
</tr>
<tr>
  <td colspan="3" align="left" class="tbtext">  
   	<textarea name="pcontent" cols="100" rows="8" class="inputbox" id="pcontent"><?php echo $pcontent?></textarea>
	<script>
	
	var oEdit1 = new InnovaEditor("oEdit1");
    oEdit1.width = 750;
    oEdit1.height = 400;
    
    oEdit1.groups = [
    ["grpEdit1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
    ["grpEdit2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
    ["grpEdit3", "", ["TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog", "ImageDialog", "YoutubeDialog"]],
    ["grpEdit4", "", ["CharsDialog", "Line", "BRK",  "CustomTag","HTML5Video"]], ["grpEdit5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo", "FullScreen"]]
    ];
 
    if (oEdit1.fileBrowser != "") {
        oEdit1.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,650,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    } else { /* If file browser not used, reduce the HTML5 Video dialog height */
        oEdit1.arrCustomButtons = [["HTML5Video", "modalDialog('/admin/Editor/scripts/common/webvideo.htm',690,330,'HTML5 Video');", "HTML5 Video", "btnVideo.gif"]];
    }
	
    /*Enable Custom File Browser */
	
	oEdit1.fileBrowser = "/admin/Editor/assetmanager/asset.php";
    /*Define "CustomTag" dropdown */
    oEdit1.arrCustomTag=[["Contact Us Form","{\{contact_form\}}"]];//Define custom tag selection
    /*Apply stylesheet for the editing content*/
     oEdit1.css = "/admin/Editor/scripts/bootstrap/css/bootstrap.min.css";	
    /*Render the editor*/
   
	oEdit1.REPLACE("pcontent");
	
	</script>
	
	
	</td>
</tr>
<tr>
  <td colspan="3" align="left" class="tbtext">&nbsp;</td>
</tr>
<tr>
	<td colspan="3" align="left">
	
	    <input type="submit" name="submit" value="Add Content Page" class="inputbox">	</td>
</tr>
<tr>
	<td colspan="3" align="center">	</td>
</tr>
</table>
</form>
<br /><br />
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>
<!-- ###################### Content Area Close ###################### -->
<?php 
include_once("footer.php");
?>