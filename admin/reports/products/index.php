<?php
include_once("../../session.php");
include_once("../../header.php");
$Title = "Products Reporting";

$tbl_name=$prefix."products";		//your table name


$targetpage = "index.php"; 	//your file name  (the name of this file)

################## PAGINATION ########################
$fieldNamesArray = array(
	"field1" => "id",
	"field2" => "product_name",
	"field3" => "price",
	"field4" => "buyer",
	"field5" => "orders",
	"field6" => "refund",
	"field7" => "refer",
	"field8" => "canceled",
	"field9" => "clicks",
	"field10" => "views",
	"field11" => "epc",
	"field11" => "sales",
      );

	
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;

	/*
	 First get total number of rows in data table.
	 If you have a WHERE clause in your query, make sure you mirror it here.
	 */
	
	$sql = "select count(*) as total from $tbl_name";
	$row_total= $db->get_a_line($sql);
	$total_pages = $row_total['total'];

	/* Setup vars for query. */
	
	if(isset ($_GET["limit"])){
		$limit = $_GET["limit"];
	}else{
		$limit = 25; 								//how many items to show per page
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
	/* Get data. 
	 //(SELECT DATEDIFF( NOW(),FROM_UNIXTIME(m.date_added))  FROM rrp_member_products m ,$tbl_name p WHERE m.product_id = p.id) AS DAYS
	 //(SELECT COUNT(*) FROM rrp_member_products m WHERE m.product_id = p.id) AS total,
	 * */
        
        $WHEREX=" where o.item_number=m.product_id 
                AND o.txnid= m.txn_id
                AND o.payment_status='Completed'
                AND o.txnid <> 'ALLOCATED'
                AND o.txnid <> 'FREE'
                ";

              $sql = "SELECT p.id,p.pshort,p.product_name,p.prodtype, 
                (SELECT COUNT(id) FROM ".$prefix."member_products m  WHERE m.product_id = p.id) AS buyer, 
                (SELECT COUNT(id) FROM ".$prefix."member_products m  WHERE m.product_id = p.id and refunded=1 ) AS refund,
                (SELECT COUNT(id) FROM ".$prefix."orders m  WHERE m.item_number = p.id ) AS orders,
                (SELECT COUNT(id) FROM ".$prefix."orders m  WHERE m.item_number = p.id and payment_status ='Cancelled' ) AS canceled,
                (SELECT COUNT(DISTINCT(referrer)) FROM ".$prefix."orders m  WHERE m.item_number = p.id and m.referrer <> '' and m.referrer <> 'None' ) AS refer,
				(SELECT @views:= count(id) from ".$prefix."click_stats where product = p.pshort) as views,     
                (SELECT @clicks:= count(id) from ".$prefix."click_stats where product = p.pshort  AND item_type <> '') as tempviews,
                (SELECT @sales:=count(distinct o.randomstring) FROM `rrp_member_products` m,rrp_orders o $WHEREX AND o.item_name = p.product_name) as tempsales,
                (SELECT @price:= SUM(o.payment_amount) FROM `rrp_member_products` m,rrp_orders o $WHEREX AND o.item_name = p.product_name) as tempprice,
                (SELECT @clicks:= CAST(@clicks AS DECIMAL)) as clicks,
                (SELECT @price:= CAST(@price AS DECIMAL)) as price,
                (SELECT @sales:= CAST(@sales AS DECIMAL)) as sales,
                ((@sales * 100)/@clicks ) as conversion,
                ((@price)/@clicks) as epc
                FROM $tbl_name p
                ORDER BY $field $dir  
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


?>
<link rel="stylesheet" href="../common/newLayout/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
<script src="../common/newLayout/jquery/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner"><p><strong><?php echo $Title ?></strong></p>
<div class="summery" style="margin:10px 0px">
	<fieldset>
		<legend>Summary</legend>
		<b>Total Products: <span style="color:red">
	<?php
			$row_total= $db->get_a_line("select count(*) as total from $tbl_name;");
			echo $row_total['total'];
	?></span></b>
		<b>Todays Purchase:<span style="color:red">
	<?php	$date=date('Y-m-d');
			$row_total= $db->get_a_line("select count(*) as total from rrp_member_products where date_added ='$date';");
			echo $row_total['total'];
	?>
	</span></b> 
		
	</fieldset>
</div>
<p></p>


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
	 </form>
	 </td>
	 <td>
	 	
	</td>
	 </tr>
	
	 </table>
	
  
 
 <div id="grid-reports" > 
<table class="notsortable" width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
  <thead>   
  <tr>
  	<th width="4">
  	<a href="<?php echo $targetpage?>?col=field1&amp;dir=<?php echo getDirection('field1',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Id</a><span class="<?php echo getCssClass('field1',$dir,$fieldName);?>">&nbsp;</span>
  	</th>
  	<th align="left">
  	<a href="<?php echo $targetpage?>?col=field2&amp;dir=<?php echo getDirection('field2',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Name</a><span class="<?php echo getCssClass('field2',$dir,$fieldName);?>">&nbsp;</span>
  	</th>
    <th align="left">
    <a href="<?php echo $targetpage?>?&col=field3&amp;dir=<?php echo getDirection('field3',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Earning</a><span class="<?php echo getCssClass('field3',$dir,$fieldName);?>">&nbsp;</span>
    </th>
    <th align="left">
    <a href="<?php echo $targetpage?>?col=field4&amp;dir=<?php echo getDirection('field4',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Buyers</a><span class="<?php echo getCssClass('field4',$dir,$fieldName);?>">&nbsp;</span>    </th> 
    <th align="left">
    <a href="<?php echo $targetpage?>?col=field5&amp;dir=<?php echo getDirection('field5',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Orders</a><span class="<?php echo getCssClass('field5',$dir,$fieldName);?>">&nbsp;</span>
    </th> 
    <th align="left">
    <a href="<?php echo $targetpage?>?col=field6&amp;dir=<?php echo getDirection('field6',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Refund</a><span class="<?php echo getCssClass('field6',$dir,$fieldName);?>">&nbsp;</span>
    </th>
    <th align="left">
    <a href="<?php echo $targetpage?>?col=field7&amp;dir=<?php echo getDirection('field7',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Referred</a><span class="<?php echo getCssClass('field7',$dir,$fieldName);?>">&nbsp;</span>
    </th>
    <th align="left">
    <a href="<?php echo $targetpage?>?col=field8&amp;dir=<?php echo getDirection('field8',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Cancelled</a><span class="<?php echo getCssClass('field8',$dir,$fieldName);?>">&nbsp;</span>
    </th>
     <th align="left">
        <a href="<?php echo $targetpage?>?col=field12&amp;dir=<?php echo getDirection('field12',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
        Sales
        </a><span class="<?php echo getCssClass('field12',$dir,$fieldName);?>">&nbsp;</span>
        </th> 
    <th align="left">
        <a href="<?php echo $targetpage?>?col=field9&amp;dir=<?php echo getDirection('field9',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
        Clicks
        </a><span class="<?php echo getCssClass('field9',$dir,$fieldName);?>">&nbsp;</span>
        </th> 
    <th align="left">
        <a href="<?php echo $targetpage?>?col=field10&amp;dir=<?php echo getDirection('field10',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
        Views
        </a><span class="<?php echo getCssClass('field10',$dir,$fieldName);?>">&nbsp;</span>
        </th> 
    <th align="left">
        <a href="<?php echo $targetpage?>?col=field11&amp;dir=<?php echo getDirection('field11',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
        EPC
        </a><span class="<?php echo getCssClass('field11',$dir,$fieldName);?>">&nbsp;</span>
        </th>
    
  </tr>
  </thead>
 <?php
$i=0;
if($total_pages > 0)
{
 foreach ($result as $row){
 	
 if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
 ?>
  <tr class="<?php echo $class?>">
  	<td valign="middle"><?php echo $row['id']?></td>
    <td valign="middle" style="text-align:left;"><?php echo $row['product_name'];?></td>
    <td valign="middle">$ <?php echo number_format($row['price'],2)?></td>
    <td valign="middle" style="text-align:center"><a href="buyer.php?id=<?php echo $row['id']?>"><?php echo $row['buyer']?></a></td>
    <td valign="middle" style="text-align:center"><a href="sales.php?id=<?php echo $row['id']?>"><?php echo $row['orders']?></a></td>
    <td valign="middle" style="text-align:center"><a href="refund.php?id=<?php echo $row['id']?>"><?php echo $row['refund']?></a></td>
    <td valign="middle" style="text-align:center"><a href="referer.php?id=<?php echo $row['id']?>"><?php echo $row['refer']?></a></td>
    <td valign="middle" style="text-align:center"><a href="cancel.php?id=<?php echo $row['id']?>"><?php echo $row['canceled']?></a></td>
	 <td valign="middle" style="text-align:right"><?php echo $row['sales']?></td>	
    <td valign="middle" style="text-align:right"><?php echo $row['clicks']?></td>
    <td valign="middle" style="text-align:right"><?php echo $row['views']?></td>
    <td valign="middle" style="text-align:center"><?php echo number_format($row['epc'],2)?></td>
    
  </tr>
  
<?php

 $i++; }
 		if($start==0 && $totalrec > 0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}
		
} else {
	
echo "<tr><td colspan='8' style='text-align:center'>Sorry no record found</td></tr>";
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
<?php 

 include_once("../../footer.php");?>
