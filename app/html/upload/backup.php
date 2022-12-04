<?php

class DBbackup {
   public $suffix;
   public $dirs;
   public $dbname;
   protected $dbInstance;
   public function __construct() {

    $dbhost='localhost';
    $dbname= $_POST['data_name'];
    $username= $_POST['username'];
    $password= $_POST['pswd'];
    $this->dbname = $dbname ;

     try{
      $this->dbInstance = new PDO("mysql:host=".$dbhost.";dbname=".$dbname, 
      $username, $password ,array(
      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
      ));
    } catch(Exception $e) {
      die("Error ".$e->getMessage());
    }
     $this->suffix = date('Ymd_His');

   }

   public function backupdb($tables = '*',$add = false){
     // $output = "-- database PROCEDURE - ".date('Y-m-d H:i:s').PHP_EOL;
     // $output .= "SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';".PHP_EOL;
     // $output .= "SET foreign_key_checks = 0;".PHP_EOL;
     // $output .= "SET AUTOCOMMIT = 0;".PHP_EOL;
     // $output .= "START TRANSACTION;".PHP_EOL;
     //get all table names
     $output = PHP_EOL ;
     if($tables == '*') {
       $tables = [];
       $query = $this->dbInstance->prepare('SHOW TABLES');
       $query->execute();
       while($row = $query->fetch(PDO::FETCH_NUM)) {
         $tables[] = $row[0];
       }
       $query->closeCursor();
     }
     else {
       $tables = is_array($tables) ? $tables : explode(',',$tables);
     }

     foreach($tables as $table) {

       $query = $this->dbInstance->prepare("SELECT * FROM `$table`");
       $query->execute();
       $output .= "DROP TABLE IF EXISTS `$table`;".PHP_EOL;
       $query2 = $this->dbInstance->prepare("SHOW CREATE TABLE `$table`");
       $query2->execute();
       $row2 = $query2->fetch(PDO::FETCH_NUM);
       $query2->closeCursor();
       $output .= PHP_EOL.$row2[1].";".PHP_EOL;
         while($row = $query->fetch(PDO::FETCH_NUM)) {
           $output .= "INSERT INTO `$table` VALUES(";
           for($j=0; $j<count($row); $j++) {
             $row[$j] = addslashes($row[$j]);
             $row[$j] = str_replace("\n","\\n",$row[$j]);
             if (isset($row[$j]))
               $output .= "'".$row[$j]."'";
             else $output .= "''";
             if ($j<(count($row)-1))
              $output .= ',';
           }
           $output .= ");".PHP_EOL;
         }
       }
       $output .= PHP_EOL.PHP_EOL;
       $output .= "-- -------------------------------------".PHP_EOL;          
       $output =  str_replace("DEFAULT '0000-00-00 00:00:00'","DEFAULT CURRENT_TIMESTAMP",$output);
       if($add){ return $output ;}  // Sẽ nối sau phần procedure

     //$output .= "COMMIT;";
     //save filename

      $backup_file_name = $this->dbname.'_db_'.$this->suffix ;
      $this->writeUTF8filename($backup_file_name.'.sql',$output);
      // ---------------- ZIP file
      $zip = new ZipArchive;
      $res = $zip->open($backup_file_name. '.zip',ZIPARCHIVE::CREATE);
      if ($res === TRUE) {
        $zip->addFile($backup_file_name. '.sql', $this->dbname. '.sql');
        $zip->close();
        unlink($backup_file_name. '.sql');
        $backup_file_name = $backup_file_name. '.zip' ;
      }else $backup_file_name = $backup_file_name. '.sql' ; 

      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=' . basename($this->dbname.'_db.zip'));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($backup_file_name));
      ob_clean();
      flush();
      readfile($backup_file_name);
      exec('rm ' . $backup_file_name); 
      unlink($backup_file_name);
   }


