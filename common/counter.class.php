<?phpclass counter {    public $prefix;    public $db;    public function __construct() {        global $db, $prefix;	$this->prefix = $prefix;	$this->db = $db;    }    public function setcounter($productid=0,$pshort='',$ip,$ref)    {          $today= date('Y-m-d');         $num= md5(rand(5,100000));       $expiry = time()+(3600*24);       $ip_ref = md5($ip);      if(empty($_COOKIE[$pshort.'-code'])){          setcookie($pshort.'-code', $num, $expiry,"/");        setcookie($pshort.'-secure', $ip_ref, $expiry,"/");		$sql="select count(id) as total  from ".$this->prefix."click_stats where ip='$ip' AND                        referrer='$ref' AND                        product='$pshort' AND                        visited_date='$today'";                       $row=$this->db->get_a_line($sql);                       if($row['total'] < 1){                      $q = "insert into ".$this->prefix."click_stats set                                 visited_date='$today',                                cookies_ref='$num',                                ip_ref='$ip_ref',                                referrer='$ref',                                 ip='$ip',                                 item_type='$item_type',                                 item_id='$item_id',                                  product='$pshort'";                        $this->db->insert($q);			}        	}          if(empty($pshort))            {                $sql="select pshort from ".$this->prefix."products where id = '$productid'";                $row=$this->db->get_a_line($sql);                $pshort = $row['pshort'];             }              $cookies_ref = $_COOKIE[$pshort.'-code'];              $ip_ref = $_COOKIE[$pshort.'-secure'];              if(!empty($cookies_ref))               {                 $sql="select ip,cookies_ref  from ".$this->prefix."click_stats where ip_ref='$ip_ref' AND                        cookies_ref ='$cookies_ref' AND                           referrer='$ref' AND                        product='$pshort' AND                        visited_date='$today'";                      $row=$this->db->get_a_line($sql);                      $cookies_db = $row['cookies_ref'];                      $ip_db = md5($row['ip']);                                          if($cookies_db != "$cookies_ref" && $ip_db != $ip_ref )                    {                            $sql="select count(id) as total  from ".$this->prefix."click_stats where ip='$ip' AND                        referrer='$ref' AND                        product='$pshort' AND                        visited_date='$today'";                       $row=$this->db->get_a_line($sql);                       if($row['total'] < 1){                         $q = "insert into ".$this->prefix."click_stats set                                 visited_date='$today',                                cookies_ref='$cookies_ref',                                ip_ref='$ip_ref',                                referrer='$ref',                                 ip='$ip',								item_type='$item_type', 								item_id='$item_id',                                  product='$pshort'";                        $this->db->insert($q);                       }                                            }                    else                        return false;               }                                        }    public function setCounterByType($productid=0,$pshort='',$ip,$ref,$item_type = 'product',$item_id=0)    {                  $today= date('Y-m-d');         $num= md5(rand(5,100000));       $expiry = time()+(3600*24);       $ip_ref = md5($ip);              if(empty($_COOKIE[$pshort.'-code'])){          setcookie($pshort.'-code', $num, $expiry,"/");        setcookie($pshort.'-secure', $ip_ref, $expiry,"/");		 		$sql="select count(id) as total  from ".$this->prefix."click_stats where ip='$ip' AND                        referrer='$ref' AND                        product='$pshort' AND                        visited_date='$today'";                       $row=$this->db->get_a_line($sql);                       if($row['total'] < 1){                      $q = "insert into ".$this->prefix."click_stats set                                 visited_date='$today',                                cookies_ref='$num',                                ip_ref='$ip_ref',                                referrer='$ref',                                 ip='$ip', 								item_type='$item_type', 								item_id='$item_id',                                 product='$pshort'";                        $this->db->insert($q);						}      				}          if(empty($pshort))            {                $sql="select pshort from ".$this->prefix."products where id = '$productid'";                $row=$this->db->get_a_line($sql);                $pshort = $row['pshort'];             }                            $cookies_ref = $_COOKIE[$pshort.'-code'];              $ip_ref = $_COOKIE[$pshort.'-secure'];              if(!empty($cookies_ref))               {                 $sql="select ip,cookies_ref  from ".$this->prefix."click_stats where ip_ref='$ip_ref' AND                        cookies_ref ='$cookies_ref' AND                           referrer='$ref' AND                        product='$pshort' AND                        visited_date='$today'";                      $row=$this->db->get_a_line($sql);                      $cookies_db = $row['cookies_ref'];                      $ip_db = md5($row['ip']);                                          if($cookies_db != "$cookies_ref" && $ip_db != $ip_ref )                    {                            $sql="select count(id) as total  from ".$this->prefix."click_stats where ip='$ip' AND                        referrer='$ref' AND                        product='$pshort' AND                        visited_date='$today'";                       $row=$this->db->get_a_line($sql);                       if($row['total'] < 1){                         $q = "insert into ".$this->prefix."click_stats set                                 visited_date='$today',                                cookies_ref='$cookies_ref',                                ip_ref='$ip_ref',                                referrer='$ref',                                 ip='$ip',								item_type='$item_type', 								item_id='$item_id',                                  product='$pshort'";                        $this->db->insert($q);                       }                                            }                    else                        return false;               }    }}?>