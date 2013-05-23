<?php
class Template_information{
	 private $path;
	 private $dir_handle;
	 private $totalsize; 
	 private $totalcount;
	 private $dircount;
 
	 public function __construct($path)
	 {
	 	$this->path=$path;
	 	$this->dir_handle = @opendir($this->path) or die("Unable to open $path");
	 	
	 }
 	public function getDirectorySize($path)
	{
	
	  if ($dir_handle = opendir ($path))
	  {
	    while (false !== ($file = readdir($dir_handle)))
	    {
	    	 
	      $nextpath = $path . '/' . $file;
	      if ($file != '.' && $file != '..' &&  $file != '.svn' && !is_link ($nextpath))
	      {
	        if (is_dir ($nextpath))
	        {
	          $dircount++;
	          $result = $this->getDirectorySize($nextpath);
	         
	          $this->totalsize += $result['size'];
	       
	        }
	        elseif (is_file ($nextpath))
	        {
	          $this->totalsize += filesize ($nextpath);
	          $this->totalcount++;
	        }
	      }
	    }
	  }
	  closedir ($dir_handle);
	 
	
	  return $this->sizeFormat($this->totalsize);
	} 
 
 	public function sizeFormat($size)
	{
	    if($size<1024)
	    {
	        return $size." bytes";
	    }
	    else if($size<(1024*1024))
	    {
	        $size=round($size/1024,1);
	        return $size." KB";
	    }
	    else if($size<(1024*1024*1024))
	    {
	        $size=round($size/(1024*1024),1);
	        return $size." MB";
	    }
	    else
	    {
	        $size=round($size/(1024*1024*1024),1);
	        return $size." GB";
	    }

	} 
	public function ReadFolderDirectory($dir = "../templates/")
    {
    $dirs=(scandir($dir));
    
    for($i=0;$i<count($dirs);$i++)
    	{
            if($dirs[$i]!='.' && $dirs[$i] != '..' )
            	 $lists[]= $dirs[$i];
    	}	
   
        return $lists;   
    }
	public function getDefault($prifix,$db,$name)
	{
		$sql="select `default` from ".$prifix."template where name='$name'";
		$row=$db->get_a_line($sql);
		if($row['default']==1)
			return 1;
		else 
			return 0;	
	}     
}