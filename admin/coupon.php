<?php
include "session.php";
include "header.php";

$Title = "Coupon Code Management";
$targetpage = "coupon.php"; 	//your file name  (the name of this file)
$tbl_name=$prefix."coupon_codes";		//your table name

############# Actions ##################
switch($act)
{
	case 'd':
		if($obj_pri->canDelete($pageurl))
		{
		$msg=delete_content($pageid,$db,$prefix);
		header("location:$targetpage?msg=$msg");
		}
		else
		{
		 $msg=archive_content($pageid,0,$db,$prefix);
		 header("location:$targetpage?msg=$msg");
		}		
	break;
	case 'a':
		$msg=archive_content($pageid,$state,$db,$prefix);
		header("location:$targetpage?msg=$msg");
	break;	
}
############# Messages ##################
switch($msg)
{
	case 'add':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Coupon Code is Successfully Added</div>';
	break;
	case 'edit':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Coupon Code is Successfully Edited</div>';
	break;	
	case 'd':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Coupon Code is Successfully Deleted</div>';
	break;
	case 'ar':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Coupon Code is Successfully Unarchived!</div>';
	break;
	case 'un':
		$Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Coupon Code is Successfully Archived!</div>';
	break;
}
############# Operation ##################

function delete_content($id,$db,$prefix)
{
	
	$db->insert("delete from ".$prefix."coupon_codes where id ='$id'");
	
	return $msg='d'; 
		
}

function archive_content($id,$state,$db,$prefix)
{

	$db->insert("update ".$prefix."coupon_codes set publish='$state' where id ='$id'");
	if($state==1)
		return $msg='ar';
	else 
		return $msg='un';
}


########## pagination ###########

$fieldNamesArray = array(
	"field1" => "id",
	"field2" => "couponcode",
	"field3" => "prod",
	"field4" => "discount"
	);

	
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;

	/*
	 First get total number of rows in data table.
	 If you have a WHERE clause in your query, make sure you mirror it here.
	 */
	
	$query = "select count(*) as cnt from $tbl_name";
	$rs_total=mysql_query($query);
	$total_pages = mysql_fetch_array($rs_total);
	$total_pages = $total_pages['cnt'];

	/* Setup vars for query. */
	
	if(isset ($_GET["limit"])){
		$limit = $_GET["limit"];
	}else{
		$limit = 10; 								//how many items to show per page
	}

	$page = $_GET['page'];

	if(isset($_GET['col']) && isset($_GET['dir'])){
		$fieldName = $_GET['col'];
		$field = $fieldNamesArray[$_GET['col']];
		$dir = $_GET['dir'];

	}else{
		$fieldName = 'field1';
		$field = "id";
		$dir = "ASC";
	}

	if($page)
	$start = ($page - 1) * $limit; 			//first item to display on this page
	else
	$start = 0;								//if no page var is given, set start to 0

	/* Get data. */

	$sql = "select * from $tbl_name order by $field $dir  limit $start,$limit";
	$result = mysql_query($sql);

	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1

	/*
		Now we apply our rules and draw the pagination object.
		We're actually saving the code to a variable in case we want to draw it more than once.
		*/
	$pagination = "";
	if($lastpage > 1)
	{
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1)
		$pagination.= "<a href=\"$targetpage?page=$prev&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">&lt;&lt; previous</a>";
		else
		$pagination.= "<span class=\"disabled\">&lt;&lt; previous</span>";

		//pages
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
				$pagination.= "<span class=\"current\">$counter</span>";
				else
				$pagination.= "<a href=\"$targetpage?page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
					else
					$pagination.= "<a href=\"$targetpage?page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lastpage</a>";
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage?page=1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
					else
					$pagination.= "<a href=\"$targetpage?page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lastpage</a>";
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage?page=1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
					else
					$pagination.= "<a href=\"$targetpage?page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
				}
			}
		}

		//next button
		if ($page < $counter - 1)
		$pagination.= "<a href=\"$targetpage?page=$next&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">Next &gt;&gt;</a>";
		else
		$pagination.= "<span class=\"disabled\">Next &gt;&gt;</span>";
		$pagination.= "</div>\n";
	}


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

########## pagination ###########


?>
<?php echo $Message ?>
<link rel="stylesheet" href="../common/newLayout/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
<script src="../common/newLayout/jquery/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner"><p><strong><?php echo $Title ?></strong></p>


<br>



<a id="pagination"></a> 
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td width="50%" valign="middle">
	<span style="float:left;">Select Number of rows per page:</span>
	<form name="limitForm" action="<?php echo  $targetpage?>#pagination" method="GET" style="float:left;">
	  <select name="limit" onchange="document.limitForm.submit()" style="width:100px;">
	 	<option value="10" <?php echo isSelected(10,$limit)?>>10</option>
	 	<option value="25" <?php echo isSelected(25,$limit)?>>25</option> 
	    <option value="50" <?php echo isSelected(50,$limit)?>>50</option>
	    <option value="100" <?php echo isSelected(100,$limit)?>>100</option>
	  </select>
	 </form>
	 </td>
	 <td>
		 <?php if($obj_pri->canAdd($pageurl)){ ?>
		 	<div class="buttons">
		 		<a href="addcoupon.php">Add New Coupon Code</a>
		 	</div>
		 <?php }?>
	 	<td>
	 </tr>
	
	 </table>
	
  
 
 <div id="grid"> 
