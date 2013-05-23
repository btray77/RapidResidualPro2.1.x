<a name="blog_anchor"></a>
<div id="blog">
	{if $blogs_detail eq '1'}
	<h2>{$pagename}</h2>
	<div class="date">{$date_added}</div>
	<div class="article">{$pcontent}</div>
	{else}
	{foreach item=blog from=$blogs}
	<div class="posts">
		<h2>{$blog.pagename}</h2>
		<div class="date">{$blog.date_added}</div>
		<div class="article">{$blog.description}</div>
		<div class="comment">{$blog.comments}</div>
		<div class="readmore"><a href="?page={$blog.filename}">Read more</a></div>
		<div class="seperator"></div>
	</div>
	{/foreach}
	<div class="pager">{$pager}</div>
	{/if}
</div>



