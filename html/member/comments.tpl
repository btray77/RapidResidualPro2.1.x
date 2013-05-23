    <h3>Comments</h3>
    {if count($comments) gt 0}
      {foreach item=comment2 from=$comments}
    	<a name="comment_{$comment2.id}"></a>
        <div class="author"><a href="http://{$comment2.display_url}" target="_blank" rel="nofollow"> {$comment2.display_name}</a> </div>
        <div class="date">{$comment2.date}</div>
        <div class="posts">{$comment2.comment|nl2br}</div>
        
            {if count($comment2.reply) gt 0}
			<div class="replys">
				{foreach item=replys from=$comment2.reply}
					<div class="title"> {$replys.title}</div> 
                                         <div class="date">{$replys.postedon}</div>
					 <div class"discription">{$replys.description}</div>
                                         <div class="seperator"></div> 
				{/foreach}
			</div>    
		
		{/if}
       <div class="seperator"></div> 
      {/foreach}
   
    {else}
    <div class="comment">No comments found</div>
    {/if}

<div class="comment-form">
<a name="comm_pos"></a>
<h3>Post a comment</h3>
<div style="width:100%;">
	{$error}
</div>
<form action="/member/{$ptype}.php?page={$page}#comment_{$max_id}" id="new_comment" method="post">
<input type="hidden" name="page" value='{$page}'>
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="5">
<tr>
<td>
Enter your name:<br>
<input id="display_name" name="display_name" size="30" type="text" value="{$display_name}" /></td>
</tr>
<tr>
<td>
Enter your website address:<br>
<input id="display_url" name="display_url" size="30" type="text" value="{$display_url}" /></td>
</tr>
<tr>
<td>
Share your comments:<br>
<textarea cols="50" id="comment" name="comment" rows="5">{$post_comments}</textarea></td>
</tr>
<tr>
  <td>
  	<!-- <img src="/common/captcha/captcha.php?.png" alt="CAPTCHA" /> -->
    <img src="/captcha/CaptchaSecurityImages.php?width=160&height=55&characters=5" alt="CAPTCHA" />
   </td>
</tr>
<tr>
  <td><input type="text" name="captchastring" size="30" /></td>
</tr>
<tr>
<td align="left"><input type="submit" name="Submit" value="Share"/></td>
</tr>
</table>
</form>

</div>