<table class="notsortable" width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
  <thead>   
  <tr>
  	<th>
  	<a href="<?php echo $targetpage?>?col=field1&amp;dir=<?php echo getDirection('field1',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Id</a><span class="<?php echo getCssClass('field1',$dir,$fieldName);?>">&nbsp;</span></th>
    <th width="200" align="left">
    <a href="<?php echo $targetpage?>?col=field2&amp;dir=<?php echo getDirection('field2',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Coupon Code</a><span class="<?php echo getCssClass('field2',$dir,$fieldName);?>">&nbsp;</span>
	</th>
    </th>
    <th width="200" align="left">
    <a href="<?php echo $targetpage?>?col=field3&amp;dir=<?php echo getDirection('field3',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Product</a><span class="<?php echo getCssClass('field3',$dir,$fieldName);?>">&nbsp;</span>
    </th>
    <th width="200" align="left">Expiry Date
    </th>
    <th align="left">
     <a href="<?php echo $targetpage?>?col=field4&amp;dir=<?php echo getDirection('field4',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Discount</a><span class="<?php echo getCssClass('field4',$dir,$fieldName);?>">&nbsp;</span>
    </th>
    <th align="left">Link Example</th>
    <th >Edit/Delete</th>
  </tr>
  </thead>
 <?php
$i=0;
 while($row=mysql_fetch_array($result)){
 if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
			
			
		if($row["date_added"]){
			$dateAdded= date("M d, Y g:i a",strtotime($row["date_added"]));
		}else {
			$dateAdded= "-";
		}
	if($obj_pri->canDelete($pageurl))
		{
			if($row['publish']==0){
			$delete='<a href="'.$targetpage.'?act=d&pageid='.$row['id'].'">
			<img src="../images/cross-gray.png" alt="editImage" title="Click to delete this coupon code" Onclick="return confirm('."'".'Are you sure! you want to delete this page?'."'".')">
			</a>';
			}
			else
			{
			$delete='<a href="'.$targetpage.'?act=d&pageid='.$row['id'].'">
			<img src="../images/crose.png" alt="editImage" title="Click to delete this coupon code" Onclick="return confirm('."'".'Are you sure! you want to delete this page?'."'".')">
			</a>';
			}
		}
		else{
			
			if($row['publish']==0){		
				$delete='<a href="'.$targetpage.'?act=a&pageid='.$row['id'].'&state=1">
					<img src="../images/cross-gray.png" alt="editImage" title="Click to unarchive this coupon code">
			</a>';
				
			}
			else{
				$delete='<a href='.$targetpage.'?act=a&pageid='.$row['id'].'&state=0">
			<img src="../images/crose.png" alt="editImage" title="Click to archive this coupon code">
			</a>';}
				
			}
 	
 	?>
  <tr class="<?php echo $class?>">
  	<td valign="middle"><?php echo $row['id']?></td>
    <td valign="middle"><?php echo $row['couponcode']?></td>
    <td valign="middle"><?php echo $row['prod']?></td>
     <td valign="middle"><?php echo $row['expire_date']?></td>
    <td valign="middle">$ <?php echo $row['discount']?> </td>
    <td valign="middle">
	    <a href="couponlink.php?coupon=<?php echo $row['couponcode']?>&product_name=<?php echo $row['prod']?>&iframe=true&width=750px&height=290px" rel="prettyPhoto">
	<img src="../images/yellowlink.png" alt="linkImage" title="Click to go to the Coupon Code">
	</a>
    
    </td>
    <td valign="middle"> 
    <div style="float:left;padding-left:16px;">
    	<a href="editcoupon.php?prodid=<?php echo $row['id']?>"> <img src="../images/editIcon.png" alt="editImage" title="Click to edit this coupon code" > </a>
    </div>
   <div style=" float:right;padding-left:10px;"> <?php echo $delete?></div>
    
    </td>
  </tr>
  
<?php
 if($start==0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}
 $i++;} ?>
</table>
<div class="pages">
		<div class="totalpages">Total: <?php echo $startrec; ?> - <?php echo $totalrec; ?> of  <?php echo $total_pages;?></div>
		<div class="pager"><?php echo $pagination?>&nbsp;</div></div>
		<div><a href="#top" style="text-align:center;">Move to top</a>
</div>
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>
<script type="text/javascript" charset="utf-8">
		$(document).ready(function(){
			$("a[rel^='prettyPhoto']").prettyPhoto();
		});
</script>
<?php 
include "footer.php";
?>