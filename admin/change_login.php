<?php
include_once("session.php");
include_once("header.php");
$file=file("../html/admin/change_login.html");
$returncontent=join("",$file);

if (isset($_POST['submit']))
{
	// Set variables from form
	$oldpass =  $_POST["old_pass"];
	$newpass = $_POST["new_pass1"];
	$newpass2 = $_POST["new_pass2"];

	// Get password from database
	$mysql="select * from ".$prefix."admin_settings where id='$admin_id'";
	$rslt=$db->get_a_line($mysql);
	$fPass=$rslt["password"];

	// Encript old password from form with md5
	$oldpass=md5($oldpass);
	if ((!$oldpass) || (!$newpass) || (!$newpass2))
	{
		// One or more fields not filled out in form.
		$msg = '<div class="top-message"><img src="../images/crose.png" align="absmiddle"> <b>One or more fields were not filled out!</b></div>';
	}
	else if ($oldpass == $fPass)
	{
		if ($newpass == $newpass2)
		{
			// Encript new password with md5
			$newpass=md5($newpass);
			$newpass2=$db->quote(md5($newpass2));
			$mysql="update ".$prefix."admin_settings set password='{$newpass}' where id='$admin_id'";
			$db->insert($mysql);
			$msg = '<div class="success" style="color:green;"><img src="../images/tick.png" align="absmiddle"> <b>Admin Password Edited Successfully!</b></div>';
		}
		elseif($newpass != $newpass2)
		{
			// New passwords do not match.
			$msg = '<div class="top-message"><img src="../images/crose.png" align="absmiddle"> <b>New passwords do not match!</b></div>';
		}
	}
	elseif ($oldpass != $fPass)
	{
		// Old password doesn't match database
		$msg = '<div class="top-message"><img src="../images/crose.png" align="absmiddle"> <b> Old Password does not match with database!</b></div>';
	}
}
$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
include_once("footer.php");
?>