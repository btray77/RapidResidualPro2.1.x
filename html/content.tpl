{$HTMLhead}
<div class="content-1">
	<h1>{$pagename}</h1>
	{$social_media}
	<p>{$main_content}</p>
</div>
{if isset($comments)}
<div class="comments">{$comments}</div>
{/if}
{$HTMLfooter}
