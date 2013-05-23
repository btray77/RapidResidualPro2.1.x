<h3>Comments</h3>
<!--{if count($comments) gt 0}
{foreach item=comment from=$comments}
<a name="comment_{$comment.id}"></a>
<div class="author">
	<a href="http://{$comment.display_url}" target="_blank">
	    {$comment.display_name}
    </a>    
</div>
<div class="date">{$comment.date}</div>
<div class="comment">{$comment.comment|nl2br}</div>
<div class="url"><a href="{$commnt.display_url}" rel="nofollow">{$comment.display_url}</a></div>
{if $comment.title}	
	<div class="url" style="padding-left:40px;">
    	<strong>Posted on {$comment.postedon}</strong><br />
	   	{$comment.title}: {$comment.description}
    </div>    
{/if}

{/foreach}
{else}
<div class="comment">No comments found</div>
{/if}
-->
{if count($comments) gt 0}
{foreach item=comment from=$comments}
    {if count($comment.comment) gt 0}
    {foreach item=comment2 from=$comment}
    	<a name="comment_{$comment2.id}"></a>
        <div class="author">
            <a href="http://{$comment2.display_url}" target="_blank">
                {$comment2.display_name}
            </a>    
        </div>
        <div class="date">{$comment2.date}</div>
        <div class="comment">{$comment2.comment|nl2br}</div>
        <!-- <div class="url"><a href="http://{$comment2.display_url}" rel="nofollow">{$comment2.display_url}</a></div> -->
        <div style='clear:both;'></div>
    {/foreach}
    {/if}
    {if count($comment.reply) gt 0}
        {foreach item=replys from=$comment.reply}
            <div class="replys">
                <strong>Posted on {$replys.postedon}</strong><br />
                {$replys.title}: {$replys.description}
            </div>    
        {/foreach}
    {/if}
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
<form action="/member/{$ptype}.php?content={$page}&tcontent1={$campaign}#comment_{$max_id}" id="new_comment" method="post">
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
<input id="display_url" name="display_url" size="30" type="text" value="{$display_url}" /> <strong>e.g www.examplesite.com</strong></td>
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
<td align="left">
	<input type="hidden" name="campaign" id="campaign" value="{$campaign}" />
	<input type="submit" name="Submit" value="Share"/>
</td>
</tr>
</table>
</form>

</div>