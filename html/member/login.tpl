{$error}
<center>
<font class="tbtext">Enter your user  name and password below to access the member area.</font>

<div id="loginbox">

<form action='login.php' method="post" name="login">

<input name="country" value="" type="hidden" />
                <input name="city" value=""  type="hidden"  />
                <input name="latitude" value=""  type="hidden"  />
                <input name="longitude" value=""  type="hidden"  />
                <input name="operating_system" value="{$os}"  type="hidden"  />
                <input name="browser" value="{$browser}"  type="hidden"  />
                <input name="destination" value="{$destination}"  type="hidden"  />

<input type=hidden name=product value='{$product}'>
<input type=hidden name=coupon value='{$coupon}'>
<table width="50%" border="0" cellpadding="3" cellspacing="5" class="maintbs" align="center">
<tr>
	<th colspan="3" class="tbtext" align="center">Member Details</th>
</tr>
<tr>
	<td align="right" width="40%" class="logotext">User Name: </td>
	<td width="60%" align="left" class="logotext"><input type="text" name="dUser"  value="{$dUser}" class="inputbox"></td>
</tr>
<tr>
	<td class="logotext" align="right" width="40%">Password:</td>
	<td width="60%" align="left" class="logotext"><input type="password" name="dPass"  value="{$dPass}" class="inputbox"></td>
</tr>

<tr>
	<td colspan="2" align="center" class="logotext"><input type="submit" name="submit" value="{$button}" class="inputbox"></td>
</tr>
<tr>
	<td colspan="2" align="center" class="logotext">
	<a href="forgot_password.php">Forgot Password</a>	</td>
</tr>
</table>
</form>
</div>

{literal}
<script src="/common/newLayout/jquery/jquery.js"></script>
<script language="JavaScript" src="http://j.maxmind.com/app/geoip.js"></script>
<script>
$(document).ready(function (){
	
$('input[name=country]')[0].value = geoip_country_name();
$('input[name=city]')[0].value = geoip_city();
$('input[name=latitude]')[0].value = geoip_latitude();
$('input[name=longitude]')[0].value = geoip_longitude();


$('input[type=submit]')[0].disabled = false;
	});
</script>
{/literal}