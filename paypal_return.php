<?php
include_once("common/config.php");
include ("include.php");

if(!empty($_GET['randomstring'])){
         $randomstring = $_GET['randomstring'];
}
else
{
    $custom = $_POST['custom'];	
    $str = explode('|',$custom);
    $randomstring	= $str[0];
        if(empty($randomstring)){
                $custom = $_COOKIE['custom'];
                $str = explode('|',$custom);
                $randomstring	= $str[0];
        }
}

if($_POST['payer_status']=='verified'){
$str = explode('|', $_POST['custom']);
$ip = $str[1];
$amt_owed = $str[2];
$pid = $str[3];
$ref = $str[4];
$ttype = $str[5];
$today = date('Y-m-d');
$q="select count(*) as total from ".$prefix."members where randomstring='$randomstring'";
$rows=$db->get_a_line($q); 
$count = $rows['total'];

if($count > 0)
{
	$q="select id as mid from ".$prefix."members where randomstring='$randomstring'";
	$r=$db->get_a_line($q);
	$mid=$r['mid'];
}
else
{
	$now = time();
	$set = "date_joined = now(),";
	$set .= "ip='$ip',";
	$set .= "last_login = $now,";
	$set .= "firstname='Account',";
	$set .= "lastname='Pending',";
	$set .= "email='$payer_email',";
	$set .= "ref='$ref',";
	$set .= "randomstring = '$randomstring'";
	$sql= "insert into " . $prefix . "members set $set";
	$member_id = $db->insert_data_id($sql);
}
$sql = "select count(*) as total from " . $prefix . "orders where randomstring='$randomstring';";
$rows = $db->get_a_line($sql);
$count = $rows['total'];
	if($count==0){		
		if($txn_type!='web_accept'){
			
			$payment_status='Pending';
			$txn_id=$subscriber_id=$subscr_id;
			
		
			$set = "item_number='$item_number'";
			$set .= ", item_name='$item_name'";
			$set .= ", date='$today'";
			$set .= ", payment_amount='$amount3'";
			$set .= ", payer_email='$payer_email'";
			$set .= ", payment_type='$payment_type'";
			$set .= ", payment_status='$payment_status'";
			$set .= ", txnid='$txn_id'";
			$set .= ", payee_email='$business'";
			$set .= ", referrer='$ref'";
			$set .= ", randomstring='$randomstring'";
			$set .= ", subscriber_id='$subscriber_id'";
			$sql = "insert into " . $prefix . "orders set $set;";
			$db->insert($sql);
			
			$set = "member_id='$member_id'";
			$set .= ", product_id='$item_number'";
			$set .= ", date_added='$today'";
			$set .= ", txn_id='$txn_id'";
			$set .= ", type='$prodtype'";
			$sql = "insert into " . $prefix . "member_products set $set ;";
			$db->insert($sql); // 	CREATE A NEW ORDER
			//$string .= " \n CREATE ORDER SQL:  " . $sql;
		}
	}
	
}

$pagecontent = "
<script src=\"/common/newLayout/jquery/jquery.js\" type=\"text/javascript\" charset=\"utf-8\"></script>
<div align=center id='payment_content'>
    <table align='center'>
    <tr><td align=center valign=center>
            <table width=600px border=1><tr><td>
                    <font face=verdana>
                    <p align=center>
                    <b>Validating your PayPal purchase, </b></p>
                    <p align=center>
                    please wait... <span id='time'></span>
                    </p>
                    <p align=center>
                    <div id='loading'align='center'><img src='images/wait.gif'></div>
                    <div id='page-content'></div>
                    </p>
                    <p align=center>		
                    After confirmation you will be taken to the signup form to complete your purchase.<br>
                    
                    </p>
                    </font>
            </td></tr></table>
    </td></tr></table>
</div>
<script type='text/javascript'>
var echeck = '<br /><br /><p align=center id=payment_content><table align=center width=600 border=1 cellpadding=10><tr><td><font face=verdana>&nbsp;</font><p align=justify><font color=red face=verdana><b>NOTICE:</b></font><font face=verdana> It looks like you paid with an eCheck or bank draft. We can not complete your purchase until your payment clears through PayPal. Your download will become available to you the instant your payment clears through PayPal. This should take 3 to 4 days.</font></p><p align=center><font face=verdana><a href=index.php>Click Here To Continue To The Member Area</a> </font></p></td></tr></table><br /><br /></p>';
var varCounter = 1;
var number_of_try =15;        
var ajaxcontent = function(){
     if(varCounter < number_of_try) {
            $('#time').html(varCounter + ' seconds');
            $.ajax({
		        type: 'POST',
		        url: 'confirmpayment.php',
		        data : 'random=$randomstring&try='+varCounter ,
		        dataType: 'html',
		        cacheBoolean:false,
		        success: function(data) {
                        if(varCounter == number_of_try)
                            {
                              $('#page-content').html(data); 
                              $('#page-content').addClass('error'); 
                              $('#loading').css('display','none'); 
                            }
						else{
                             $('#loading').css('display','none');
                             varCounter = number_of_try;
                             window.location.href='signup.php?randomstring=$randomstring&pid=$product_id'
                           }	
     	        }
	        }); 
         varCounter++;
     } 
        else 
          clearInterval(ajaxcontent);
};
$(document).ready(function(){
     setInterval(ajaxcontent, 1000);
});
  
</script>        
        
";
	
	$smarty->assign('pagename','PayPal purchase process...');
	$smarty->assign('main_content',$pagecontent);
	$output = $smarty->fetch('html/content.tpl');
	
	$objTpl = new TPLManager($FILEPATH.'/index.html');
	$hotspots = $objTpl->getHotspotList();
	$placeHolders = $objTpl->getPlaceHolders($hotspots);
	$i=0;
	foreach($placeHolders as  $items)
	{
		$smarty->assign("$hotspots[$i]","$items");
		$i++;
	}
	$smarty->assign('content',$output);
	$smarty->assign('error',$warning);
	$smarty->display($FILEPATH.'/index.html');
?>