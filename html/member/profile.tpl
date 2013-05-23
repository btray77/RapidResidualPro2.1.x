<script type="text/javascript">
function formCheck(formobj){
	// Enter name of mandatory fields
	var fieldRequired = Array("firstname","lastname","email");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("First Name","Last Name","Email");
	// dialog message
	var alertMsg = "Please complete the following fields:\n";
	
	var l_Msg = alertMsg.length;
	
	for (var i = 0; i < fieldRequired.length; i++){
		var obj = formobj.elements[fieldRequired[i]];
		if (obj){
			switch(obj.type){
			case "select-one":
				if (obj.selectedIndex == -1 || obj.options[obj.selectedIndex].text == ""){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			case "select-multiple":
				if (obj.selectedIndex == -1){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			case "text":
			case "textarea":
				if (obj.value == "" || obj.value == null){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			default:
			}
			if (obj.type == undefined){
				var blnchecked = false;
				for (var j = 0; j < obj.length; j++){
					if (obj[j].checked){
						blnchecked = true;
					}
				}
				if (!blnchecked){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
			}
		}
	}

	if (alertMsg.length == l_Msg){
		return true;
	}else{
		alert(alertMsg);
		return false;
	}
}
</script>
<div id="forms">	
{$message}		
<form name="memberact" action="profile.php" method="post" enctype="multipart/form-data" onsubmit="return formCheck(memberact)">
<input type="hidden" name="Act" value="{$act}">
<input type="hidden" name="pid" value="{$id}">
<table width="95%" align="center" border="0" cellpadding="2" cellspacing="5" id="table3">

  <tr>
    <td colspan="2" align="right" ><a href="index.php?page=change_login">Change Password</a></td>
    </tr>
  <tr>
    <td  align="left">&nbsp;</td>
    <td  align="left">&nbsp;</td>
  </tr>
  <tr>
	<td align="left">
	First Name:</td>
	<td align="left"><input type="text" name="firstname" size="60" value="{$firstname}" class="inputbox"></td>
</tr>
<tr>
	<td align="left">
	Last Name:</td>
	<td align="left"><input type="text" name="lastname" size="60" value="{$lastname}" class="inputbox"></td>
</tr>
<tr>
	<td align="left">
	Email Address:</td>
	<td align="left"><input type="text" name="email" size="60" value="{$email}" class="inputbox"></td>
</tr>
{if $paypal_enable == 'yes'}
<tr>
  <td align="left">PayPal Email:</td>
  <td align="left"><input name="paypal_email" type="text" class="inputbox" id="paypal_email" value="{$paypal_email}" size="60" />  </td>
</tr>
{/if}
{if $alertpay_enable == 'yes'}
<tr>
  <td align="left">Alert Pay Email:</td>
  <td align="left"><input name="alertpay_email" type="text" class="inputbox" id="alertpay_email" value="{$alertpay_email}" size="60" />  </td>
</tr>
<tr>
  <td align="left">Alert Pay IPN Security Code:</td>
  <td align="left"><input name="alertpay_ipn_code" type="text" class="inputbox" id="alertpay_ipn_code" value="{$alertpay_ipn_code}" size="60" />  </td>
</tr>

{/if}
<tr>
  <td align="left">ClickBank ID:</td>
  <td align="left"><input name="clickbank_email" type="text" class="inputbox" id="clickbank_email" value="{$clickbank_email}" size="60" />  </td>
</tr>
<tr>
  <td align="left">Postal Address:</td>
  <td align="left"><input name="address_street" type="text" class="inputbox" id="address_street" value="{$address_street}" size="60" />  </td>
</tr>
<tr>
  <td align="left">City:</td>
  <td align="left"><input name="address_city" type="text" class="inputbox" id="address_city" value="{$address_city}" size="60" />  </td>
</tr>
<tr>
  <td align="left">State / County:</td>
  <td align="left"><input name="address_state" type="text" class="inputbox" id="address_state" value="{$address_state}" size="60" />  </td>
</tr>
<tr>
  <td align="left">Postal / Zip Code:</td>
  <td align="left"><input name="address_zipcode" type="text" class="inputbox" id="address_zipcode" value="{$address_zipcode}" size="60" />  </td>
</tr>
<tr>
  <td align="left">Country:</td>
  <td align="left"><input name="address_country" type="text" class="inputbox" id="address_country" value="{$address_country}" size="60" />  </td>
</tr>
<tr>
  <td align="left">Telephone Number:</td>
  <td align="left"><input name="telephone" type="text" class="inputbox" id="telephone" value="{$telephone}" size="60" />  </td>
</tr>
<tr>
  <td align="left">Skype Id:</td>
  <td align="left"><input name="skypeid" type="text" class="inputbox" id="skypeid" value="{$skypeid}" size="60" />  </td>
</tr>
<tr>
  <td colspan="2" align="left" class="logotext style1">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="left"><input type="submit" name="submit" value="Update profile"  class="inputbox"></td>
</tr>
</table>
</form>	
</div>