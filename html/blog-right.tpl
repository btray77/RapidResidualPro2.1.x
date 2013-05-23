<h2>Recent Posting</h2>
<ul>
{foreach item=blog from=$recent}
		<li><a href="?page={$blog.filename}#blog_anchor">{$blog.pagename}</a></li>
	{/foreach}
</ul>	
<h2>Archive Posts</h2>
<ul>
	{foreach item=archive from=$archives}
		<li><a href="?archive={$archive.link}#blog_anchor">{$archive.title}</a></li>
	{/foreach}
</ul>