<div class="messages"> 	
    <div class="add"><a href="?page=addmessage&pid={$pid}">Add Message </a></div>
 	{if count($messages) gt 0}
		{foreach item=msg from=$messages}
			<div class="message">
				<div class="postedby">Posted by: {$msg.name}</div>
				<div class="date">{$msg.dates}</div>
				<div class="description">{$msg.message}</div>
				{if $msg.upload_file}
					<div class="file">
                                                <div class="filename">
						{$msg.upload_file}</strong>({$msg.upload_file_size})
                                                </div>
                                               <div class="buttons download">
                                        	<a href="/download/{$msg.download_file}"><img src="/images/admin/download.png" title="Download File" alt="Download File" border="0" align="absmiddle"> Download File</a> 
                                               </div>
						{if $msg.name == 'Administrator'}
						{else}
                                                <div class="buttons delete">
                                                    <a href="?page=messages&pid={$pid}&mode=delfile&mid={$msg.id}" onclick="return confirm('Are you sure to Delete this file?');">
                                                    <img src="/images/crose.png" border="0" alt="Remove file" title="Remove file" align="absmiddle" /> Remove File</a>
                                               </div>
                                            
						{/if}
					</div>
				{/if}
			</div>
		{/foreach}
		<div class="pagers">{$pager}</div>
	{else}
	<div class="products">No Message is given in message board</div>
	{/if}
</div>