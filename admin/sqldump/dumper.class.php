<?php

//require_once '/common/database.class.php';

class dumper {

    private $con;
    private $dbname;

    public function __construct($db, $dbname) {
        $this->con = $db;
		
        //$this->dbname="Tables_in_rapid";
        $this->dbname="Tables_in_".$dbname;
    }

    public function getCreateTable($table) {
      $query = 'SHOW CREATE TABLE ' . $table;
       
        $rs = $this->con->get_rsltset($query);
        return $rs[0]['Create Table'];
    }

    public function exportDb() {
         $tables = $this->getTables();
        
        foreach ($tables as $key => $tableArr) {
            $table = $tableArr[$this->dbname];
            //$return.= '--Data for the table ' . $table . ' ';
            $return.="\n\n";
            $return.= 'DROP TABLE IF EXISTS  ' . $table . ';';
            $return.= "\n\n" . $this->getCreateTable($table) . ";\n\n";
            $return .= $this->getTableData($table);
            $return.="\n\n\n";
        }
       
        //save file
        $fileName = $_SERVER[DOCUMENT_ROOT].'/dumper/sql_dumps/' . time() . '.sql';
        $handle = fopen($fileName, 'w+');
        fwrite($handle, $return);
        fclose($handle);
    }

    private function getTables() {
        $query = 'SHOW TABLES';
        return $this->con->get_rsltset($query);
    }

    private function getTableFields($table) {
        $query = 'DESC ' . $table;
        $result = $this->con->get_rsltset($query);
        $fieldsArr = array();
        foreach ($result as $key => $value) {
            $fieldsArr[] = $value['Field'];
        }
        return $fieldsArr;
    }

    private function getTableData($table) {
        
        $fields = $this->getTableFields($table);
        print_r($fields);
        
        $query = 'SELECT * FROM ' . $table;
        $result = $this->con->get_rsltset($query);
		
       $querycnt = 'SELECT count(*) as cnt FROM ' . $table;
      $resultcnt = $this->con->get_a_line($querycnt);
        
        if($resultcnt['cnt'] > 0){
        $return.= 'INSERT INTO `' . $table . '` VALUES';
        foreach ($result as $keyResult => $row) {
            $return.= '(';
            foreach ($fields as $key => $field) {
                $value = $field;
                $row[$value] = addslashes($row[$value]);
                $row[$value] = ereg_replace("\n", "\\n", $row[$value]);
                if (isset($row[$value])) {
                    $return.= "'" . $row[$value] . "'";
                } else {
                    $return.= '""';
                }
                if ($key < (count($fields) - 1)) {
                    $return.= ',';
                }
            }
            if ($keyResult < (count($result) - 1)) {
                $return.= '),';
            }
        }
        $return.= ");\n";
        }else{
        	
        }
        
        return $return;
    }
    public function listdirByDate($path){
    $dir = opendir($path);
    $list = array();
    while($file = readdir($dir)){
        if ($file != '.' and $file != '..'){
            // add the filename, to be sure not to
            // overwrite a array key
            $ctime = filectime($path .'/'. $file) . ',' . $file;
            $list[$ctime] = $file;
        }
    }
    closedir($dir);
    krsort($list);
    return $list;
}

   public function formatbytes($file, $type='KB') {
        switch($type){
            case "KB":
                $filesize = filesize($file) * .0009765625; // bytes to KB
           break;
           case "MB":
               $filesize = (filesize($file) * .0009765625) * .0009765625; // bytes to MB
            break;
           case "GB":
              $filesize = ((filesize($file) * .0009765625) * .0009765625) * .0009765625; // bytes to GB
          break;
     }
      if($filesize <= 0){
         return $filesize = 'unknown file size';}
      else{return round($filesize, 2).' '.$type;}
  }

 public function deleteFiles($path,$arr) {

     foreach ($arr as $value) {
        unlink($path.'/'.$value);
        
     }

 }
}
?>