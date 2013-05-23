<?php
include_once("session.php");
include_once("header.php");
$pshort=$_GET['product'];
$Title = "Manage Messages";
$GetFile = file("../html/admin/member_messages.html");
$Content = join("",$GetFile);
$mid= $_GET['mid'];
$id = $_GET['id'];


if($act == "d")
{
	// Delete message
	$sel_query = "Select * from ".$prefix."member_messages Where id = '".$id."'";
	$msg_records = $db->get_a_line($sel_query);
	$file_name = $msg_records['upload_file'];
	if($file_name){ unlink($file_name); }
	$db->insert("delete from ".$prefix."member_messages where id ='$id'");
	$msg = "d";
}

if($act == "c")
{
	// Check current visibility
	$q = "select * from ".$prefix."member_messages where id = '$id'";
	$rr1 = $db->get_a_line($q);
	$vis = $rr1['vis'];

	if($vis == '1')
	{
		$vis = '0';
	}
	elseif($vis == '0')
	{
		$vis = '1';
	}
	// Change visibility
	$db->insert("update ".$prefix."member_messages set vis='$vis' where id = '$id'");
	$msg = "c";
}

if($msg == "a")
{
	$Message = "<div class='success'><img src='/images/tick.png' border='0'> Message Has Been Successfully Added</div>";
}
else if($msg == "e")
{
	$Message = "<div class='success'><img src='/images/tick.png' border='0'> Message Has Been Successfully Edited</div>";
}
else if($msg == "d")
{
	$Message = "<div class='success'><img src='/images/tick.png' border='0'> Message Has Been Successfully Deleted</div>";
}
else if($msg == "c")
{
	$Message = "<div class='success'><img src='/images/tick.png' border='0'> Message Visibility Has Been Successfully Changed</div>";
}

$sql = "update ".$prefix."member_messages set checked='1' where mid='$mid' && product='$pshort'";
$db->insert($sql);

$sql_products = "select * from ".$prefix."products where pshort = '$pshort'";
$rs_products = $db->get_a_line($sql_products);
$product_name = $rs_products[product_name];
$productid = $rs_products[id];

########## pagination ###########
$links = "member_messages.php?product=$pshort&mid=$mid&";
$targetpage="member_messages.php";

$sql_message = "select count(*) as total from ".$prefix."member_messages where mid='$mid' && product='$pshort'";
$rs_message = $db->get_a_line($sql_message);
$total = $rs_message[total];
if(isset ($_GET["limit"])){
	$limit = $_GET["limit"];
}else{
	$limit = 10; 								//how many items to show per page
}
if($pageno)
$start = ($pageno - 1) * $limit; 			//first item to display on this page
else
{
$start = 0;
$pageno = 0;
}	
$pager=$common->pagiation_simple('member_messages.php?mid='.$mid.'&product='.$pshort,$limit,$rs_message['total'],$pageno,$start,'');		
########## pagination ###########


$ChangeColor = 1;
$ToReplace = "";


$result = mysql_query("select * from ".$prefix."member_messages where mid='$mid' && product='$pshort' order by id desc limit $start, $limit");

function getDirection($currentField, $dir, $fieldName){

		if($fieldName == $currentField && $dir== 'DESC')
		{
			return "ASC";
				
		}else if($fieldName == $currentField && $dir== 'ASC'){
				
			return "DESC";
		}else {
		  
			return "ASC";
		}
	}

function getCssClass($currentField, $dir, $fieldName){

		if($fieldName == $currentField && $dir== 'DESC')
		{
			return "sortDesc";

		}else if($fieldName == $currentField && $dir== 'ASC'){

			return "sortAsc";
		}else {

			return "";

		}
	}

function isSelected($currentValue, $limit){
		if($currentValue == $limit){

			return 'selected="selected"';
		}
	}
?>
<script src="../common/newLayout/jquery/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<style type="text/css">
	#grid table tr th{
		text-align: left;
	}
	
	#grid table tr td{
		text-align: left;
		padding-left: 15px;
	}
