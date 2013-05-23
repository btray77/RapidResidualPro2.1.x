<?php

 			require_once 'config/config.php';
			require_once 'common.php';

			$dummy_contents = 'Telenor Pakistan, best known for ethics, has gone beyond its policies and allegedly hired a local Google Group moderator to send unsolicited emails to those internet users who never subscribed to the service. Telenor Pakistan, best known for ethics, has gone beyond its policies and allegedly hired a local Google Group moderator to send unsolicited emails to those internet users who never subscribed to the service.{{video_20051210-w50s-56K.flv}} Telenor Pakistan, best known for ethics, has gone beyond its policies and allegedly hired a local Google Group moderator to send unsolicited emails to those internet users who never subscribed to the service. Telenor Pakistan, best known for ethics, has gone beyond its policies and allegedly hired a local Google Group moderator to send unsolicited emails to those internet users who never subscribed to the service. Telenor Pakistan, best known for ethics, has gone beyond its policies and allegedly hired a local Google Group moderator to send unsolicited emails to those internet users who never subscribed to the service.';
			
			$test_token = 'audio_s055-ar-rahman.mp3';
			echo $get_contents = preg_replace('/\{\{([a-zA-Z_]*)\}\}/e', "$$1", $dummy_contents);
			
			$contents = explode('_',$test_token);
			$content_type = $contents[0];
			$content_id = $contents[1];
			
			$query = "SELECT * FROM amazon_s3 WHERE content_id ='".$content_id."'";			
			$results = $db->get_a_line($query);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Token Preview</title>
<script type='text/javascript' src='mediaplayer/swfobject.js'></script>
</head>

<body>
<table width="100%" border="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php 
  	if($content_type == 'video'){
  ?>
  <tr>
    <td width="10%">&nbsp;</td>
    <td width="80%">Video Contents</td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
    <div id='mediaspace'>This text will be replaced</div>
     
    <script type='text/javascript'>
      var so = new SWFObject('mediaplayer/player.swf','mpl','470','320','9');
      so.addParam('allowfullscreen','<?php echo ($results['full_screen'] == 'Yes')? 'true' : 'false';?>');
      so.addParam('allowscriptaccess','always');
      so.addParam('wmode','opaque');
      so.addVariable('file','<?php echo S3::getAuthenticatedURL($results['bucket_id'], $results['content_id'], 3600);?>');
      so.addVariable('screencolor','990000');
      so.addVariable('bufferlength','<?php echo $results['buffer_time'];?>');
      so.addVariable('controlbar','<?php echo ($results['player_controls'] == 'Yes')? 'bottom' : 'none';?>');
	  so.addVariable('autostart','<?php echo ($results['full_screen'] == 'Yes')? 'true' : 'false';?>');
      so.write('mediaspace');
    </script>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php echo ($results['download_link'] == 'Yes')?'<a href="'.S3::getAuthenticatedURL($results['bucket_id'], $results['content_id'], 3600).'">Download</a>' : '';?></td>
    <td>&nbsp;</td>
  </tr>
  <?php
	}elseif($content_type == 'audio'){
  ?>
  <tr>
    <td width="10%">&nbsp;</td>
    <td width="80%">Audio Contents</td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>

    <div id='mediaplayer'></div>
    
    <script type="text/javascript">
        var so = new SWFObject('mediaplayer/player.swf','playerID','560','24','9');
        so.addParam('allowfullscreen','true');
        so.addParam('allowscriptaccess','always');
        so.addVariable('file', '<?php echo S3::getAuthenticatedURL($results['bucket_id'], $results['content_id'], 3600);?>');
        so.addVariable('controlbar', '<?php echo ($results['player_controls'] == 'Yes')? 'bottom' : 'none';?>');
        so.write('mediaplayer');
    </script>


    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php echo ($results['download_link'] == 'Yes')? '<a href="'.S3::getAuthenticatedURL($results['bucket_id'], $results['content_id'], 3600).'">Download</a>' : '';?></td>
    <td>&nbsp;</td>
  </tr>
  <?php
	}elseif($content_type != 'audio' && $content_type != 'video'){
  ?>
  <tr>
    <td width="10%">&nbsp;</td>
    <td width="80%">Files </td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php echo ($results['download_link'] == 'Yes')? '<a href="'.S3::getAuthenticatedURL($results['bucket_id'], $results['content_id'], 3600).'">Download</a>' : '';?></td>
    <td>&nbsp;</td>
  </tr>
  <?php 
	}
?>
  
</table>
</body>
</html>