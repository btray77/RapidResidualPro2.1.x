<?php
include_once("session.php");

$mrand  = $_GET['mrand'];
$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
$os = $common->getOS($user_agent);
$browser =$common->getBrowser($user_agent);
?>

<html>
<body>
<script language="JavaScript" src="http://j.maxmind.com/app/geoip.js"></script>
<form name="submitform" action='../member/index.php' method="post">
    <input type="hidden" name="mrand" value='<?=$mrand?>'>
    <input name="country" id="country" value=""  type="hidden"  />
    <input name="city" value="" id="city"  type="hidden"  />
    <input name="latitude" value="" id="latitude" type="hidden"  />
    <input name="longitude" value="" id="longitude"  type="hidden"  />
    <input name="operating_system" value="<?php echo $os ?>"  type="hidden"  />
    <input name="browser" value="<?php echo $browser ?>"  type="hidden"  />
</form>
<script type="text/javascript">
    document.getElementById('country').value=geoip_country_name();
    document.getElementById('city').value=geoip_city();
    document.getElementById('latitude').value=geoip_latitude();
    document.getElementById('longitude').value=geoip_longitude();    
    document.forms["submitform"].submit();
</script>
</body>
</html>

<?php
?>