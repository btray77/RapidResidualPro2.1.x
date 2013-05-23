<?php include_once("session.php");
include_once("header.php");



if(is_numeric($_GET['cid']))
{
	$cid = (int) $_GET['cid'];
}
else 
	$cid=0;

if(is_numeric($_GET['id']))
{
	$id = (int) $_GET['id'];
}
else 
	$id=0;	
	
$sql='select * from ' . $prefix . 'comments where id = ' . $cid .';';
$row=$db->get_a_line($sql);


if($id > 0)
{
$sql_reply='select * from ' . $prefix . 'comments_reply where id = ' . $id .';';
$Replyform = $db->get_a_line($sql_reply);	
}

#################### ACTION ##########################

switch($_POST['action']){
		
	case 'add':
	 $msg=addreply(0,$prefix,$db,$_POST);
	 header("location:comment_reply.php?cid=$cid&msg=$msg");
	 exit();
	break;
	case 'edit':
		$id= (int) $_POST['id'];
		$msg=addreply($id,$prefix,$db,$_POST);
		header("location:comment_reply.php?cid=$cid&msg=$msg");
		exit();
	break;
	case 'del':
	  if($obj_pri->canDelete($pageurl))	{
		$id= (int) $_POST['id'];
		$msg=deletereply($id,$prefix,$db,$_REQUEST['check']);
		header("location:comment_reply.php?cid=$cid&msg=$msg");
		exit();
	  }
		
	break;
	case 'publish':
		$id= (int) $_POST['id'];
		$msg=publishedreply($id,$prefix,$db,$_REQUEST['check'],1);
		header("location:comment_reply.php?cid=$cid&msg=$msg");
		exit();
	break;
	case 'unpublish':
		$id= (int) $_POST['id'];
		$msg=publishedreply($id,$prefix,$db,$_REQUEST['check'],0);
		header("location:comment_reply.php?cid=$cid&msg=$msg");
		exit();
	break;
				
}
############# OPERATION ####################
function addreply($id,$prefix,$db,$_POST)
{
	$title=addslashes(trim($_POST['title']));
	$description=addslashes(trim($_POST['description']));
	$cid=addslashes(trim($_POST['cid']));
	
	if($id > 0 )
	{ 
		$sql="update  ". $prefix ."comments_reply set title='$title',description='$description',published='1',comment_id='$cid' where id='$id'";
		$db->insert($sql);	
		return 'e'; 
	}
	else 
	{
		$sql="insert  ". $prefix ."comments_reply set title='$title',description='$description',published='1',comment_id='$cid'";
		$db->insert($sql);	
		return 'a'; 	
	}
	
}

function deletereply($id,$prefix,$db,$selected)
{
	
	if(count($selected)> 0):
	$str='';
	foreach($selected as $sel)
	{	
		$str.=$sel.',';
	}
	$str=substr($str,0,-1);
	
	$sql="delete from ".$prefix."comments_reply where id in($str)";
	
	$db->insert("$sql");

	$msg = "d";
	endif;
	return $msg;
}

function publishedreply($id,$prefix,$db,$selected,$state)
{
	
	if(count($selected)> 0):
	$str='';
	foreach($selected as $sel)
	{	
		$str.=$sel.',';
	}
	$str=substr($str,0,-1);
	if($state==0)
	{
		$sql="update ".$prefix."comments_reply set published=0 where id in($str)";
		$db->insert("$sql");
		$msg = "up";
	}
	else
	{	$sql="update ".$prefix."comments_reply set published=1 where id in($str)";
		$db->insert("$sql");
		$msg = "p";
	}
	endif;
	
	return $msg;
}
################## MESSAGE ##################
if($msg == "d")
{
	 $Message ='<div class="success"><img src="../images/tick.png" align="absmiddle"> Reply is successfully deleted</div>';
	
}
else if($msg == "a")
{
	$Message ='<div class="success"><img src="../images/tick.png" align="absmiddle"> Reply is successfully added</div>';
	
}
else if($msg == "e")
{
	$Message ='<div class="success"><img src="../images/tick.png" align="absmiddle"> Reply is successfully edit</div>';
	
}
else if($msg == "p")
{
	$Message ='<div class="success"><img src="../images/tick.png" align="absmiddle"> Reply is successfully published</div>';
	
}
else if($msg == "up")
{
	$Message ='<div class="success"><img src="../images/tick.png" align="absmiddle"> Reply is successfully unpublished</div>';
	
}
?>
<?php echo $Message;?>
<script>
function confirmdelete()
{
	document.getElementById('action').value = 'del';
	if(confirm('Are you sure you want to delete this comment?'))
		{
			document.form.submit();
			return true;
		}
	else return false;
}

checked=false;
function checkedAll () {
	
	var aa= document.getElementById('form');
	 if (checked == false)
          {
           checked = true
          }
        else
          {
          checked = false
          }
	for (var i =0; i < aa.elements.length; i++) 
	{
	 	aa.elements[i].checked = checked;
	}
 }

