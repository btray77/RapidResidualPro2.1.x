<?php

class Parser{
		private $data;
		public $classes;		
		public $attributes;

		function __construct($data){
				$this->data = str_replace("\n", "", $data);
				$this->data = str_replace("\t", "", $this->data);
				
				$result = explode("{", $this->data);
				
				$cur = array();	
				for($i=0, $count = count($result); $i < $count; $i++){
						$temp = explode("}", $result[$i]);
						$cur = array_merge($cur, $temp);
				}
				
				for($i=0, $count = count($cur); $i < $count; $i++){
						if(@strstr($cur[$i], ":")) $this->attributes[$classID] = $this->parse_attributes($cur[$i]);
						else{
							$class = $this->parse_class($cur[$i], $i);
							$this->classes[] = $class;	
							$className = $class['name'];
							$classID = $i;
						}
				}
				

		}
		
		
		
		function get_classes($name){
					for($i=0, $count = count($this->classes); $i < $count; $i++){
							if(@strstr($this->classes[$i]['text'], $name)) $out[] = $this->classes[$i];	
					}
					return $out;
		}
		
		function get_attributes($class, $attr=''){
				$classes = $this->get_classes($class);
				
				//$class = "(" . implode("), (", $classes) . ")";
				
				for($i=0, $count = count($classes); $i < $count; $i++){
						$index = $classes[$i]['id'];	
						$name = $classes[$i]['text'];	
						
						if(empty($attr)){
							 //$find = implode(", <br>", $this->attributes[$index]);
							// echo '<pre>'; print_r($this->attributes[$index]); echo'</pre>';
								$attr_name = $this->attributes[$index];
								foreach($attr_name as $label => $text ){
										$attr .= $label;
										$find .= '<br>' . $text;
								}
							
						}
						else $find = $this->attributes[$index][$attr];
						
						if($find)	$attributes[] =  $find ;
				}
				
				return @implode('<br>', $attributes);
				
		}
		
		function parse_class($class, $id){
				if(strstr($class, ",")) $sisters = explode(",", $class);
				if(strstr($class, " ")) $childern = explode(" ", $class);
				
				switch(substr($class,0, 1)){
					
					case '.':
						$type = 'class';
						
					case '#': 
						$type = 'id';
						
					default:
						$type = 'tag';		
					
				}
				
			//	echo '<pre>'; print_r($sisters); echo'</pre>';
				
				if(count($sisters)>0){
						$name = $sisters[0];	
						//echo $name; exit;
				}
				else if(count($childern)>0){
						$name = $childern[0];	
				}
				
			//	echo 'name:' . $name . '<br>';
				
				if(empty($name)){
					$name = $class;	
				}
				
			//	$name = trim(str_replace(',', '', $name));
				$name = trim(str_replace('#', '', $name));
				$name = trim(str_replace('.', '', $name));
				
				$out['name'] = trim(str_replace($type, '', $name));
				$out['id'] = $id;
				
				$out['text'] = $class; 
				
				if(count($sisters)>0) $out['sisters'] = $sisters; 
				
				if(count($childern)>0) $out['childern'] = $childern; 
				
				return $out;
		}
		
		function write_class(){
			
		}
		
		function write_attaribute(){
			
		}
		
		function parse_attributes($attr_str){
				$pairs  = explode(";", trim($attr_str));
				
				for($i=0, $count = count($pairs); $i < $count; $i++){
							$temp = explode(":", trim($pairs[$i]));
							
							$index = trim($temp[0]);
							$value = trim($temp[1]);
							if(!empty($index)) $out[$index] = $value;
				}
				
				return $out;
		}
			
	

}




?>