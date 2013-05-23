<?php
include_once("session.php");
include_once("header.php");

$sql_order = "SELECT count(txnid) as total,txnid  FROM `rrp_orders` Where txnid <> 'FREE' AND txnid <>'ALLOCATED'  group by txnid order by  total  DESC";
$result_order = mysql_query($sql_order);
$row_order= mysql_fetch_array($result_order);

?>

<link rel="stylesheet" href="../common/newLayout/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
<script src="../common/newLayout/jquery/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<a id="pagination"></a> 
<div class="content-wrap-inner"><strong><?php echo $Title ?></strong><br />
	
	
		<?php 
		
		 
		
		while($row_order = mysql_fetch_assoc($result_order)){
			if($row_order['total'] > 1)
				{
				 $sql ="select * from `rrp_orders` where `txnid` ='$row_order[txnid]' ";
				 $rs=mysql_query($sql);
				 $i=0;
				 while($row = mysql_fetch_assoc($rs)){
				 	if($i==0){
						$sql_update_order ="UPDATE `rrp_orders` SET subscriber_id ='$row_order[txnid]' where `txnid` ='$row_order[txnid]'";
						mysql_query($sql_update_order);
						 $sql_order ="select o.id as oid,m.member_id   from `rrp_orders` o, rrp_member_products m where o.`txnid` = m.txn_id AND o.`txnid` ='$row[txnid]' ";
					 											
						//print_r($row);		
						}
					else {
						
						$rs_orders = mysql_query($sql_order);
						$rowdata = mysql_fetch_assoc($rs_orders);
						
						echo "<br><font color='red'> Add to subscribtion History</font> <br>";
						$sql_insert = $sql ='INSERT INTO rrp_subscription_payment_history  SET '.
											"oid='".$rowdata['oid']."',".
											"create_date='".$row['date']."',".
											"product_id='".$row['item_number']."',".
											"price='".$row['payment_amount']."',".
											"payment_status='".$row['payment_status']."',".
											"subscribtion_id='".$row['txnid']."',".
											"user_id='".$rowdata['member_id']."';";
																
						if(!mysql_query($sql_insert)) die( mysql_error());
						$sql ="DELETE FROM `rrp_orders` where id =$row[id]";
						mysql_query($sql);
					}
					$i++;
					}
					
				}
                }
                ?>
    <div class="success" style="width: 90%">Migration is completed...</div> 

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
