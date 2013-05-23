<?php
$file=file($_SERVER['DOCUMENT_ROOT']."/html/admin/footer.html");
$returncontent=join("",$file);

$returncontent=preg_replace("/{{(.*?)}}/e","$$1",$returncontent);
echo $returncontent;
?>