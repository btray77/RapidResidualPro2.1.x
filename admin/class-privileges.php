<?php
class Privileges
{
	private $role;
	private $user;
	private $db;
	private $prefix;
	
	
	function __construct($userid,$db)
	{
		include($_SERVER['DOCUMENT_ROOT']."/common/config.php");
		$this->db = $db;
		$this->prefix=$prefix;
		$this->user=$userid;
	}
	
	function getRole()
	{
		$mysql="select role from ".$this->prefix."admin_settings where id=$this->user";
		$rs=$this->db->get_a_line($mysql);
		$this->role=$rs['role'];
		return 	$this->role;
	}
	function getAccess($url)
	{
		$mysql="select `all` as allaccess from ".$this->prefix."privileges where url='$url'";
		$rs=$this->db->get_a_line($mysql);
		return 	$rs['allaccess'];;
		
	}
	function canAdd($url){
		
		if($this->role==1 or $this->role==2 )
			return true;
		else if($this->getAccess($url)==1 )
			return true;
		else {	
		
		$mysql="select `add` as addRecord from ".$this->prefix."privileges where url='$url' and role=$this->role";
	   	$rs=$this->db->get_a_line($mysql);
		if($rs['addRecord']==1) 
			return true;
		else 
			return false;
		}
	
	}
	function canView($url){
		if($this->role==1 or $this->role==2 )
			return true;
		else if($this->getAccess($url)==1 )
			return true;
		else {
		$mysql="select `view` from ".$this->prefix."privileges where url='$url' and role=$this->role";
	  	$rs=$this->db->get_a_line($mysql);
		if($rs['view']==1) 
			return true;
		else 
			return false;
		}
	}
	function canEdit($url){
		if($this->role==1 or $this->role==2 )
			return true;
		else if($this->getAccess($url)==1)
			return true;
		else {
		$mysql="select `edit` from ".$this->prefix."privileges where url='$url' and role=$this->role";
	   	$rs=$this->db->get_a_line($mysql);
		if($rs['edit']==1) 
			return true;
		else 
			return false;
		}
	}
	function canDelete($url){
		if($this->role==1)
		return true;
		
		$mysql="select `delete` from ".$this->prefix."privileges where url='$url' and role=$this->role";
	   	$rs=$this->db->get_a_line($mysql);
		if($rs['delete']==1) 
			return true;
		else 
			return false;
	}
	
}