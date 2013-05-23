{$formstart}
	<table width="90%" cellspacing="5" cellpadding="2" border="0" align="center">
    <tbody>
    	<tr>
        	<td width="22%" align="left" class="tbtext">First Name: *</td>
            <td width="78%" align="left" class="tbtext">
            	<input type="hidden" readonly="readonly" value="2" size="40" name="memberid">
                <input readonly="readonly" value="{$firstname}" size="40" name="firstname">
            </td>
        </tr>
        <tr>
        	<td align="left" class="tbtext">Last Name: *</td>
            <td align="left" class="tbtext">
            	<input readonly="readonly" value="{$lastname}" size="40" name="lastname">
            </td>
        </tr>
        <tr>
        	<td align="left" class="tbtext">Email: *</td>
            <td align="left" class="tbtext">
            	<input readonly="readonly" value="{$email}" size="40" name="email">
            </td>
       	</tr>
        <tr>
        	<td align="left" class="tbtext">Phone: *</td>
            <td align="left" class="tbtext">
            	<input readonly="readonly" value="{$phone}" name="phone">
            </td>
        </tr>
        {$domains}
       <tr>
       		<td colspan="2">
            	{$submit_button}
            </td>
       </tr>
     </tbody>
  </table>
{$formend}