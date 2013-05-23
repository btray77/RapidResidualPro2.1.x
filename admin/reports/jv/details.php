<?php
include_once("../../session.php");
include_once("../../header.php");
$Title = "JV's Detail Report";


$targetpage = "product.php?mid=$mid"; 	//your file name  (the name of this file)

################## PAGINATION ########################
$fieldNamesArray = array(
	"field1" => "id",
	"field2" => "email",
	"field3" => "username",
//"field4" => "discount"
);

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
if(empty($month))
$month=date('m');
if(empty($year))
$year=date('Y');
$sql = "
		SELECT od.item_number as id,od.date,od.item_name as product_name,od.payment_amount,p.commission,
		(SELECT COUNT(id) FROM ".$prefix."click_stats   WHERE referrer = '$mid' and product=item_name) AS click
		
		FROM ".$prefix."orders od ,".$prefix."products p
		
		WHERE od.referrer ='$mid' AND p.id = od.item_number
		GROUP BY item_name";
$result = $db->get_rsltset($sql);

/* Setup page vars for display. */


/*
 Now we apply our rules and draw the pagination object.
 We're actually saving the code to a variable in case we want to draw it more than once.
 */



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
$days=days_in_month($month,$year);

?>
<link
	rel="stylesheet" href="../common/newLayout/prettyPhoto.css"
	type="text/css" media="screen" charset="utf-8" />
<script
	src="../common/newLayout/jquery/jquery.prettyPhoto.js"
	type="text/javascript" charset="utf-8"></script>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong><?php echo $Title ?></strong></p>
<div align="right" style="width: auto; float: right;">[<a
	href="index.php">BACK</a>]</div>
<a id="pagination"></a>
<h4>Summary Report of <?php echo $mid?> for <?php echo $month .' '. $year;  ?></h4>
<div class="summary" style="width: 50%;">
<div class="form">

<form method="get" action="" name="">Stats For <select name="month">
	<option value="01" <?php if($month == '01') echo 'selected';?>>Jan</option>
	<option value="02" <?php if($month == '02') echo 'selected';?>>Feb</option>
	<option value="03" <?php if($month == '03') echo 'selected';?>>March</option>
	<option value="04" <?php if($month == '04') echo 'selected';?>>April</option>
	<option value="05" <?php if($month == '05') echo 'selected';?>>May</option>
	<option value="06" <?php if($month == '06') echo 'selected';?>>June</option>
	<option value="07" <?php if($month == '07') echo 'selected';?>>July</option>
	<option value="08" <?php if($month == '08') echo 'selected';?>>Aug</option>
	<option value="09" <?php if($month == '09') echo 'selected';?>>Sep</option>
	<option value="10" <?php if($month == '10') echo 'selected';?>>Oct</option>
	<option value="11" <?php if($month == '11') echo 'selected';?>>Nov</option>
	<option value="12" <?php if($month == '12') echo 'selected';?>>Dec</option>
</select> <select name="year">
<?php for($i =2011;$i<2021;$i++){

	?>
	<option value="<?php echo $i;?>" <?php if($i==$year) echo 'selected';?>><?php echo $i;?></option>
	<?php }?>
</select> <input type="hidden"  name="mid" value="<?php echo $mid?>"> <input
	type="submit" name="search" value="search"></form>
</div>
<table border="0" cellspacing="0" cellpadding="5">
	<tr>
		<th>&nbsp;</th>
		<th><?php echo $month;?></th>
		<th>Accumulated</th>
	</tr>
	<tr>
		<th>Total Views </th>
		<td><?php
		$start_date=$year.'-'.$month . '-01';
		$end_date=$year.'-'.$month . '-' .$days;

		$sql = "select count(id) as total from ".$prefix."click_stats where referrer = '$mid' and visited_date between '$start_date' and '$end_date'";
		$row_total= $db->get_a_line($sql);
		echo $row_total['total'];
		?></td>
		<td><?php $sql = "select count(id) as total from ".$prefix."click_stats where referrer = '$mid'";
		$row_total= $db->get_a_line($sql);
		echo $row_total['total'];?></td>
	</tr>
	<tr>
		<th>Uniques Views </th>
		<td><?php
		$start_date=$year.'-'.$month . '-01';
		$end_date=$year.'-'.$month . '-' .$days;

		$sql = "select count(id) as total from ".$prefix."click_stats where referrer = '$mid' and visited_date between '$start_date' and '$end_date' AND item_type <> ''";
		$row_total= $db->get_a_line($sql);
		echo $row_total['total'];
		?></td>
		<td><?php $sql = "select count(id) as total from ".$prefix."click_stats where referrer = '$mid' AND item_type <> ''";
		$row_total= $db->get_a_line($sql);
		echo $row_total['total'];?>
		  </td>
	</tr>
	<tr>
		<th>Sales Generated</th>
		<td>
		<?php 
			$sql_thismonth = "select count(payment_amount) as total from ".$prefix."orders o, ".$prefix."members m
							where m.paypal_email = o.payee_email
							AND referrer = '$mid'
							AND o.date between '$start_date' and '$end_date'
							AND o.payment_status='Completed';";
			$row_thismonth = $db->get_a_line($sql_thismonth);
			if($row_thismonth['total']){
				$thismonth = $row_thismonth['total'];
			}else{
				$thismonth = '0';
			}
			echo $thismonth;
		?>		</td>
		<td>
		<?php
			$sql_accumulated = "select count(payment_amount) as total from ".$prefix."orders o, ".$prefix."members m
							where m.paypal_email = o.payee_email
							AND referrer = '$mid'
							AND o.payment_status='Completed';";
			$row_accumulated = $db->get_a_line($sql_accumulated);
			if($row_accumulated['total']){
				$thismonthacc = $row_accumulated['total'];
			}else{
				$thismonthacc = '0';
			}
			echo $thismonthacc;
		?>		</td>
	</tr>
	<tr>
		<th>Total Sales Generated</th>
		<td><?php $sql = "select count(id) as total from ".$prefix."orders where referrer = '$mid' AND payment_status ='Completed' AND 	date between '$start_date' and '$end_date'";
		$row_total= $db->get_a_line($sql);
		echo $row_total['total'];?></td>
		<td><?php $sql = "select count(id) as total from ".$prefix."orders where referrer = '$mid' AND payment_status ='Completed'";
		$row_total= $db->get_a_line($sql);
		echo $row_total['total'];?></td>
	</tr>
	<tr>
		<th>Commissions Earned</th>
		<td><?php 
		$sql = "select SUM(payment_amount) as total from ".$prefix."orders o, ".$prefix."members m where m.paypal_email = o.payee_email
	AND referrer = '$mid' AND 	o.date between '$start_date' and '$end_date' AND o.payment_status='Completed' ;";
		$row_total= $db->get_a_line($sql);
		echo '$ '.number_format($row_total['total'],2);?></td>
		<td><?php
		$sql = "select SUM(payment_amount) as total from ".$prefix."orders o, ".$prefix."members m where m.paypal_email = o.payee_email
	AND referrer = '$mid' AND o.payment_status='Completed'; ";
		$row_total= $db->get_a_line($sql);
		echo '$ '.number_format($row_total['total'],2);?></td>
	</tr>
