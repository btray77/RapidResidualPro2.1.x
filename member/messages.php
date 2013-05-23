<?php 
include ("include.php");
include_once("session.php");

$SelectedContent = $Output[1];
$pid = $_GET['pid'];

$q = "select * from ".$prefix."products where pshort='$pid'";
$v = $db->get_a_line($q);
$prod 	= $v['id'];
$coaching = $v['coaching'];

$sql = "update ".$prefix."member_messages set mchecked='1' where mid='$memberid' && product='$pid'";
$db->insert($sql);


$q = "select prot_down from ".$prefix."site_settings";
$r = $db->get_a_line($q);
@extract($r);

// Delete files of messages starts
	if(isset($_GET['mode']) && $_GET['mode'] == 'delfile'){
		$message_id = trim($_GET['mid']);
		
		$sel_query = "Select * from ".$prefix."member_messages Where id = '".$message_id."'";
		$msg_records = $db->get_a_line($sel_query);
		$file_name = $_SERVER['DOCUMENT_ROOT'].$prot_down.$msg_records['upload_file'];
		unlink($file_name);
		
		$del_query = "update ".$prefix."member_messages
						set upload_file = ''
						where id = '".$message_id."'";	
		$db->insert($del_query);
		header("Location: index.php?page=messages&pid=$pid");	
		exit();
	}
// Delete files of messages ends


$q = "select count(*) as cnt from ".$prefix."member_products where member_id='$memberid' && product_id='$prod' && refunded ='0'";
$r = $db->get_a_line($q);
if($r[cnt] == 0 OR $coaching =='no')
{
	$call = "error.php?error=4";
	header("Location: ".$call);
	exit;
}

$q = "select * from ".$prefix."members where id='$memberid'";
$r2 = $db->get_a_line($q);
$firstname = $r2['firstname'];
$lastname = $r2['lastname'];


########## pagination ###########
$sql = "select count(*) as total from ".$prefix."member_messages where mid='$memberid' && vis='0' && product='$pid'";
$row_total = $db->get_a_line($sql);
if($pageno)
$start = ($pageno - 1) * $limit; 			//first item to display on this page
else
{
$start = 0;
$pageno = 0;
}	
$pager=$common->pagiation_simple('index.php?page=messages&pid='.$pid,$limit,$row_total['total'],$pageno,$start,'');
########## pagination ###########


$ToReplace = "";
$sql="select * from ".$prefix."member_messages where mid='$memberid' && vis='0' && product='$pid' order by id desc limit $start, $limit";
$row_message = $db->get_rsltset($sql);
$i=0;
function format_bytesss($bytes) {
	   if ($bytes < 1024) return $bytes.' B';
	   elseif ($bytes < 1048576) return round($bytes / 1024, 2).' KB';
	   elseif ($bytes < 1073741824) return round($bytes / 1048576, 2).' MB';
	   elseif ($bytes < 1099511627776) return round($bytes / 1073741824, 2).' GB';
	   else return round($bytes / 1099511627776, 2).' TB';
}

if(count($row_message) > 0){
foreach($row_message as $msg)
{
	$admin          = $msg['admin'];
	$message        = $msg['message'];
	$message        = str_replace("\r\n", "<br>", $message);
	$message        =$common->mywordwrap($message, 60);
	$mid            = $msg['id'];
	$upload_file    = $msg['upload_file'];
	
	//$upload_file_size = $msg['upload_file_size'];

	if ($admin == '1'){
		$name = "Administrator";
	}else{
		$name = $firstname .' ' . $lastname;
	}

	//$date = date('F j, Y', strtotime($date));

	$messageContent[$i]['name'] = $name;
	$messageContent[$i]['id'] = $mid;
	$messageContent[$i]['message'] = $message;
	$messageContent[$i]['upload_file'] =  $upload_file;
	$messageContent[$i]['download_file'] = $upload_file;
	 $file_size = filesize($_SERVER['DOCUMENT_ROOT']. $prot_down.$upload_file);
	$messageContent[$i]['upload_file_size']  = format_bytesss($file_size);
	$messageContent[$i]['dates'] = $msg[date_added];

	$i++;
}
}

	$smarty->assign('pid',$pid);
	$smarty->assign('pager',$pager);
	$smarty->assign('messages',$messageContent);
	$output_login = $smarty->fetch('../html/member/messages.tpl');
	$smarty->assign('pagename','MessageBoard');
	$smarty->assign('main_content',$output_login);

	?>