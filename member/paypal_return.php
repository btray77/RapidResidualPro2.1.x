<?php
include ("include.php");
include_once("session.php");
$pshort = $_POST['pshort'];
$echeck = $_POST['echeck'];
$custom = $_POST['custom'];
$str = explode('|', $custom);
$randomstring = $str[0];

$item_number = $str[3];
$responder = $_REQUEST["responder"];
if (!empty($_REQUEST['randomstring']))
    $randomstring = $_REQUEST['randomstring'];
if (empty($item_number)) {
    $product_sql = "select item_number from " . $prefix . "orders where randomstring='$randomstring'";
    $row_product = $db->get_a_line($product_sql);
    $item_number = $row_product['item_number'];
}
$q = "select * from " . $prefix . "products where id='$item_number'";
$s = $db->get_a_line($q);
@extract($s);
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
                    <div id='loading'align='center'><img src='/images/wait.gif'></div>
                    <div id='page-content'></div>
                    </p>
                    <p align=center>		
                    After confirmation you will be taken to the signup form to complete your purchase.<br>
                    (This page will refresh every 1 seconds until PayPal provides payment confirmation.)
                    </p>
                    </font>
            </td></tr></table>
    </td></tr></table>
</div>
<script type='text/javascript'>
var echeck = '<br /><br /><p align=center id=payment_content><table align=center width=600 border=1 cellpadding=10><tr><td><font face=verdana>&nbsp;</font><p align=justify><font color=red face=verdana><b>NOTICE:</b></font><font face=verdana> It looks like you paid with an eCheck or bank draft. We can not complete your purchase until your payment clears through PayPal. Your download will become available to you the instant your payment clears through PayPal. This should take 3 to 4 days.</font></p><p align=center><font face=verdana><a href=index.php>Click Here To Continue To The Member Area</a> </font></p></td></tr></table><br /><br /></p>';
var varCounter = 1;
var number_of_try =120;        
var ajaxcontent = function(){
     if(varCounter < number_of_try) {
            $('#time').html(varCounter + ' seconds');
            $.ajax({
		        type: 'POST',
		        url: 'confirmpayment.php',
		        data : 'random=$randomstring&try='+varCounter+'&item_number='+$item_number ,
		        dataType: 'html',
		        cacheBoolean:false,
		        success: function(data) {
                             if(data == 1){
						  	  	$('#loading').css('display','none');
                                varCounter = number_of_try;
								window.location.href='index.php?pshort=$pshort'
                             } 
						     else if(data == -1)
							 {
								$('#payment_content').html('');
								$('#loading').css('display','none');
								$('#payment_content').html(echeck); 
							 }
		           		   else if(varCounter == number_of_try && data != 1)
                            {
                              $('#page-content').html(data); 
                              $('#page-content').addClass('error'); 
                              $('#loading').css('display','none'); 
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
$hotspots = $objTpl->getHotspotList();
$placeHolders = $objTpl->getPlaceHolders($hotspots);
$i = 0;
foreach ($placeHolders as $items) {
    $smarty->assign("$hotspots[$i]", "$items");
    $i++;
}
$smarty->assign("menus", '');
$smarty->assign('current_date', '');
$smarty->assign('right_panel', '');
$smarty->assign('sidebar','');
$smarty->assign('error', '');
$smarty->assign('content', $pagecontent);
$smarty->display($FILEPATH . '/index.html');
?>