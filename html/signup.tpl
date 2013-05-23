{$msg}

{$message}

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

<form name="login" id="signup_form" method="post" action="signup.php"  enctype="multipart/form-data" onsubmit="return formCheck(login);">

<input type="hidden" name="page" value="{$page}"> 

<input type=hidden name=randomstring value='{$randomstring}'>

<input type=hidden name=pid value='{$pid}'>



<input name="country" id="country" value=""  type="hidden"  />

<input name="city" value=""  type="hidden"  />

<input name="latitude" value=""  type="hidden"  />

<input name="longitude" value=""  type="hidden"  />

<input name="operating_system" value="{$os}"  type="hidden"  />

<input name="browser" value="{$browser}"  type="hidden"  />



{$hidelink1}

<table width="80%" border="0" align="center" cellpadding="2" cellspacing="5" style="margin-left:70px;">

<tr>

	<td width="22%" align="left" nowrap="nowrap" class="tbtext">First Name:&nbsp;<span class="style1">*</span></td>

	<td width="78%" align="left" class="tbtext"><input type="text" name="firstname" size="40" class="inputbox required alphanum" value="{$fname}" tabindex="1"></td>

</tr>

<tr>

	<td align="left" nowrap="nowrap" class="tbtext">Last Name:&nbsp;&nbsp;<span class="style1">*</span></td>

	<td class="tbtext" align="left"><input type="text" name="lastname" size="40" class="inputbox required alphanum" value="{$lname}" tabindex="2" ></td>

</tr>

<tr>

	<td align="left" nowrap="nowrap" class="tbtext">Email:&nbsp;&nbsp;<span class="style1">*</span></td>

	<td class="tbtext" align="left"><input type="text" name="email" size="40" class="inputbox required email" value="{$email}" tabindex="3"></td>

</tr>

<tr>

	<td align="left" nowrap="nowrap" class="tbtext">User Name:&nbsp;&nbsp;<span class="style1">*</span></td>

	<td class="tbtext" align="left"><input type="text" name="username" size="40" class="inputbox required" value="{$uname}" tabindex="4"></td>

</tr>

<tr>

	<td align="left" nowrap="nowrap" class="tbtext">Password:&nbsp;&nbsp;<span class="style1">*</span></td>

	<td class="tbtext" align="left"><input type="password" name="password" size="40" class="inputbox required " tabindex="5"></td>

</tr>

<tr>

        <td nowrap="nowrap">&nbsp;</td>

	<td class="tbtext" align="left" >

            <img src="captcha/CaptchaSecurityImages.php?width=160&height=55&characters=5" />

      </td>

</tr>



<tr>

  <td align="left" nowrap="nowrap" class="tbtext">&nbsp;Security Code:&nbsp;&nbsp;<span class="style1">*</span></td>

  <td colspan="2" align="left" class="tbtext">

<input type="text" name="captchastring" id="captchastring" size="30" class="inputbox required alphanum" tabindex="6"/></td>

</tr>

<tr>

        <td colspan="2" align="left" ><br/><input type="submit" name="Submit" value=" Signup " class="inputbox"></td>

	</tr>

</table>

{$hidelink2}

{literal}



<script language="JavaScript" src="http://j.maxmind.com/app/geoip.js"></script>

<script type="text/javascript">

        <!--   

        $(document).ready(function(){

         $.validator.addMethod( "alphanum", function(value, element) {

            return this.optional(element) || /^[a-z0-9\-\s\@\#\$\%\^\*\(\)\-\+\=\_~\`\<\>\[\]\{\}\,\.\:\;]+$/i.test(value);

                }, "Error:This symbols are not allowed"

			);

         $.validator.addMethod( "username", function(value, element) {

            return this.optional(element) || /^[a-z0-9\-\_]+$/i.test(value);

                }, "Error:Only Alphanumeric and - and _ is allowed"

		);   

        $("#signup_form").validate();



        }); 

            

        //-->

</script>

{/literal}

</form>