<?php
include_once("../../session.php");
include_once("../../header.php");
$Title = "JV's Reporting";

$tbl_name=$prefix."members";		//your table name

if(!empty($filter))
{
$targetpage = "index.php?filter=$filter"; 	//your file name  (the name of this file)	
}
else
$targetpage = "index.php?filter=all"; 	//your file name  (the name of this file)

################## PAGINATION ########################
$fieldNamesArray = array(
	"field1" => "id",
	"field2" => "firstname",
	"field3" => "views",
	"field4" => "referer",
	"field5" => "permotion",
	"field7" => "click",
	"field8" => "paypal",
	"field9" => "AlertPay",
	
	);

	
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;

	/*
	 First get total number of rows in data table.
	 If you have a WHERE clause in your query, make sure you mirror it here.
	 */
	
	
	$sql = "select count(*) as total from $tbl_name WHERE status=3";
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
		$dir = "DESC";
	}

	if($page)
	$start = ($page - 1) * $limit; 			//first item to display on this page
	else
	$start = 0;								//if no page var is given, set start to 0
	/* Get data. */
	$WHEREX=" where o.item_number=m.product_id 
                AND o.txnid= m.txn_id
                AND o.payment_status='Completed'
                AND o.txnid <> 'ALLOCATED'
                AND o.txnid <> 'FREE' 
				AND	m.paypal_email = o.payee_email AND o.referrer = m.username
				";
	
	
	$sql = "
	 SELECT m.id, m.firstname, m.lastname, m.email, m.ref,m.randomstring,m.username, 
	(SELECT @views:= COUNT(id) FROM ".$prefix."click_stats WHERE referrer = m.username) AS views, 
	(SELECT @clicks:= COUNT(id) FROM ".$prefix."click_stats WHERE referrer = m.username) AS click,
	(SELECT COUNT(*) FROM $tbl_name WHERE ref = m.username) AS referer,
	(SELECT count(p.id) FROM ".$prefix."orders o,".$prefix."products p  WHERE 
                m.paypal_email = o.payee_email AND  
                o.item_number = p.id AND 
                o.referrer = m.username AND 
                o.payment_status='Completed'
                ) AS permotion,
(SELECT sum(o.payment_amount) FROM ".$prefix."member_products m,".$prefix."orders o $WHEREX AND o.payment_gateway ='') AS paypal,
(SELECT sum(o.payment_amount) FROM ".$prefix."member_products m,".$prefix."orders o $WHEREX AND o.payment_gateway ='AlertPay') AS AlertPay,
(SELECT sum(o.payment_amount) FROM ".$prefix."member_products m,".$prefix."orders o $WHEREX AND o.payment_gateway ='Clickbank') AS Clickbank
	from $tbl_name m
	
	WHERE status=3  order by $field $dir  limit $start,$limit";
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
	
  
 
 <div id="grid-reports"> 
<table class="notsortable" width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
  <thead>   
 <tr>
    <th width="4">
  	<a href="<?php echo $targetpage?>&col=field1&amp;dir=<?php echo getDirection('field1',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Id</a><span class="<?php echo getCssClass('field1',$dir,$fieldName);?>">&nbsp;</span>  	</th>
  	<th align="left">
  	<a href="<?php echo $targetpage?>&col=field2&amp;dir=<?php echo getDirection('field2',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Name</a><span class="<?php echo getCssClass('field2',$dir,$fieldName);?>">&nbsp;</span>  	</th>
   <!-- <th align="left">
    <a href="<?php echo $targetpage?>&col=field3&amp;dir=<?php echo getDirection('field3',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Email</a><span class="<?php echo getCssClass('field3',$dir,$fieldName);?>">&nbsp;</span>
    </th> -->
    <th align="left">
    <a href="<?php echo $targetpage?>&col=field4&amp;dir=<?php echo getDirection('field4',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Referred</a><span class="<?php echo getCssClass('field4',$dir,$fieldName);?>">&nbsp;</span>    </th> 
    <th align="left">
    <a href="<?php echo $targetpage?>&col=field5&amp;dir=<?php echo getDirection('field5',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Sale</a><span class="<?php echo getCssClass('field5',$dir,$fieldName);?>">&nbsp;</span>    </th> 
    <th align="left"> <a href="<?php echo $targetpage?>&col=field3&amp;dir=<?php echo getDirection('field3',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Views</a><span class="<?php echo getCssClass('field3',$dir,$fieldName);?>">&nbsp;</span></th>
    <th align="left">
    <a href="<?php echo $targetpage?>&col=field6&amp;dir=<?php echo getDirection('field6',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Clicks</a><span class="<?php echo getCssClass('field6',$dir,$fieldName);?>">&nbsp;</span>    </th>
    
    <th align="left">
    <a href="<?php echo $targetpage?>&col=field7&amp;dir=<?php echo getDirection('field7',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	PayPal</a><span class="<?php echo getCssClass('field7',$dir,$fieldName);?>">&nbsp;</span>    </th>
    <th align="left">
    <a href="<?php echo $targetpage?>&col=field8&amp;dir=<?php echo getDirection('field8',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	AlertPay</a><span class="<?php echo getCssClass('field8',$dir,$fieldName);?>">&nbsp;</span>    </th>
    <th align="left">
    <a href="<?php echo $targetpage?>&col=field9&amp;dir=<?php echo getDirection('field9',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	ClickBank</a><span class="<?php echo getCssClass('field9',$dir,$fieldName);?>">&nbsp;</span>    </th>
    <th align="left">Earning</th>
    <th align="left">
   	Conversion    </th>
    <th align="left">
   	EPC    </th>
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
    <td valign="middle" style="text-align:left;"><a href="details.php?mid=<?php echo $row['username']?>">
    					<?php echo $row['firstname'] .' '. $row['lastname'];?></a></td>
   <!-- <td valign="middle"><?php echo $row['email']?></td> -->
    <td valign="middle" style="text-align:center"><a href="referer.php?mid=<?php echo $row['username']?>"><?php echo $row['referer']?></a></td>
    <td valign="middle" style="text-align:center"><a href="promotion.php?mid=<?php echo $row['username']?>"><?php echo $row['permotion']?></a></td>
    <td valign="middle" style="text-align:center"><?php echo $row['views']?></td>
    <td valign="middle" style="text-align:center"><?php echo $row['click']?></td>
    <td valign="middle" style="text-align:right">$ <?php echo number_format($row['paypal'],2)?></td>
    <td valign="middle" style="text-align:right">$ <?php echo number_format($row['Alertpay'],2)?></td>
    <td valign="middle" style="text-align:right">$ <?php echo number_format($row['Clickbank'],2)?></td>
    <td valign="middle" style="text-align:right">$ <?php   $total=$row['paypal']+$row['Alertpay']+$row['Clickbank'];
    echo number_format($total,2)?></td>
    <td valign="middle" style="text-align:center"><?php if($row['click'] > 0) {
                               echo number_format((($row['permotion']*100) /$row['click']),2).' %';
                                }else
                                     echo '0.00 %'; ?> </td>
    <td valign="middle" style="text-align:center"><?php if($row['click'] > 0) {
                               $price=$row['paypal']+ $row['Alertpay']+$row['Clickbank'];
                               echo number_format(($price/$row['click']),2).'';
                                }else
                                     echo '0.00'; ?> </td>
  </tr>
  
<?php

 $i++; }
 		if($start==0 && $totalrec > 0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}
		
} else {
	echo "<tr><td colspan='9' style='text-align:center'>Sorry no record found</td></tr>";
	
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




 include_once("../../footer.php");?>
