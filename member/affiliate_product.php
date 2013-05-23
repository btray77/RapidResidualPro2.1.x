<?php
include_once("../../session.php");
include_once("../../header.php");
$Title = "Purchase Product Reporting";


$targetpage = "product.php?mid=$mid"; 	//your file name  (the name of this file)

################## PAGINATION ########################
$fieldNamesArray = array(
	"field1" => "id",
	"field2" => "email",
	"field3" => "username",
	//"field4" => "discount"
	);

	
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;

	/*
	 First get total number of rows in data table.
	 If you have a WHERE clause in your query, make sure you mirror it here.
	 */
	$sql = "select count(id) as total from ".$prefix."orders where randomstring = '$mid'";
	$row_total= $db->get_a_line($sql);
	$total_pages = $row_total['total'];
	$total_pages=1;
	/* Setup vars for query. */
	
	if(isset ($_GET["limit"])){
		$limit = $_GET["limit"];
	}else{
		$limit = 20; 								//how many items to show per page
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

	/* Get data.  ,".$prefix."products p  p.id = o.item_number  ,p.commission */
	

	$sql = "
		SELECT od.item_number as id,od.item_name as product_name,od.payment_amount,p.commission,
		(SELECT COUNT(id) FROM ".$prefix."click_stats   WHERE referrer = '$mid' and product=item_name) AS click
		
		FROM ".$prefix."orders od ,".$prefix."products p
		
		WHERE od.referrer ='$mid' AND p.id = od.item_number
		GROUP BY item_name    
		LIMIT $start,$limit";
	$result = $db->get_rsltset($sql);

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
		$pagination.= "<a href=\"$targetpage&page=$prev&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">&lt;&lt; previous</a>";
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
				$pagination.= "<a href=\"$targetpage&page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
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
					$pagination.= "<a href=\"$targetpage&page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&page=$lpm1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&page=$lastpage&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lastpage</a>";
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage&page=1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">1</a>";
				$pagination.= "<a href=\"$targetpage&page=2&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
					else
					$pagination.= "<a href=\"$targetpage&page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&page=$lpm1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&page=$lastpage&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$lastpage</a>";
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage&page=1&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">1</a>";
				$pagination.= "<a href=\"$targetpage&page=2&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
					else
					$pagination.= "<a href=\"$targetpage&page=$counter&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">$counter</a>";
				}
			}
		}

		//next button
		if ($page < $counter - 1)
		$pagination.= "<a href=\"$targetpage&page=$next&amp;limit=$limit&amp;col=$fieldName&amp;dir=$dir#pagination\">Next &gt;&gt;</a>";
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


?>
<link rel="stylesheet" href="../common/newLayout/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
<script src="../common/newLayout/jquery/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner"><p><strong><?php echo $Title ?></strong></p>
<div align="right">[<a href="index.php">BACK</a>]</div>
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
	  <input type="hidden" name="filter" value="all" >
	  <input type="hidden" value="<?php echo $mid;?>" name="mid"> 
	 </form>
	 </td>
	 <td>
	 	
	</td>
	 </tr>
	
	 </table>
	<div class="filter" style="margin-bottom:10px;">
	<!--  <form name="filterForm" action="<?php echo  $targetpage?>#pagination" method="GET" style="float:left;">
	<fieldset>
	 		<legend>Filter</legend>
	 		<input type="radio" name="filter" value="all" <?php if($filter=='all') echo 'checked="checked"';?> onclick="document.filterForm.submit();"> All
	 		<input type="radio" name="filter" value="active" <?php if($filter=='active') echo 'checked="checked"';?> onclick="document.filterForm.submit();"> Active
	 		<input type="radio" name="filter" value="block" <?php if($filter=='block') echo 'checked="checked"';?> onclick="document.filterForm.submit();"> Block
	 		<input type="radio" name="filter" value="referer" <?php if($filter=='referer') echo 'checked="checked"';?> onclick="document.filterForm.submit();"> Has referer
	 		<input type="radio" name="filter" value="free" <?php if($filter=='free') echo 'checked="checked"';?> onclick="document.filterForm.submit();"> Member
	 		<input type="radio" name="filter" value="affiliate" <?php if($filter=='affiliate') echo 'checked="checked"';?> onclick="document.filterForm.submit();"> Affiliate member
	 		<input type="radio" name="filter" value="jv" <?php if($filter=='jv') echo 'checked="checked"';?> onclick="document.filterForm.submit();"> JV Partners 
	 	</fieldset>
	 	<input type="hidden" value="<?php echo $limit;?>" name="limit">
	 	<input type="hidden" value="<?php echo $page;?>" name="page"> 
	</form>--> 	
	</div>
  
 
 <div id="grid-reports"> 
<table class="notsortable" width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
  
  <tr>
  	
  	<th width="4">Id</th>
  	<th align="left">Name</th>
    <th align="left">Price</th>
    <th align="left">Commission</th> 
    <th align="left">Visits</th>
    <th align="left">Conversion</th>
    <th align="left">Paypal</th>
    <th align="left">AlertPay</th>
    <th align="left">Click Bank</th>
    
    
  </tr>
  
 <?php
$i=0;
if(count($total_pages) > 0)
{
 foreach ($result as $row){
	if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
	 ?>
  <tr class="<?php echo $class?>">
  	<td valign="middle"><?php echo $row['id']?></td>
    <td valign="middle" style="text-align:left;"><?php echo $row['product_name'];?></td>
    <td valign="middle" style="text-align:center"><?php echo $row['payment_amount']?></td>
    <td valign="middle" style="text-align:center"><?php if(!empty($row['commission'])) echo $row['commission'].'%'?></td>
    <td valign="middle" style="text-align:center"><?php echo $row['click']?></td>
    <td valign="middle" style="text-align:center"><?php echo 'soon' //$row['conversion']?></td>
    <td valign="middle" style="text-align:center"><?php echo 'soon'//$row['paypal']?></td>
  	<td align="left" style="text-align:center"><?php echo 'soon'//$row['alertPay']?></td>
    <td align="left" style="text-align:center"><?php echo 'soon'//$row['clickBank']?></td>
  </tr>
 <?php
 $i++; }
 		if($start==0 && $totalrec > 0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}
} 
else {
	echo "<tr><td colspan='7' style='text-align:center'>Sorry no record forund</td></tr>";
}	
 ?>
</table>
<div class="pages">
		<div class="totalpages">Total: <?php if($startrec >0 ) echo $startrec; else echo "0"; ?> - 
									   <?php if($totalrec >0 ) echo $totalrec; else echo "0"; ?> of <?php echo $total_pages;?></div>
		<div class="pager"><?php echo $pagination?>&nbsp;</div></div>
		<div><a href="#top" style="text-align:center;">Move to top</a>
</div>
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>
<?php function total_purchase_products($db,$prefix,$id)
{
	$sql = "select count(id) as total from ".$prefix."member_products where member_id = $id";
	$row_total= $db->get_a_line($sql);
	return $total_pages = $row_total['total'];
		
}

?>
<?php include_once("../../footer.php");?>
