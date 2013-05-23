<?php
include_once("common/config.php");
include ("include.php");

if(empty($randomstring))
	$randomstring=$_REQUEST['randomstring'];

$redirect ="/signup.php?randomstring=$randomstring";	
$pagecontent = "

	<div id='forms'>
	<p align=center>
		<b>Validating your AlerPay purchase, please wait...</b>
		</p>
		 <p align=center>
                    please wait... <span id='time'></span>
                    </p>
                    <p align=center>

                    <div id='loading'align='center'><img src='images/wait.gif'></div>
					<div id='page-content'></div>
		<p align=center>		
		After confirmation you will be taken to the signup form to complete your purchase.<br>
		
		</p>
		</font></p></div>
<script type='text/javascript'>

var varCounter = 1;
var number_of_try =120;        
var ajaxcontent = function(){
     if(varCounter < number_of_try) {
            $('#time').html(varCounter + ' seconds');
            $.ajax({
		        type: 'POST',
		        url: 'confirmpayment-alertpay.php',
		        data : 'random=$randomstring&try='+varCounter ,
		        dataType: 'html',
		        cacheBoolean:false,
		        success: function(data) {
                         
                             if(data == 'completed'){
                               $('#loading').css('display','none');
                               varCounter = number_of_try;
                                window.location.href='$redirect'
                                
                            } 
                            
		           			 else if(varCounter == number_of_try)
                            {
                              $('#page-content').html(data); 
                              $('#page-content').addClass('error'); 
                              $('#loading').css('display','none'); 
                            }
                            
                            
			   
		        },
		        
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



	$smarty->assign('pagename','AlertPay purchase process...');
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