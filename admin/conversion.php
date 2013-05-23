<?php
include_once("session.php");
include_once("header.php");
$GetFile = file("../html/admin/conversion.html");
$Content = join("",$GetFile);
$Title = "Product Conversion Reports";
$targetpage = "conversion.php"; 	//your file name  (the name of this file)
$tbl_name=$prefix."products";		//your table name
$fieldNamesArray = array(
	"field1" => "id",
	"field2" => "product_name",
	"field3" => "prodtype",
	"field4" => "views",
    "field5" => "sales",
    "field6" => "conversion",
    "field7" => "EPC",
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
                $WHEREX=" where o.item_number=m.product_id 
                AND o.txnid= m.txn_id
                AND o.payment_status='Completed'
                AND o.txnid <> 'ALLOCATED'
                AND o.txnid <> 'FREE'";
				
               $sql = "SELECT p.id,p.pshort,p.product_name,p.prodtype, 
                (SELECT @clicks:= count(id) from ".$prefix."click_stats where product = p.pshort AND item_type <> '') as tempviews,
				(SELECT @views:=count(id) from ".$prefix."click_stats where product = p.pshort ) as tempviews,
                (SELECT @sales:=count(distinct o.randomstring) FROM ".$prefix."member_products m,rrp_orders o $WHEREX AND o.item_name = p.product_name) as tempsales,
                (SELECT @price:= SUM(o.payment_amount) FROM ".$prefix."member_products m,".$prefix."orders o $WHEREX AND o.item_name = p.product_name) as tempprice,
                (SELECT @clicks:= CAST(@clicks AS DECIMAL)) as clicks,
                (SELECT @price:= CAST(@price AS DECIMAL)) as price,
				(SELECT @views:= CAST(@views AS DECIMAL)) as views,
                (SELECT @sales:= CAST(@sales AS DECIMAL)) as sales,
                ((@sales * 100)/@clicks ) as conversion,
                (@price/@clicks) as epc
                FROM $tbl_name p 
                ORDER BY $field $dir  limit $start,$limit";
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
//$Content = preg_replace($Pat,$ToReplace,$Content);
//$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
//echo $Content;
?>
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
	
	 </tr>
	
	 </table>
	
  
 
 <div id="grid-reports"> 
<table class="notsortable" width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
  <thead>   
  <tr>
  	<th>
  	<a href="<?php echo $targetpage?>?col=field1&amp;dir=<?php echo getDirection('field1',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Id</a><span class="<?php echo getCssClass('field1',$dir,$fieldName);?>">&nbsp;</span></th>
    <th align="left">
    <a href="<?php echo $targetpage?>?col=field2&amp;dir=<?php echo getDirection('field2',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Product Name</a><span class="<?php echo getCssClass('field2',$dir,$fieldName);?>">&nbsp;</span>    </th>
     <th>
         <a href="<?php echo $targetpage?>?col=field3&amp;dir=<?php echo getDirection('field3',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Type</a><span class="<?php echo getCssClass('field3',$dir,$fieldName);?>">&nbsp;</span>     </th>
     <th>
         <a href="<?php echo $targetpage?>?col=field3&amp;dir=<?php echo getDirection('field3',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Total Earning</a><span class="<?php echo getCssClass('field3',$dir,$fieldName);?>">&nbsp;</span>     </th>
    <th align="left"><a href="<?php echo $targetpage?>?col=field4&amp;dir=<?php echo getDirection('field4',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Sales</a><span class="<?php echo getCssClass('field8',$dir,$fieldName);?>">&nbsp;</span></th>
    <th><a href="<?php echo $targetpage?>?col=field4&amp;dir=<?php echo getDirection('field8',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Views</a><span class="<?php echo getCssClass('field8',$dir,$fieldName);?>">&nbsp;</span></th>
    <th>
        <a href="<?php echo $targetpage?>?col=field5&amp;dir=<?php echo getDirection('field5',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">Clicks</a><span class="<?php echo getCssClass('field5',$dir,$fieldName);?>">&nbsp;</span>        </th>
    <th>
        <a href="<?php echo $targetpage?>?col=field6&amp;dir=<?php echo getDirection('field6',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Conversion</a><span class="<?php echo getCssClass('field6',$dir,$fieldName);?>">&nbsp;</span>        </th>
    <th>
        <a href="<?php echo $targetpage?>?col=field7&amp;dir=<?php echo getDirection('field7',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	EPC</a><span class="<?php echo getCssClass('field7',$dir,$fieldName);?>">&nbsp;</span>        </th>
  </tr>
  </thead>
 <?php
$i=0;
 while($row=mysql_fetch_array($result)){
 if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
 	
 	$q = "select count(*) as cnt from ".$prefix."click_stats where product ='$row[pshort]'";
	$r1 = $db->get_a_line($q);
	$visits = $r1[cnt];
	
 
 	$q = "select count(*) as cnt from ".$prefix."member_products where product_id ='$row[id]' && refunded='0'";
	$r2 = $db->get_a_line($q);
	$sales = $r2[cnt];
	if($sales > '0' && $visits > '0')
	{
		$count1 = $sales / $visits;
		$count2 = $count1 * 100;
		$rate = number_format($count2, 2);
	}
	else
	{
		$rate = '0';
	}
	$Getmem = $db->get_a_line("select firstname, lastname, email from ".$prefix."members where randomstring = '$randomstring'");
	@extract($Getmem);			
		
		
	
 	
 	?>
  <tr class="<?php echo $class?>">
  	<td valign="middle"><?php echo $row['id']?></td>
    <td valign="middle" ><?php echo $row['product_name']?></td>
    <td valign="middle"><?php echo $row['prodtype']?></td>
    <td valign="middle">$ <?php echo number_format($row['price'],2)?></td>
    
    <td valign="middle" style="text-align: center"><?php echo $row['sales']?></td>
    <td valign="middle" style="text-align: center"><?php echo $row['views']?>&nbsp;</td>
    <td valign="middle" style="text-align: center"><?php echo $row['clicks']?></td>
    <td valign="middle" style="text-align: center"><?php echo number_format($row['conversion'],2)?> % </td>
    <td valign="middle" style="text-align: center"><?php 
    if($row['sales']>0 && $row['views'] > 0 )    {
     $sales = $row['views']/$row['sales'];
     
     $epc= ($row['price']*$row['sales'])/$row['views'];
    }
    else
    {$epc=0;}
    echo number_format($row['epc'],2)?></td>
  </tr>
  
<?php
 if($start==0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}
 $i++;} ?>
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
<?php include_once("footer.php");?>