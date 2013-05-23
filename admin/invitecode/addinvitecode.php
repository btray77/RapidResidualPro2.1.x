<?php
include_once("../session.php");
include_once("../header.php");
$sql = "select affiliate_invite_code from ". $prefix ."site_settings";
$row_invite=$db->get_a_line($sql);
	
if($id > 0)
{
	$sql="select * from ".$prefix."invitecode where id=$id";
	$row=$db->get_a_line($sql);
	
	
	
}


if (isset($_POST['submit']))
{
	// Parse form data
	$code		= $_POST["code"];
	$invitefor		= $_POST["invitefor"];
	$id		= $_POST["id"];
	// Update database
	$set	= "   code='$code'";
	$set	.= ", invitefor='$invitefor'";
	if( $id > 0){
	$db->insert("update ".$prefix."invitecode set $set where id=$id") ;
	$msg = "edit";
	header("Location: index.php?msg=$msg");
	}
	else
	{
	$db->insert("insert ".$prefix."invitecode set $set") ;
	$msg = "add";
	header("Location: index.php?msg=$msg");
	}
}

?>



<!-- ###################### Error Message Start ###################### -->
<?php echo $msg;?>

<!-- ###################### Error Message End ###################### -->


<!-- ###################### Content Area Start ###################### -->
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong><?php if( $id > 0) echo 'Edit Coupon Code'; else echo 'Add Coupon Code'; ?></strong></p>
<div class="buttons"><a	href="index.php" >Go Back</a></div>
<div class="formborder"><br />
<br />

<form action="addinvitecode.php" method="post">
<?php if($id > 0) {?>
 <input type="hidden" name="id" value="<?php echo $id;?>"> 
<?php } ?>
<table width="95%" border="0" align="center">
	
	  <td colspan="2" class="tbtext"><strong>Invite Code Settings </strong></td>
    </tr>
	<tr>
	  <td colspan="2" class="titles">&nbsp;</td>
    </tr>
	<tr>
	  <td width="31%"  class="tbtext"><strong>Invite Code:</strong></td>
      <td><input name="code" class="inputbox" type="text" id="code" value="<?php echo $row['code']; ?>">
      <div class="tool">	
		<a href="" class="tooltip" title="Invite Code is code you want your users to type in URL.">
			<img src="../../images/toolTip.png" alt="help"/>		</a>		</div>      </td>
	</tr>
	<tr>
	  <td width="31%"  class="tbtext"><strong>Invite Code for:</strong></td>
      <td>
      <?php echo $row_invite['affiliate_invite_code '];?>
      	<select name="invitefor" id="invitefor">
          <option value="1" <?php if( $row['invitefor']==1) echo 'selected';?>>JV Signup</option>
          <?php  if($row_invite['affiliate_invite_code']==1){?>
          <option value="2" <?php if( $row['invitefor']==2) echo 'selected';?>>Affiliate Signup</option>
          <?php }?>
        </select>
       
      
      <div class="tool">	
		<a href="" class="tooltip" title="Invite code for JV Signups or Affilite Signup.">
			<img src="../../images/toolTip.png" alt="help"/>		</a>		</div>
		</td>
	</tr>
	
	<tr>
	  <td class="tbtext" colspan="2">&nbsp;</td>
    </tr>
	<tr>
		<td colspan="2">
	  	<input type="submit" name="submit" value="Save Invite Code" class="inputbox">		</td>
	</tr>	
</table>
</form>
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>

<?php 
include_once("../footer.php");
?>