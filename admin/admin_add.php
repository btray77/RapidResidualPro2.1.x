<?php
include_once("session.php");
include_once("header.php");

if ($_GET['msg'] == 'e'){

	$msg = '<div class="top-message"><img src="../images/crose.png" align="absmiddle">Invalid Email!  Please Check Enter Your Correct Email And Try Again.</div>';

}

if ($_GET['msg'] == 'req'){

	$msg = '<div class="top-message"><img src="../images/crose.png" align="absmiddle">Please Fill all the required fields!</div>';

}
// UAC end

$file=file("../html/admin/admin_add.html");
$returncontent=join("",$file);

if (isset($_POST['submit']))
{
	if(!empty($_POST["email"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
	// Parse form data
		if ((valid_email($_POST["email"])) == FALSE){
			header("Location: admin_add.php?msg=e");
		} else{
			$email			= $db->quote($_POST["email"]);
			$username 		= $db->quote($_POST["username"]);
			$password 		= $db->quote(md5($_POST["password"]));
			$role 		= $db->quote($_POST["role"]);
		
			// Enter member data into database
			$set = "webmaster_email  = {$email},";
			$set .= "username  = {$username},";
			$set .= "password  = {$password},";
			$set .= "role  = {$role}";
			$mid = $db->insert_data_id("insert into ".$prefix."admin_settings set $set");
		
			header("Location: view_all_admins.php?msg=a");
		}
	}
	else 
		header("Location: admin_add.php?msg=req");
}
$refrer = $_SERVER['HTTP_REFERER'];

$role='<select name="role" id="role">';
switch($obj_pri->getRole()){
	case 1:
		$role.='
			<option value="1">Super Administrator</option>
			<option value="2">Administrator</option>
			<option value="3">Content Management Admin</option>
			<option value="4">Product Manamement Admin</option>
			<option value="5">Help Desk and Member Management</option>
			<option value="6">Affilate Manager Privileges</option>
            <option value="7">Design Admin</option>';
	break;
	case 2:
		$role.='
			<option value="2">Administrator</option>
			<option value="3">Content Management Admin</option>
			<option value="4">Product Manamement Admin</option>
			<option value="5">Help Desk and Member Management</option>
			<option value="6">Affilate Manager Privileges</option>
            <option value="7">Design Admin</option>';
	break;	
	}
$role.='</select>';




$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>