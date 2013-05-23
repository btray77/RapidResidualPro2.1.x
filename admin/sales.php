<?php
include_once("session.php");
include_once("header.php");

$Title = "Sales Reports";

$targetpage = "sales.php"; 	//your file name  (the name of this file)
$tbl_name=$prefix."orders";		//your table name

$fieldNamesArray = array(
	"field1" => "id",
	"field2" => "item_name",
	"field3" => "payment_amount",
	"field4" => "payment_status",
	"field5" => "payment_gateway",
	"field6" => "txnid",
	"field7" => "payer_email",
	"field8" => "payee_email",
	"field9" => "date",
	"field10" => "referrer",
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
		$dir = "DESC";
	}

	if($page)
	$start = ($page - 1) * $limit; 			//first item to display on this page
	else
	$start = 0;								//if no page var is given, set start to 0

	/* Get data. */
	

	$sql = "select *  from $tbl_name  order by $field $dir  limit $start,$limit";
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
<a id="pagination"></a> 
<div class="content-wrap-inner"><p><strong><?php echo $Title ?></strong></p>


<br>




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
	
  
 
 <div id="grid-reports" > 
 <div style="overflow-y:scroll">
<table class="notsortable" width="95%" border="0" align="center" cellpadding="2" cellspacing="0" >
  <thead>   
  <tr>
   <th nowrap="nowrap">
    <a href="<?php echo $targetpage?>?col=field9&amp;dir=<?php echo getDirection('field9',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Order Date</a><span class="<?php echo getCssClass('field9',$dir,$fieldName);?>">&nbsp;</span>
	</th>
  	<th nowrap="nowrap"><a href="<?php echo $targetpage?>?col=field1&amp;dir=<?php echo getDirection('field1',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">OID</a><span class="<?php echo getCssClass('field1',$dir,$fieldName);?>">&nbsp;</span>
	</th>
	<th nowrap="nowrap"><a href="<?php echo $targetpage?>?col=field10&amp;dir=<?php echo getDirection('field10',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">Referrer</a><span class="<?php echo getCssClass('field10',$dir,$fieldName);?>">&nbsp;</span>
	</th>
    <th align="left" nowrap="nowrap" >
    <a href="<?php echo $targetpage?>?col=field2&amp;dir=<?php echo getDirection('field2',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Product Name</a><span class="<?php echo getCssClass('field2',$dir,$fieldName);?>">&nbsp;</span>    </th>
    
    <th nowrap="nowrap">Member Info</th>
    <th nowrap="nowrap">
    <a href="<?php echo $targetpage?>?col=field3&amp;dir=<?php echo getDirection('field3',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Price</a><span class="<?php echo getCssClass('field3',$dir,$fieldName);?>">&nbsp;</span>    </th>
    <th nowrap="nowrap">
     <a href="<?php echo $targetpage?>?col=field4&amp;dir=<?php echo getDirection('field4',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Status</a><span class="<?php echo getCssClass('field1',$dir,$fieldName);?>">&nbsp;</span>    </th>
    <th nowrap="nowrap">
     <a href="<?php echo $targetpage?>?col=field5&amp;dir=<?php echo getDirection('field5',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Gateway</a><span class="<?php echo getCssClass('field5',$dir,$fieldName);?>">&nbsp;</span>    </th>
    <th nowrap="nowrap">
     <a href="<?php echo $targetpage?>?col=field6&amp;dir=<?php echo getDirection('field6',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Transaction Id</a><span class="<?php echo getCssClass('field6',$dir,$fieldName);?>">&nbsp;</span>    </th>
   
    <th nowrap="nowrap">
    <a href="<?php echo $targetpage?>?col=field8&amp;dir=<?php echo getDirection('field8',$dir,$fieldName)?>&amp;limit=<?php echo $limit?>#pagination">
	Payee Email</a><span class="<?php echo getCssClass('field8',$dir,$fieldName);?>">&nbsp;</span>    </th>
   
   
   
  </tr>
  </thead>
 <?php
$i=0;
 while($row=mysql_fetch_array($result)){
 $status='';
 if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
	$sql="select firstname, lastname, email from ".$prefix."members where randomstring = '$row[randomstring]'";
	$Getmem = $db->get_a_line($sql);
	@extract($Getmem);			
 	if($row['txnid']=='FREE') $status='free';
	if($row['txnid']=='ALLOCATED') $status='allocate'; 
	
	?>
	<tr class="<?php echo $class .' '. $status?>">
	<td valign="middle"> <?php echo $row['date']?></td>
    <td valign="middle">
	<?php
	
	 if(!empty($row['subscriber_id'])){?>
	<a href="paymenthistory.php?oid=<?php echo $row['id']; ?>" title="Check Subscribtion Product Payment History"><?php echo $row['id']?></a>
	<?php } else echo $row['id'];?>
	</td>
	<td valign="middle"><?php echo $row['referrer']?></td>
    <td valign="middle" ><?php echo $row['item_name']?></td>
    <td valign="middle"  nowrap="nowrap" > <?php echo $firstname; ?> <?php echo $lastname;?><br /><?php echo $email;?><br />
	<strong>Payer ID:</strong><?php echo $row['payer_email']?>
	</td>
    <td valign="middle" ><?php echo $row['payment_amount']?></td>
    <td valign="middle"><?php echo $row['payment_status']?></td>
    <td valign="middle">
    	<?php if(empty($row['payment_gateway'])) echo 'PayPal'; else echo $row['payment_gateway']?>
    </td>
   	<td valign="middle"><?php echo $row['txnid']?></td>
   
    <td valign="middle"><?php echo $row['payee_email']?></td>
  
    
   
  </tr>
  
<?php
 if($start==0){$totalrec=$startrec+$i; $startrec=$start+1;}
		else {$startrec=$start;$totalrec=$startrec+$i;}
 $i++;} ?>
</table>
</div>
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
<script type="text/javascript" charset="utf-8">
		$(document).ready(function(){
			$("a[rel^='prettyPhoto']").prettyPhoto();
		});
</script>
<style>
.free{background-color:#FFF0FF;}
.allocate{background-color:#F0FFF0;}
</style>
<?php include_once("footer.php");?>
