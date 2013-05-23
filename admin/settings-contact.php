<?php
include_once("session.php");
include_once("header.php");
include_once("../common/constant-contact.class.php");
$ccListOBJ = new CC_List(); 
$allLists = $ccListOBJ->getLists();

if (isset($_POST['submit']))
{
	// Parse form data
	$username		= $db->quote(trim($_POST["username"]));
	$password		= $db->quote(trim($_POST["password"]));
    $api_key		= $db->quote(trim($_POST["api_key"]));
	// Update database
	$set	= " username={$username}";
	$set	.= ", password={$password}";
	$set	.= ", api_key={$api_key}";
	
        
        if($id > 0)
        {
            $pid = $db->insert_data_id("update ".$prefix."constant_contact set $set") ;
            $msg = "edit";
        }
        else
        {
	$pid = $db->insert_data_id("insert into ".$prefix."constant_contact set $set") ;
        $msg = "add";
        }
	header("Location: settings-contact.php?msg=$msg");
}

$sql="select * from ".$prefix."constant_contact";
$row=$db->get_a_line($sql);
@extract($row);

switch ($msg) {
    case 'add':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle">Setting is Successfully Added!</div>';
        break;
    case 'edit':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Setting is Successfully Edited!</div>';
        break;
    case 'd':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Setting is Successfully Deleted!</div>';
        break;
    case 'a':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> Setting is successfully Unarchived!</div>';
        break;
    case 'un':
        $Message = '<div class="success"><img src="../images/tick.png" align="absmiddle"> ResSettingponder is successfully Archived!</div>';
        break;
}
?>

<!-- ###################### Error Message Start ###################### -->

<?php echo $Message ?>

<!-- ###################### Error Message End ###################### -->

<!-- ###################### Content Area Start ###################### -->

<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner"><p><strong>Constant Contact Autoresponder</strong></p>

<div class="buttons">
    <a href="constant-contact.php">Go back</a>
    </div>

<div class="formborder">
<form action="" method="post">
    
<table width="95%" align="center" border="0">
	<tr>
	  <td colspan="2" class="tbtext"><strong>Constant Contact Settings</strong></td>
    </tr>
	<tr>
	  <td colspan="2" class="tbtext">&nbsp;</td>
    </tr>
	<tr>
	  <td width="31%"  class="tbtext"><strong>Username *</strong></td>
      <td><input name="username" class="inputbox" type="text" id="username" value="<?php echo trim($username);?>">
      <div class="tool">	
		<a href="" class="tooltip" title="Constant Contact User Name">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
      </td>
	</tr>
	<tr>
	  <td width="31%"  class="tbtext"><strong>Password: *</strong></td>
      <td>
          <input name="password" class="inputbox" type="text" id="password" value="<?php echo trim($password);?>">
      <div class="tool">	
		<a href="" class="tooltip" title="Constant Contact Password ">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
      </td>
	</tr>
        
        <tr>
	  <td width="31%"  class="tbtext"><strong>API Key: *</strong></td>
      <td>
          <input name="api_key" class="inputbox" type="text" id="api_key" value="<?php echo trim($api_key);?>" size="50">
          <br>
          <small>xxxxx-xxx-xxxx-xxxx-xxxxxxxxxx</small>
      <div class="tool">	
		<a href="" class="tooltip" title="Constant Contact Api Key ">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
      </td>
	</tr>
	
	<tr>
	  <td class="tbtext" colspan="2">&nbsp;</td>
    </tr>

	<tr>
		<td colspan="2">
	  	<input type="submit" name="submit" value="Save Settings" class="inputbox">		</td>
	</tr>
	<tr>
	  <td colspan="2">&nbsp;</td>
    </tr>
	
	
	

	
</table>
</form>
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>


<?php include_once("footer.php");?>