</table>


</div>





<div id="grid-reports" style="width: 70%;">
<table class="notsortable" border="0" align="center" cellpadding="2"
	cellspacing="0">

	<tr>

		<th style="text-align: center">Date</th>
		<th style="text-align: center">Sales</th>
		<th style="text-align: center">Earned</th>
                <th style="text-align: center">Views</th>
                <th style="text-align: center">Clicks</th>
                <th style="text-align: center">Conversion</th>
                <th style="text-align: center">EPC</th>
	</tr>

	<?php
	$i=0;
	if(count($total_pages) > 0)
	{
		for($day=1;$day <= days_in_month($month,$year);$day++){
			$date= $day.'-'. $month .'-'. $year;
			$start_date =  $year.'-'. $month .'-'. $day;
			if ($i%2 == 0){ $class= "standardRow";} else{ $class= "alternateRow";}
			?>
	<tr class="<?php echo $class?>">
		<td valign="middle" style="text-align: center;"><?php echo $date;?></td>
		<td valign="middle" style="text-align: center"><?php $sql = "select count(payment_amount) as total from ".$prefix."orders o, ".$prefix."members m where m.paypal_email = o.payee_email
	AND referrer = '$mid' AND 	o.date ='$start_date' AND o.payment_status='Completed';";
			$row_total= $db->get_a_line($sql);
			if($row_total['total']> 0)
                        {
                            $sales=$row_total['total'];
			echo $row_total['total'];
                        }
			else
                        {
                        $sales=0;
			echo '--';
                        }
                        ?></td>
		<td valign="middle" style="text-align: center">
                 <?php 
		$sql = "select SUM(payment_amount) as total from ".$prefix."orders o, ".$prefix."members m where m.paypal_email = o.payee_email
	AND referrer = '$mid' AND 	o.date ='$start_date' AND o.payment_status='Completed';";
		$row_total= $db->get_a_line($sql);
		if($row_total['total']> 0){
                $earning=$row_total['total'];
		echo '$ '.number_format($row_total['total'],2);
                }
		else
                {
		echo '--';
                $earning=0;
                }
		?></td>
                <td style="text-align: center">
				
				 <?php 
		$sql = "select count(id) as total from ".$prefix."click_stats where referrer = '$mid' and 
                    visited_date = '$start_date'";
		$row_total= $db->get_a_line($sql);
		if($row_total['total']> 0){
                $clicks=$row_total['total'];
		echo $row_total['total'];
                }
		else
                {
		echo '--';
                $clicks=0;
                }
				?>
				</td>
                <td style="text-align: center">
                    <?php 
		$sql = "select count(id) as total from ".$prefix."click_stats where referrer = '$mid' and 
                    visited_date = '$start_date' AND item_type <> ''";
		$row_total= $db->get_a_line($sql);
		if($row_total['total']> 0){
                $clicks=$row_total['total'];
		echo $row_total['total'];
                }
		else
                {
		echo '--';
                $clicks=0;
                }
                ?>                </td>
                <td style="text-align: center"><?php
                if($clicks >0 && $sales > 0 ){
                echo number_format((($sales*100) /$clicks),2).' %';}
                else
                    echo '--';
                ?></td>
                <td style="text-align: center">
                <?php 
                 if($clicks >0 && $sales > 0 && $earning > 0 ){
                echo number_format(($earning/$clicks),2);
                 }
                 else
                      echo '--';
                ?>                </td>
	</tr>
	<?php
	$i++; }
	if($start==0 && $totalrec > 0){$totalrec=$startrec+$i; $startrec=$start+1;}
	else {$startrec=$start;$totalrec=$startrec+$i;}
	}
	else {
		echo "<tr><td colspan='7' style='text-align:center'>Sorry no record found</td></tr>";
	}
	?>
</table>

<div><a href="#top" style="text-align: center;">Move to top</a></div>
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>
	<?php
	function days_in_month($month, $year)
	{
		return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
	}

	?>
	<?php include_once("../../footer.php");?>