</script>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong>Comments Reply</strong></p>
<div class="buttons">
<?php if(!empty($row['filename'])){ ?>
	<a href="comment_moderation.php?pageid=<?php echo stripslashes($row['page']);?>&amp;filename=<?php echo stripslashes($row['filename']);?>&amp;type=<?php echo stripslashes($row['type']);?>">Back to Comments</a>
<?php }else{ ?>
	<a href="comment_moderation.php?pageid=<?php echo stripslashes($row['page']);?>&amp;type=<?php echo stripslashes($row['type']);?>">Back to Comments</a>
<?php }?>	
</div>

<div style="margin-bottom:14px;float:left;" >
<div class="blog-comment"><?php echo stripslashes($row['comment']);?></div>
<div class="date">Post by: <?php echo stripslashes($row['display_name']);?>  on <?php echo $row['date']?></div>
</div>
<?php 
$sql_reply='select * from ' . $prefix . 'comments_reply where comment_id = ' . $row['id'] .';';
$gerReply = $db->get_rsltset($sql_reply);

if(count($gerReply)>0){ ?>
<form action="comment_reply.php?cid=<?php echo $cid?>" method="post" name="form" id="form">
<div class="header">
	<div style="float:left">
	<input type="checkbox" name="allbox" id="allbox" onclick="checkedAll()"/>Select All
	</div>
	<div style="float:right">
	<div style="margin-top:1px;margin-right:5px; width: auto;float:left">
		   Published
		   <input type="image" src="../images/admin/published.png" title="Published selected reply" name="action"  align="absmiddle"  value="publish"  onclick="this.form.action.value = this.value" >
		  
		   Unpublished
		   <input type="image" src="../images/admin/unpublished.png" title="Unpublished selected reply" name="action"  align="absmiddle"  value="unpublish"  onclick="this.form.action.value = this.value"  >
		   <?php if($obj_pri->canDelete($pageurl))	{?>	
		   Delete
		   <input type="image" src="../images/admin/delete.gif" title="Delete selected reply" name="action"  align="absmiddle"  value="d"  onclick=" return confirmdelete()"  >
		   <?php }?>
		   
		   
		   <input type="hidden" name="action" id="action" value="" />		
		</div>
	</div>
</div>


<?php 
$i=0;
foreach($gerReply as $reply) { 
		if($reply["postedon"])
		{
			$postedon= date("M d, Y g:i a",strtotime($reply["postedon"]));
		}else {
			$postedon= "-";
		}
?>		
<div class="reply">
	
	<div class="blog-name">
	 <input type="checkbox" id="check" name="check[]" value="<?php echo $reply["id"];?>" >
	<a href="comment_reply.php?cid=<?php echo $cid?>&action=edit&id=<?php echo $reply['id'] ?>#reply"><?php echo stripslashes($reply['title']); ?></a>
	<?php if($reply['published']==1)
		{
		$img="published.png";
		$alt="Publish";
		}
		else 
		{
			$img="unpublished.png";
			$alt="Unpublish";
		}
		?>
				 	<img src="../images/admin/<?php echo $img?>" border="0" alt="<?php echo $alt?> <?php echo stripslashes($reply['title']); ?>"
				 	title="<?php echo $alt?> <?php echo stripslashes($reply['title']); ?>" align="absmiddle">
	</div>
	<div class="date">Posted on: <?php echo $postedon?></div>
	<div class="reply-description"><?php echo stripslashes($reply['description']) ?></div>
	<div class="seperator"></div>
</div>
<?php } }?>
</form>
<a id="reply"></a>
<div class="replyform">
	<fieldset>
		<legend>Reply</legend>
		<form action="comment_reply.php?cid=<?php echo $cid?>" method="post" name="replyform" >
		<table width="100%" cellpadding="5" cellspacing="0">
			<tr>
				<td>Title:</td>
				<td><input type="text" name="title" value="<?php echo stripslashes($Replyform['title']);?>" >
				 	
				 </td>
			</tr>
			<tr>
				<td>Description:</td>
				<td><textarea cols="40" rows="5" name="description"><?php echo stripslashes($Replyform['description']);?></textarea></td>
			</tr>
			
			<tr>
				<td colspan="2">
				
				<input type="hidden" value="<?php echo $cid?>" name="cid">
				<?php if(!empty($_REQUEST['action']) && is_numeric($_REQUEST['id'])){?>
				<input type="submit" name="submit" value="Save">
				<input type="hidden"" value="edit" name="action">
				<input type="hidden" value="<?php echo $_REQUEST['id']?>" name="id">
				<?php } else {?>
				<input type="hidden" value="add" name="action">
				<input type="submit" name="submit" value="Save">
				<?php }?>
				</td>
				
			</tr>
		
		</table>
		
	</fieldset>
</div>



</div>
<div class="content-wrap-bottom"></div>
</div>
<?php include_once("footer.php");?>