   private function writeUTF8filename($fn,$c){  /* save as utf8 encoding */
     $handle =fopen($fn,"w+");
     # Now UTF-8 - Add byte order mark
     fwrite($handle, pack("CCC",0xef,0xbb,0xbf));
     fwrite($handle, $c);
     fclose($handle);
   }

 public function backup($tables = '*'){
     $output  = PHP_EOL;
     $output .= "SET sql_mode = '';".PHP_EOL;
     $output .= 'START TRANSACTION;'.PHP_EOL;
     $output .= 'SET time_zone = "+00:00";'.PHP_EOL;
     $output .= "SET foreign_key_checks = 0;".PHP_EOL;
     $output .= PHP_EOL;
     $output .= "-- ".PHP_EOL;
     $output .= "-- Cơ sở dữ liệu: `$this->dbname`".PHP_EOL;          
     $output .= "-- ".PHP_EOL;          
     $output .= PHP_EOL;
     $output .= "DELIMITER $$".PHP_EOL;          
     $output .= PHP_EOL;          
     $output .= "--".PHP_EOL;          
     $output .= "-- Thủ tục".PHP_EOL;          
     $output .= "--".PHP_EOL;          
     //get all table names
     $output .= PHP_EOL ;
     if($tables == '*') {
       $tables = [];
       $qr = "SHOW PROCEDURE STATUS WHERE DB = '$this->dbname'" ;
       $query = $this->dbInstance->prepare($qr);
       $query->execute();
       while($row = $query->fetch(PDO::FETCH_NUM)) {
         $tables[] = $row[1];  // [2] is Name PROCEDURE
       }
       $query->closeCursor();
     }
     else {
       $tables = is_array($tables) ? $tables : explode(',',$tables);
     }

    foreach($tables as $table) {
       //$query = $this->dbInstance->prepare("SELECT * FROM `$table`");
       //$query->execute();
      $output .= "DROP PROCEDURE IF EXISTS `$table`$$".PHP_EOL;
      $query2 = $this->dbInstance->prepare("SHOW CREATE PROCEDURE $this->dbname.$table");
      $query2->execute();
      $row2 = $query2->fetch(PDO::FETCH_NUM);
      $query2->closeCursor();
      if($row2[2]){
        $output .= PHP_EOL.$row2[2]."$$".PHP_EOL;  // [2] is CREATE PROCEDURE
      }  
      while($row = $query->fetch(PDO::FETCH_NUM)) {
           $output .= "INSERT INTO `$table` VALUES(";
           for($j=0; $j<count($row); $j++) {
             $row[$j] = addslashes($row[$j]);
             $row[$j] = str_replace("\n","\\n",$row[$j]);
             if (isset($row[$j]))
               $output .= "'".$row[$j]."'";
             else $output .= "''";
             if ($j<(count($row)-1))
              $output .= ',';
           }
           $output .= ");".PHP_EOL;
      }
    }
      $output .= PHP_EOL;
      $output .= "DELIMITER ;".PHP_EOL;          
      $output .= "-- -------------------------------------".PHP_EOL;          
      $output .= $this->backupdb('*',true);

     //$output .= "COMMIT;";
     //save filename

      $backup_file_name = $this->dbname.'_'.$this->suffix ;
      $this->writeUTF8filename($backup_file_name.'.sql',$output);
      // ---------------- ZIP file
      $zip = new ZipArchive;
      $res = $zip->open($backup_file_name. '.zip',ZIPARCHIVE::CREATE);
      if ($res === TRUE) {
        $zip->addFile($backup_file_name. '.sql', $this->dbname. '.sql');
        $zip->close();
        unlink($backup_file_name. '.sql');
        $backup_file_name = $backup_file_name. '.zip' ;
      }else $backup_file_name = $backup_file_name. '.sql' ; 

      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=' . basename($this->dbname.'.zip'));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($backup_file_name));
      ob_clean();
      flush();
      readfile($backup_file_name);
      exec('rm ' . $backup_file_name); 
      unlink($backup_file_name);
   }   
}

?> 

<!-- var_dump($output);
die();
 -->