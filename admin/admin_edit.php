<?php
include_once("session.php");
include_once("header.php");

// user access control

if ($_GET['msg'] == 'e'){

	$msg = '<div class="top-message"><img src="../images/crose.png" align="absmiddle">Invalid Email!  Please Check Enter Your Correct Email And Try Again.</div>';

}

if ($_GET['msg'] == 'req'){

	$msg = '<div class="top-message"><img src="../images/crose.png" align="absmiddle">Please Fill all the required fields!</div>';

}
// UAC end}

$GetFile = file("../html/admin/admin_edit.html");
$Content = join("", $GetFile);
$Title = "Edit Extra Admin";

if (isset($_POST['submit']))
{
	if(!empty($_POST["webmaster_email"]) && !empty($_POST["username"]) ) {
	// Parse form data
		if ((valid_email($_POST["webmaster_email"])) == FALSE){
			header("Location: admin_edit.php?msg=e");
		} else{
	// Parse form data
	$username 			= $db->quote($_POST["username"]);
	$webmaster_email 	= $db->quote($_POST["webmaster_email"]);
	$role 	= $db->quote($_POST["role"]);

	// Edit database
	$set = "webmaster_email  = {$webmaster_email}";
	$set .= ", username  = {$username}";
	$set .= ", role  = {$role}";
	$db->insert("update ".$prefix."admin_settings set $set where id = '$id'");
	header("Location:view_all_admins.php?msg=e");
	exit;
		}
	}
	else 
		header("Location: admin_edit.php?msg=req");
	
}

// read data from database
$mysql="select * from ".$prefix."admin_settings where id='$id'";
$rslt=$db->get_a_line($mysql);
@extract($rslt);
switch($role)
{
	case 1:
		$sa='selected';		
	break;
	case 2:
		$a='selected';		
	break;
	case 3:
		$cma='selected';		
	break;
	case 4:
		$pma='selected';		
	break;
	case 5:
		$hma='selected';		
	break;
	case 6:
		$am='selected';		
	break;
        case 7:
		$dm='selected';
	break;
}
	$roles='<select name="role" id="role">';
	switch($obj_pri->getRole()){
	case 1:
		$roles.='<option value="1" '. $sa . ' >Super Administrator</option>
		<option value="2" '. $a . '>Administrator</option>
		<option value="3" '. $cma . '>Content Management Admin</option>
		<option value="4" '. $pma . '>Product Manamement Admin</option>
		<option value="5" '. $hma . '>Help Desk and Member Management</option>
		<option value="6" '. $am . '>Affilate Manager Privileges</option>
		<option value="7" '. $dm . '>Design Admin</option>';
	break;
	case 2:
		$roles.='<option value="2" '. $a . '>Administrator</option>
		<option value="3" '. $cma . '>Content Management Admin</option>
		<option value="4" '. $pma . '>Product Manamement Admin</option>
		<option value="5" '. $hma . '>Help Desk and Member Management</option>
		<option value="6" '. $am . '>Affilate Manager Privileges</option>
		<option value="7" '. $dm . '>Design Admin</option>';
	break;
	}
	$roles.='</select>';



$refrer = "view_all_admins.php";

$Content = preg_replace("/{{add_hide_begin}}(.*?){{add_hide_end}}/s","",$Content);
$Content = preg_replace($Ptn,"$$1",$Content);
echo $Content;
include_once("footer.php");
?>