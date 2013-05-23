<?php
include_once("session.php");
include_once("header.php");
if (isset($_POST['submit']))
{
	// Parse form data
	$aweber_unit		= $db->quote($_POST["aweber_unit"]);
	$rspname2		= $db->quote($_POST["rspname2"]);
        $trackingtag1		= $db->quote($_POST["trackingtag1"]);
	// Update database
	$set	= " arp_list_id={$aweber_unit}";
	$set	.= ", trackingtag1={$trackingtag1}";
	$set	.= ", rspname2={$rspname2}";
	$set	.= ", rspname='Imnica'";
        if($id > 0)
        {
            $pid = $db->insert_data_id("update ".$prefix."responders set $set where id = $id") ;
            $msg = "edit";
        }
        else
        {
	$pid = $db->insert_data_id("insert into ".$prefix."responders set $set") ;
        $msg = "add";
        }
	header("Location: imnica.php?msg=$msg");
}
if($id > 0)
{
$sql="select * from ".$prefix."responders where id=$id";
$row=$db->get_a_line($sql);
extract($row);
}
?>

<!-- ###################### Error Message Start ###################### -->
<?php echo $msg ?>

<!-- ###################### Error Message End ###################### -->

<!-- ###################### Content Area Start ###################### -->

<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner"><p><strong>Imnica Autoresponder List Management</strong></p>

<div class="buttons">
    <a href="imnica.php">Go back</a>
    </div>

<div class="formborder">
<form action="addimnica.php" method="post">
    <input type="hidden" name="id" value="<?php echo $id ?>">
<table width="95%" align="center" border="0">
	<tr>
	  <td colspan="2" class="tbtext"><strong>Imnica Settings</strong></td>
    </tr>
	<tr>
	  <td colspan="2" class="tbtext">&nbsp;</td>
    </tr>
	<tr>
	  <td width="31%"  class="tbtext"><strong>Responder Name :</strong></td>
      <td><input name="rspname2" class="inputbox" type="text" id="rspname2" value="<?php echo $rspname2;?>">
      <div class="tool">	
		<a href="" class="tooltip" title="Responder Name: What you want to call this autoresponder to remember it here. ">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
      </td>
	</tr>
	<tr>
	  <td width="31%"  class="tbtext"><strong>*Imnica List Id:</strong></td>
      <td><input name="aweber_unit" class="inputbox" type="text" id="aweber_unit" value="<?php echo $arp_list_id?>">
      
      <div class="tool">	
		<a href="" class="tooltip" title="Imnica List ID:The ListID of this autoresponder in Imnica ">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
      </td>
	</tr>
	
        <tr>
	  <td width="31%"  class="tbtext"><strong>*Custom Field ID(username):</strong></td>
      <td><input name="trackingtag1" class="inputbox" type="text" id="trackingtag1" value="<?php echo $trackingtag1?>">
      
      <div class="tool">	
		<a href="" class="tooltip" title="Imnica Custom Field ID: if you enter ID then i will show member name in imnica">
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
	  	<input type="submit" name="submit" value="Add Autoresponder" class="inputbox">		</td>
	</tr>
	<tr>
	  <td colspan="2">&nbsp;</td>
    </tr>
	
	
	<tr>
		<td class="tbtext" colspan="2" align="left">* These fields can be found inside the code for your signup form supplied by your autoresponder service. </td>
	</tr>
	

	
</table>
</form>
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>


<?php include_once("footer.php");?>
