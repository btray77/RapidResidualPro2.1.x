<?php
include_once("../session.php");
include_once("../header.php");
$Title = "Email Log";

$tbl_name=$prefix."email_log";		//your table name

$targetpage = "email_log.php?mid=$mid"; 	//your file name  (the name of this file)

################## PAGINATION ########################



	// How many adjacent pages should be shown on each side?
	$adjacents = 3;

	/*
	 First get total number of rows in data table.
	 If you have a WHERE clause in your query, make sure you mirror it here.
	 */
	
	$sql = "select count(*) as total from $tbl_name where member_id =$mid;";
	$row_total= $db->get_a_line($sql);
	$total_pages = $row_total['total'];

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
		$dir = "DESC";
	}

	if($page)
	$start = ($page - 1) * $limit; 			//first item to display on this page
	else
	$start = 0;								//if no page var is given, set start to 0
	/* Get data. */
	$sql = " SELECT * from $tbl_name where member_id =$mid  order by created_date  limit $start,$limit";
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


	

	function isSelected($currentValue, $limit){
		if($currentValue == $limit){

			return 'selected="selected"';
		}

	}


	?>
<link
	rel="stylesheet" href="../common/newLayout/prettyPhoto.css"
	type="text/css" media="screen" charset="utf-8" />
<script
	src="../common/newLayout/jquery/jquery.prettyPhoto.js"
	type="text/javascript" charset="utf-8"></script>
<link
	href="css/theme.css" rel="stylesheet" type="text/css" />

<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong><?php echo $Title ?></strong></p>
<div class="buttons">
<a href="index.php">Go Back</a>
</div> <a id="pagination"></a>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50%" valign="middle"><span style="float: left;">Select
		Number of rows per page:</span>

		<form name="limitForm" action="<?php echo  $targetpage?>#pagination"
			method="GET" style="float: left;"><select name="limit"
			onchange="document.limitForm.submit()" style="width: 100px;">
			<option value="10" <?php echo isSelected(10,$limit)?>>10</option>
			<option value="25" <?php echo isSelected(25,$limit)?>>25</option>
			<option value="50" <?php echo isSelected(50,$limit)?>>50</option>
			<option value="100" <?php echo isSelected(100,$limit)?>>100</option>
		</select> <input type="hidden" name="mid" value="<?php echo $mid?>"></form>
		</td>
		<td></td>
	</tr>

</table>



<div id="grid" >

	<?php
	$i=0;
	if($total_pages > 0)
	{
		foreach ($result as $row){
			if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
			?>
		<div class="<?php echo $class?>"style="padding:10px;">
		<h3><?php echo stripslashes($row['subject'])?></h3>
		<small><?php echo $row['created_date']?></small>
		<p><?php echo  nl2br(stripslashes($row['message']))?></p>
		
		</div>	
	    

	</tr>

	<?php

	$i++; }
	if($start==0 && $totalrec > 0){$totalrec=$startrec+$i; $startrec=$start+1;}
	else {$startrec=$start;$totalrec=$startrec+$i;}

	} else {
		echo "<tr><td colspan='9' style='text-align:center'>Sorry no record forund</td></tr>";

	}
	?>

<div class="pages">
<div class="totalpages">Total: <?php if($startrec >0 ) echo $startrec; else echo "0"; ?>
- <?php if($totalrec >0 ) echo $totalrec; else echo "0"; ?> of <?php echo $total_pages;?></div>
<div class="pager"><?php echo $pagination?>&nbsp;</div>
</div>
<div><a href="#top" style="text-align: center;">Move to top</a></div>
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




	include_once("../footer.php");?>
