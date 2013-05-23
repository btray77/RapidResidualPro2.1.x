<?php

include_once("session.php");

include_once("header.php");

$file=file("../html/admin/admin_menu.html");

$returncontent=join("",$file);

$back_start="<!--";

$back_end="-->";



// Get version number

$q = "select * from ".$prefix."site_settings where id='1'";

$v = $db->get_a_line($q);

$version = $v['version'];
$msg='';





$errorCodes = array('300'=>'User Access Denial');







$filename = '../install/';



if (file_exists($filename))

{

	$msg .= '<div class="warning"><img src="../images/warning.png" align="absmiddle">You have not deleted the install directory. This should be deleted or renamed to help secure your website.</div>';

}

$perms = substr(sprintf('%o', fileperms('../common/config.php')), -4);

if ($perms == "0777")

{

	$msg .= '<div class="top-message"><img src="../images/crose.png" align="absmiddle"> <b>You config.php file has permissions set to 777. This should be changed to 644 to ensure your site security.</b></div>';

}



if (isset($_GET['error'])){

	$errorCode = $_GET['errorCode'];

	$msg .= '<div class="top-message"><img src="../images/crose.png" align="absmiddle"> <b>Access denied based on your admin permission settings. 

	If you need access to this resource, contact the site administrator</b></div>';

	/*if($errorCode == '300'){

	}*/

}



//$graph = file_get_contents('graph.php');

$panel =  file_get_contents("http://www.rapidresidualpro.com/admin_panel/panel.php");

//$panel .= join("",$getpanel);



