<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/admin_settings.html");
$returncontent=join("",$file);

if ($_GET['msg'] == 'e'){

	$msg = '<div class="top-message"><img src="../images/crose.png" align="absmiddle">Invalid Email!  Please Check Enter Your Correct Email And Try Again.</div>';
header("Location: admin_settings.php");
}

if ($_GET['msg'] == 'req'){

	$msg = '<div class="top-message"><img src="../images/crose.png" align="absmiddle">Please Fill all the required fields!</div>';
header("Location: admin_settings.php");
}

if (isset($_POST['submit']))
{


	if(!empty($_POST["fEmail"]) && !empty($_POST["fUser"])) {
			
		if ((valid_email($_POST["fEmail"])) == FALSE){
			//echo "stuff"; die();
			header("Location: admin_settings.php?msg=e");
		}else{
			// Parse form data
			$fUser 				= $db->quote($_POST["fUser"]);
			$fEmail 			= $db->quote($_POST["fEmail"]);
		  
				
			// Update database
			$set	.= " username={$fUser}";
			$set	.= ", webmaster_email={$fEmail}";
			$mysql="update ".$prefix."admin_settings set $set where id='$admin_id'";
			$db->insert($mysql);
			$msg = '<div class="success"><img src="../images/tick.png" align="absmiddle">Settings Edited Successfully!</div>';
			header("Location: admin_settings.php");
		}

	} else {
	  
	  
		header("Location: admin_settings.php?msg=req");
	  
	}
}


// read data from database
$mysql="select * from ".$prefix."admin_settings where id='$admin_id'";
$rslt=$db->get_a_line($mysql);
@extract($rslt);

$fUser 				= $rslt["username"];
$fEmail 			= $rslt["webmaster_email"];

// show page
$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>