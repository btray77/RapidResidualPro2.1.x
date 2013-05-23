<?php
include_once '../../common/config.php';
include_once '../../common/database.class.php';
include_once("../../common/common.class.php");
$db = new database();
$common = new common();

    $sql="SELECT distinct c.subject,c.body,m.email,m.firstname,m.id,m.lastname,c.group_id,c.id as content_id

    FROM ".$prefix."email_content c,".$prefix."members m ,".$prefix."email_blaster_group_members gm

    where m.id=gm.member_id and c.group_id=gm.group_id and c.published=1 and gm.mail_status=0";





$results = $db->get_rsltset($sql);



foreach($results as $row)

{

	$subject=stripslashes($row['subject']);

	$first_name=$row['firstname'];

	$last_name=$row['lastname'];

	$member_id=$row['id'];

	$content_id=$row['content_id'];

	$email=$row['email'];

	$message=stripslashes($row['body']);

	$message = preg_replace("/{{(.*?)}}/e","$$1",$message);

	

	if($common->sendemail('', '', $email, $subject, $message, ''))

	 {

                $message=$row['body'];

		 $db->insert("INSERT ".$prefix."email_log SET `subject`='$subject',message='$message',member_id='$member_id'");

         $db->insert("UPDATE ".$prefix."email_blaster_group_members SET `mail_status`=1 where member_id=$member_id and group_id =".$row['group_id']);

	 }

}

echo 'done';





?>