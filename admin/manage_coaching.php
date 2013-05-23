<?php
include_once("session.php");
include_once("header.php");
$pshort=$_GET['pshort'];
$Title = "Manage Coaching";
$sql_products = "select * from ".$prefix."products where pshort = '$pshort'";
$rs_products = $db->get_a_line($sql_products);
$product_name = $rs_products[product_name];
$productid = $rs_products[id];

########## pagination ###########
$links = "manage_coaching.php";
$targetpage="manage_coaching.php";
$sql_message = "select count(*) as total from ".$prefix."member_products where product_id='$productid'";
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
$pager=$common->pagiation_simple('manage_coaching.php?pshort='.$pshort,$limit,$rs_message['total'],$pageno,$start,'');		

########## pagination ###########
$ChangeColor = 1;
$ToReplace = "";
$result = mysql_query("select * from ".$prefix."member_products where product_id='$productid' order by id asc limit $start, $limit");


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
		padding-left: 10px;
	}
</style>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner"><p><strong><?php echo $Title ?></strong></p>
<br>
<a id="pagination"></a> 
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td width="50%" valign="middle">
	<span style="float:left;">Select Number of rows per page:</span>
	<form name="limitForm" action="<?php echo $targetpage;?>" method="GET" style="float:left;">
		<input type="hidden" name="pshort" value="<?php echo $pshort;?>">
	    <select name="limit" onchange="document.limitForm.submit()" style="width:100px;">
	 	<option value="10" <?php echo isSelected(10,$limit)?>>10</option>
	 	<option value="25" <?php echo isSelected(25,$limit)?>>25</option> 
	    <option value="50" <?php echo isSelected(50,$limit)?>>50</option>
	    <option value="100" <?php echo isSelected(100,$limit)?>>100</option>
	  </select>
	 </form>
	 </td>
	 <td align="right">
		 <div class="buttons">
         	<a style="cursor:pointer;" href="paid_products.php">Go Back</a>
		 </div>
	 </td>
	 </tr>	
</table>
 
 <div id="grid"> 
<table class="notsortable" width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
  <thead>   
  <tr>
  	<th>MEMBER NAME</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
    <th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
    <th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
    <th>MESSAGES</th>
  </tr>
  </thead>
 <?php
$i=0;
 
while($row = mysql_fetch_array($result)){
 if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
 	
 	$q = "select * from ".$prefix."members where id='".$row['member_id']."'";
	$rt = $db->get_a_line($q);
	$firstname = $rt['firstname'];
	$lastname = $rt['lastname'];
	$member_name = $firstname." ".$lastname;
	$member_id = $row['member_id'];
	
	$q = "select count(*) as cnt from ".$prefix."member_messages where mid = '".$row['member_id']."' && product= '$pshort' && checked='0'";
	$r22 = $db->get_a_line($q);		
	$count1 = $r22[cnt];

		$list_url = '<a href='.$http_path.'recommends/'.$row['nickname'].' target = _blank>'.$http_path.'recommends/'.$row['nickname'].'</a>';
 	?>
  <tr class="<?php echo $class?>">
  	<td><?php echo $member_name;?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
    <td valign="middle">
    <?php
		$sql_message = "select count(*) as total from ".$prefix."member_messages where mid='".$row['member_id']."' && product='$pshort'";
		$rs_message = $db->get_a_line($sql_message);
		$total_message = $rs_message[total];
    
	 	if($count1 == '0')
		{
			echo '<a href=member_messages.php?mid='.$member_id.'&product='.$pshort.'>Messages</a> ('. $total_message . ')';
		}
		elseif($count1 > '0')
		{
			echo '<a href=member_messages.php?mid='.$member_id.'&product='.$pshort.'>Messages</a> (' . $total_message . ')<span class="new">'.$count1.' New</span> ';
		}
    ?>
    </td>
  </tr>
  
<?php

 $i++;}
 ?>
</table>
<div class="pages">
	<div class="pager"><?php echo $pager;?>&nbsp;</div></div>
	<div><a href="#top" style="text-align:center;">Move to top</a></div>
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>
<?php 
include_once("footer.php");
?>