</style>
<?php echo $Message; ?>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong><?php echo $Title ?></strong></p>
<br>
<a id="pagination"></a> 
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td width="50%" valign="middle">
	<span style="float:left;">Select Number of rows per page:</span>
	<form name="limitForm" action="<?php echo $targetpage;?>#pagination" method="GET" style="float:left;">
	  <select name="limit" onchange="document.limitForm.submit()" style="width:100px;">
	 	
	 	<option value="10" <?php echo isSelected(10,$limit)?>>10</option>
	 	<option value="25" <?php echo isSelected(25,$limit)?>>25</option> 
	    <option value="50" <?php echo isSelected(50,$limit)?>>50</option>
	    <option value="100" <?php echo isSelected(100,$limit)?>>100</option>
	    <input type="hidden" name="mid" value="<?php echo $mid;?>">
	 	<input type="hidden" name="product" value="<?php echo $pshort;?>">
	  </select>
	 </form>
	 </td>
	 <td align="right">
	 <div class="buttons">
		 	<a style="cursor:pointer;" href="manage_coaching.php?pshort=<?php echo $pshort;?>">Go Back</a>
	 </div>
	 <div class="buttons">
		 	<a href="addmessage.php?pid=<?php echo $pshort;?>&mid=<?php echo $mid;?>">Add Message</a>
	 </div>
	 
	 </td>
	 </tr>	
</table>
<div id="grid"> 
<table class="notsortable" width="95%" border="0" align="left" cellpadding="2" cellspacing="0" >
  <thead>  
  <tr>
  	<th colspan="2">
  	MESSAGES</th>
    <th align="left">SHOW</th>
    <th>EDIT</th>
    <th>DELETE</th>
  </tr>
  </thead>
 <?php
	$i=0;
 
while($row = mysql_fetch_array($result)){
	
 $visibility = $row['vis'];
 $admin = $row['admin'];
 
 	
 if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
 	
	$message = stripslashes($row['message']);
	$message= str_replace("\r\n", "<br>", $message);
	$message=$common->mywordwrap($message, 170);
	
	$date = $row['date_added'];
	$date = date('F j, Y', strtotime($date));	
	
 	$q = "select * from ".$prefix."members where id='".$row['mid']."'";
	$rt = $db->get_a_line($q);
	$firstname = $rt['firstname'];
	$lastname = $rt['lastname'];
	$member_name = $firstname." ".$lastname;
	$member_id = $row['mid'];
	
	if($visibility == '0')
	{
		$confirm_image = "../images/admin/published.png";
	}
	if($visibility == '1')
	{
		$confirm_image = "../images/admin/unpublished.png";
	}
	if ($admin == '1')
	{
		$name = "Administrator";
	}else
	{
		$name = $firstname." ".$lastname;
	}
	
	$q = "select count(*) as cnt from ".$prefix."member_messages where mid = '".$row['member_id']."' && product= '$pshort' && checked='0'";
	$r22 = $db->get_a_line($q);		
	$count1 = $r22[cnt];

	$list_url = '<a href='.$http_path.'recommends/'.$row['nickname'].' target = _blank>'.$http_path.'recommends/'.$row['nickname'].'</a>';
	
	if($row['upload_file']){
		$filename = str_replace("../document/", "", $row['upload_file']);
		$download_link = '/download/'.$filename;
		$view_file = "<a href='".$download_link."'><img src='/images/admin/download.png' title='Download File' alt='Download File' border='0' align='absmiddle'></a> Download File";		
	}else{
		$view_file = '';
	}
 	?>
  <tr class="<?php echo $class?>">
  	<td valign="left" colspan="2">
  		
		<?php
  			echo "<span class='msg_heading'>".$date . " - Posted by: <strong>". $name . "</strong></span>&nbsp; ".$view_file."<br /><br />";	
  			echo $message;
  		?>
  	</td>
    <td style="text-align:center" >
       	<a href='member_messages.php?act=c&id=<?php echo $row['id'];?>&mid=<?php echo $mid;?>&product=<?php echo $pshort;?>' onclick="return confirm('Are you sure you want to change the visibility of this message?');">
    		<img src="<?php echo $confirm_image;?>" border="0">
    	</a>	
    </td>
    <td style="text-align:center">
    	<a href='edit_message.php?id=<?php echo $row['id'];?>&mid=<?php echo $mid;?>&product=<?php echo $pshort;?>'>
    		<img src="/images/editIcon.png" border="0">
    	</a>
    </td>
    <td style="text-align:center">
    	<a href='member_messages.php?act=d&id=<?php echo $row['id'];?>&mid=<?php echo $mid;?>&product=<?php echo $pshort;?>' onclick="return confirm('Are you sure you want to delete this message?');">
    		<img src="/images/crose.png" border="0">
    	</a>	
    </td>
  </tr>
 <?php
 $i++;
}
 ?>
</table>
<div class="pages">
	<div class="pager">
		<?php echo $pager; ?>&nbsp;
	</div>
</div>
<div><a href="#top" style="text-align:center;">Move to top</a>
</div>
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>
<?php 
include_once("footer.php");
?>