{literal}
<style type="text/css">
<!--
.style1 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
{/literal}
<div class="">{$index_page}</div>
{$Message}
<form name="login" id="login" action="jvsign.php" method="post" enctype="multipart/form-data" onSubmit="return formCheck(login);">
<input type="hidden" name="pid" value='{$pid}'>
<input type="hidden" name="rand" value='{$rand}'>
<input type="hidden" name="code" value='{$code}'>


{$hidelink1}

<table width="90%" border="0" align="center" cellpadding="2" cellspacing="5">
<tr>
	<td align="right" nowrap class="logotext" nowarp>First Name:&nbsp;<span style='color:red'>*</span></td>
	<td class="logotext" align="left"><input type="text" name="firstname" size="40" class="inputbox required alphanum"></td>
</tr>
<tr>
	<td align="right" nowrap class="logotext" nowarp>Last Name:&nbsp;<span style='color:red'>*</span></td>
	<td class="logotext" align="left"><input type="text" name="lastname" size="40" class="inputbox required alphanum"></td>

</tr>
<tr>
	<td align="right" nowrap class="logotext">Email:&nbsp;<span style='color:red'>*</span></td>
	<td class="logotext" align="left"><input type="text" name="email" size="40" class="inputbox required email"></td>
</tr>
<tr>
	<td align="right" nowrap class="logotext">User Name:&nbsp;<span style='color:red'>*</span></td>
	<td class="logotext" align="left"><input type="text" name="username" size="40" class="inputbox required username"></td>
</tr>
<tr>
	<td align="right" nowrap class="logotext">Password:&nbsp;<span style='color:red'>*</span></td>
	<td class="logotext" align="left"><input type="password" name="password" size="40" class="inputbox required"></td>
</tr>
<tr>
	<td align="right" nowrap class="logotext">PayPal Email:&nbsp;</td>
	<td class="logotext" align="left"><input name="paypal_email" type="text" class="inputbox email" id="paypal_email" size="40"></td>
</tr>
<tr>
<td nowrap>&nbsp;</td>
<td><img src="/captcha/CaptchaSecurityImages.php?width=160&height=55&characters=5" /></td>
</tr>


<tr>
<td align="right" nowrap class="logotext">Security Code:<span style='color:red'>*</span></td>
<td><input type="text" name="captchastring" size="30" class="required alphanum"/></td>
</tr>
<tr>
<td align="right" nowrap class="logotext"></td>
<td>
{$pagename}
<div style="border: 1px solid #C4C4C4;   float: left;   height: 181px;   overflow-y: scroll;   padding: 5px;  width: 99%;">{$content}</div>
I agree to all Terms and Conditions?  &nbsp;<input type="checkbox" name="agree" class="required"/> </td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td  align="left" class="cmdtext"><input type="submit" name="Submit" value="Signup" class="inputbox"></td>
</tr>
</table>

{$hidelink2}

</form>

{literal}
<script type="text/javascript"> 
	<!--   
        $(document).ready(function(){
         $.validator.addMethod( "alphanum", function(value, element) {
           return this.optional(element) || /^[a-z0-9\-\s\@\#\$\%\^\*\(\)\-\+\=\_~\`\<\>\[\]\{\}\,\.\:\;]+$/i.test(value);
            },"Error:This symbols are not allowed"
		);
        $.validator.addMethod( "username", function(value, element) {
            return this.optional(element) || /^[a-z0-9\-\_]+$/i.test(value);
                }, "Error:Only Alphanumeric and - and _ is allowed"
		);      
        $("#login").validate();
        }); 
            
        //-->
</script>
{/literal}