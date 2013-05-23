<?php 
	$get_url = explode("/", $_SERVER['REQUEST_URI']);
	$file_name = explode(".", $get_url[2]);
	if($get_url[2] == '' || $file_name[0] == 'index'){
		$file_perm = 'step-on';
	}elseif($file_name[0] == 'license'){
		$license = 'step-on';
	}elseif($file_name[0] == 'install1'){
		$dbase = 'step-on';
	}elseif($file_name[0] == 'install2'){
		$adetails = 'step-on';
	}elseif($file_name[0] == 'install3'){
		$fnish = 'step-on';
	}
?>
<div id="stepbar">
    <div class="t">
        <div class="t">
            <div class="t"></div>
        </div>
    </div>
    <div class="m">
        <h1>Steps</h1>
        <div class="<?php if(@$file_perm){ echo @$file_perm; }else{ echo "step-off";}?>">1 : File Permissions</div>
        <div class="<?php if(@$license){ echo @$license; }else{ echo "step-off";}?>">2 : License</div>
        <div class="<?php if(@$dbase){ echo @$dbase; }else{ echo "step-off";}?>">3 : Database</div>
        <div class="<?php if(@$adetails){ echo @$adetails; }else{ echo "step-off";}?>">4 : Admin Details</div>
        <div class="<?php if(@$fnish){ echo @$fnish; }else{ echo "step-off";}?>">5 : Finish</div>
        <div class="box"></div>
    </div>
    <div class="b">
        <div class="b">
            <div class="b"></div>
        </div>
    </div>
</div>