<?php
include ("include.php");
include_once("session.php");

$id = $memberid;
if (isset($_POST['submit']))
	{
	// Set variables from form
	$oldpass =  $_POST["old_pass"];
    $newpass = $_POST["new_pass1"];
    $newpass2 = $_POST["new_pass2"];

	// Get password from database
	$mysql="select * from ".$prefix."members where id=$id";
	$rslt=$db->get_a_line($mysql);
	$fPass=$rslt["password"];
	$status	= $rslt['status'];
	
	// Menu cases here
if($status == '1')
{
	$sql_page = "select affiliate_main as member_main, affiliate_menu_id from ".$prefix."misc_pages";
	$row_page = $db->get_a_line($sql_page);
	 // Getting menu alias
 		$qry_menu_alias = "select * from ".$prefix."menus where id = '".$row_page['affiliate_menu_id']."'";
		$row_menu_alias = $db->get_a_line($qry_menu_alias);
		$menus = $objTpl->getPlaceHolders($row_menu_alias['menu_alias']);
}
elseif($status == '2')
{
	$sql_page = "select member_main as member_main, member_menu_id from ".$prefix."misc_pages"; 
	$row_page = $db->get_a_line($sql_page);
	 // Getting menu alias
 		$qry_menu_alias = "select * from ".$prefix."menus where id = '".$row_page['member_menu_id']."'";
		$row_menu_alias = $db->get_a_line($qry_menu_alias);
	$menus = $objTpl->getPlaceHolders($row_menu_alias['menu_alias']);
}
elseif($status == '3')
{
	$sql_page = "select jv_main as member_main, jv_menu_id from ".$prefix."misc_pages";
	$row_page = $db->get_a_line($sql_page);
	// Getting menu alias
 		$qry_menu_alias = "select * from ".$prefix."menus where id = '".$row_page['jv_menu_id']."'";
		$row_menu_alias = $db->get_a_line($qry_menu_alias);
	$menus = $objTpl->getPlaceHolders($row_menu_alias['menu_alias']);
}

	// Encript old password from form with md5
	$oldpass=md5($oldpass);
	if ((!$oldpass) || (!$newpass) || (!$newpass2))
		{
        $Message = "<div class='error'><img src='/images/crose.png' border='0' align='absmiddle'>One or more fields were not filled out.</div>";
		}
	else if ($oldpass == $fPass)
		{
		if ($newpass == $newpass2)
			{
			// Encript new password with md5
			$newpass=md5($newpass);
        	$newpass2=md5($newpass2);

			$mysql="update ".$prefix."members set password={$db->quote($newpass)} where id=$id";
			$db->insert($mysql);
			$Message = "<div class='success'><img src='/images/tick.png' border='0' align='absmiddle'> Password Edited Successfully!</div>";
			}
		elseif($newpass != $newpass2)
			{
			$Message = "<div class='error'><img src='/images/crose.png' border='0' align='absmiddle'> New passwords do not match.</div>";
			}

		}
	elseif ($oldpass != $fPass)
		{
		$Message = "<div class='error'><img src='/images/crose.png' border='0' align='absmiddle'> Password does not match what is in the database.</div>";
		}
	}
	
$output_login = $smarty->fetch('../html/member/change_login.tpl');
$smarty->assign("menus",$menus);
$smarty->assign('pagename','Change Password');
$smarty->assign('main_content',$output_login);
?>