/*********** Admin Right Bar*************/

		$today_date = date("Y-m-d");
		
	// Referrer Query	

		$query_ref = "select count(*) as cReferers from ".$prefix."members where  ref != '' and ref != 'None' and date_joined BETWEEN '".$today_date."' AND '".$today_date."'";	

		$row_ref = $db->get_a_line($query_ref);

		

	// Sales Query	

		$query_sales = "SELECT count(*) as cSales		

						FROM ".$prefix."orders od ,".$prefix."products p

						WHERE p.id = od.item_number

						and od.date = '".$today_date."'";	

		$row_sales = $db->get_a_line($query_sales);



	// Member Query

		$query_mem = "select (select count(*) as total from ".$prefix."members where date_joined BETWEEN '".$today_date."' AND '".$today_date."') as todays_member,

						(select count(*) as mem from ".$prefix."members where status = '2' and paypal_email = '' and alertpay_email = '' and clickbank_email = '' and date_joined BETWEEN '".$today_date."' AND '".$today_date."') as tnormal_member,

						(select count(*) as affiliate from rrp_members where (status = '1' OR status = '2') AND (paypal_email <> '' or alertpay_email<> '' or clickbank_email <> ''   ) and date_joined BETWEEN '".$today_date."' AND '".$today_date."') as taffiliate_member,

						(select count(*) as jv from ".$prefix."members where status = '3' and date_joined BETWEEN '".$today_date."' AND '".$today_date."') as tjv_member,

						(select count(*) as total from ".$prefix."members) as total_member,

						(select count(*) as mem from ".$prefix."members where status = '2' and paypal_email = '' and alertpay_email = '' and clickbank_email = '') as normal_member,

						(select count(*) as affiliate from rrp_members where (status = '1' OR status = '2') AND (paypal_email <> '' or alertpay_email<> '' or clickbank_email <> ''   )) as affiliate_member,

						(select count(*) as jv from ".$prefix."members where status = '3') as jv_member

						from ".$prefix."members";	

		$row_mem = $db->get_a_line($query_mem);

		

	// Product Query

		$query_prod = "select (select count(*) as total from ".$prefix."products) as total,

						(select count(*) as free from ".$prefix."products where prodtype = 'free') as free,

						(select count(*) as paid from ".$prefix."products where prodtype = 'paid') as paid,

						(select count(*) as oto from ".$prefix."products where prodtype = 'OTO') as OTO,

						(select count(*) as cb from ".$prefix."products where prodtype = 'Clickbank') as cb

						from ".$prefix."products";	

		$row_prod = $db->get_a_line($query_prod);

		

		

	//$rightpanel = '<div class="innerright"><table width="100%"><tr><td colspan="2"><strong>Today\'s Referrel:</strong></td></tr><tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><table cellpadding="2" cellspacing="2" width="100%"><tr class="rowodd"><td width="90%">Refer Members:</td><td width="10%">'.$row_ref['cReferers'].'</td></tr></table></td></tr><tr><td>&nbsp;</td></tr></table></div><div style="clear:both">&nbsp;</div>';

		

	$rightpanel = '<div class="innerright"><table width="100%"><tr><td colspan="2"><strong>Today\'s Sales:</strong></td></tr><tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><table cellpadding="2" cellspacing="2" width="100%"><tr class="rowodd"><td width="90%">Sales:</td><td width="10%">'.$row_sales['cSales'].'</td></tr></table></td></tr><tr><td>&nbsp;</td></tr></table></div><div style="clear:both">&nbsp;</div>';	

	

	$rightpanel .= '<div class="innerright"><table width="100%"><tr><td colspan="2"><strong>Today\'s Registered Users:</strong></td></tr><tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><table cellpadding="2" cellspacing="2" width="100%"><tr class="rowodd"><td width="90%">Members:</td><td width="10%">'.$row_mem['tnormal_member'].'</td></tr><tr class="roweven"><td width="90%">Affiliate Members:</td><td width="10%">'.$row_mem['taffiliate_member'].'</td></tr><tr class="rowodd"><td width="90%">JV Members:</td><td width="10%">'.$row_mem['tjv_member'].'</td></tr></table></td></tr><tr><td>&nbsp;</td></tr></table></div><div class="clear">&nbsp;</div>';

	

	$rightpanel .= '<div class="innerright"><table width="100%"><tr><td colspan="2"><strong>Member Statistics:</strong></td></tr><tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><table cellpadding="2" cellspacing="2" width="100%"><tr class="rowodd"><td width="90%">Members:</td><td width="10%">'.$row_mem['normal_member'].'</td></tr><tr class="roweven"><td width="90%">Affiliate Members:</td><td width="10%">'.$row_mem['affiliate_member'].'</td></tr><tr class="rowodd"><td width="90%">JV Members:</td><td width="10%">'.$row_mem['jv_member'].'</td></tr><tr class="rowtotal"><td width="90%">Total Members:</td><td width="10%">'.$row_mem['total_member'].'</td></tr></table></td></tr><tr><td>&nbsp;</td></tr></table></div><div class="clear">&nbsp;</div>';

	

	$rightpanel .= '<div class="innerright"><table width="100%"><tr><td colspan="2"><strong>Product Statistics:</strong></td></tr><tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><table cellpadding="2" cellspacing="2" width="100%"><tr class="rowodd"><td width="90%">Free Products:</td><td width="10%">'.$row_prod['free'].'</td></tr><tr class="roweven"><td width="90%">Paid Products:</td><td width="10%">'.$row_prod['paid'].'</td></tr><tr class="rowodd"><td width="90%">OTO Products:</td><td width="10%">'.$row_prod['OTO'].'</td></tr><tr class="roweven"><td width="90%">Click Bank Products:</td><td width="10%">'.$row_prod['cb'].'</td></tr><tr class="rowtotal"><td width="90%">Total Products:</td><td width="10%">'.$row_prod['total'].'</td></tr></table></td></tr><tr><td>&nbsp;</td></tr></table></div>';	

/*********** Admin Right Bar*************/





// previous logic changing logic to get the front panel from the web directory rather than some server

//$getpanel = get_web_page("http://localhost.rapidresidual/admin_panel/panel.php");

//$panel = $getpanel['content'];

// old logic ends here



//$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);

//echo $returncontent;
?>

<!-- ###################### Error Message Start ###################### -->

<?php echo $msg?>
<!-- ######################  <IFRAME SRC="/graph/index2.php" HEIGHT=300></IFRAME> Error Message End ###################### -->
<!-- ###################### Content Area Start ###################### -->
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
    <div style="width: 68%;float: left;">
        <div class="mainleft"><?php echo $panel?></div>
        <div id="main" class="main-graph"><IFRAME SRC="/graph/" HEIGHT=300 frameborder=0></IFRAME></div> 
    </div>	
        <div class="mainright">
        	<?php echo $rightpanel?>
        </div>
        <div></div>
   	</div>
<div class="content-wrap-bottom"></div>
</div>
<?php include_once("footer.php");

?>