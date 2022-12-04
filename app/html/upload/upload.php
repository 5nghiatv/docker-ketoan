<?php

if (isset($_GET['username']) && isset($_GET['password']) ){
	$username = $_GET['username'] ;
	$password = $_GET['password'] ;
	$data =  getnamedb($username, $password) ;
	echo json_encode($data);
	return;
}

// var_dump(getnamedb());
function getnamedb($username,$password){
	$conn = new mysqli('localhost',$username, $password);
	if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
	$result = $conn->query('show databases');
	$data = [];
	if ($result->num_rows > 0) {
		$except = ' information_schema ,mysql ,performance_schema ,phpmyadmin ,test ';
	    foreach ($result as $key => $value) {
	    	if(empty(strpos($except,$value['Database']))){
	    		$data[]= $value['Database'] ;
	    	}
	    }
	} else { echo "Không tìm thấy"; }
	$conn->close();
	return $data;
}

function newbackupdb(){
	include('backup.php');
	$Backup = new DBbackup();
	//$Backup->backupdb();  // Data oncly
	$Backup->backup();   // Data & Proc
}

//====================================================
function updatabase($filename){
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if(isset($_POST['data_name']) && $_POST['data_name'] !== ''){
		$conn = new mysqli('localhost',$_POST['username'] , $_POST['pswd']);
		$dbname = $_POST['data_name'] ;
		$dbempty = empty(mysqli_fetch_array(mysqli_query($conn,"SHOW DATABASES LIKE '$dbname'"))) ;
		if(!$dbempty){
			$actual_link = str_replace('upload.php', 'bigdump2.php', $actual_link) ;
			//read the entire string
			$str=file_get_contents('bigdump.php');
			$str=str_replace('db_name123456', $_POST['data_name'],$str);
			$str=str_replace('db_username123456', $_POST['username'],$str);
			$str=str_replace('db_password123456', $_POST['pswd'],$str);
			//write the entire string
			file_put_contents('bigdump2.php', $str);
			//---------------- Thay user mới vào file SQL
			$file_lines = file($filename);
			$newuser = $_POST['username'];
			foreach ($file_lines as $line) {
				// Phải là -1 mới OK
			    if(strpos($line,'CREATE DEFINER=') > -1){
			    	$olduser = substr($line ,0,strpos($line,'@`localhost`'));
			    	$newuser = 'CREATE DEFINER=`'.$newuser.'`';
			    	$str=file_get_contents($filename);
			    	$str=str_replace($olduser,$newuser,$str);
			    	file_put_contents($filename, $str);
			    	break;
			    }
			}
			// run bigdump
			header('Location: '.$actual_link);
		}
	}
	printf('<b>%s</b>', 'Nếu muốn import vào Database phải nhập tên Database tồn tại trong hệ thống và CHƯA CÓ dữ liệu.<br>');  
	echo '<p><a href="javascript:history.go(-1)" title="Return to the previous page">&laquo; Go back</a></p>';
}

//----------- Upload---------------
if (isset($_POST['backupBtn']) && $_POST['backupBtn'] == 'Backup') {
	if(!isset($_POST['data_name']) OR $_POST['data_name'] == ''){
		printf('<b>%s</b>', 'Vui lòng nhập tên Database để sao lưu.<br>');  
		echo '<p><a href="javascript:history.go(-1)" title="Return to the previous page">&laquo; Go back</a></p>';		
		return;
	}
	 newbackupdb();
	// header('Location: http://nghiatv.googlepages.com');
}
if (isset($_POST['loginBtn']) && $_POST['loginBtn'] == 'Login') {
	header('Location: http://nghiatv.tk/ketoan/');
}

//----------- Upload---------------
if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload') {
	if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
		// get details of the uploaded file
		$fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
		$fileName = $_FILES['uploadedFile']['name'];
		$fileSize = $_FILES['uploadedFile']['size'];
		$fileType = $_FILES['uploadedFile']['type'];
		$fileNameCmps = explode(".", $fileName);
		$fileExtension = strtolower(end($fileNameCmps));
		$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
		$allowedfileExtensions=array('jpg','gif','png','zip','sql','txt','xls','doc','xml');
		if (in_array($fileExtension, $allowedfileExtensions)) {
			// directory in which the uploaded file will be moved
			$uploadFileDir = './';
			$dest_path = $uploadFileDir . $newFileName;
			// Login ftp --------------
			$ftp_server="210.211.99.211";
			$ftp_user_name=$_POST['username'];
			$ftp_user_pass=$_POST['pswd'];
			
			if(!empty($_POST['isHosting'])){
				$conn_id = ftp_connect($ftp_server);
				$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
				ftp_close($conn_id);
			}	
			$login_result = !empty($_POST['isHosting']) ? $login_result : true  ;

			if( $login_result && move_uploaded_file($fileTmpPath, $dest_path))
			{
			  	$message ='File is successfully uploaded.';
				$zip = new ZipArchive;
				$res = $zip->open($newFileName);
				if ($res === TRUE) {
					$zipisdata = false;
					if($zip->getNameIndex(0)){
						$filename = strtolower($zip->getNameIndex(0));
						$zipisdata = strpos($filename,'.sql') > 0 ;
					}
					$zip->extractTo('./');
					$zip->close();
					unlink($newFileName);
					if($zipisdata){return updatabase($filename);
					}else  header('Location: http://nghiatv.tk/ketoan/');
					
				}else  $message = "Sorry, Not open Zip file.";

			}else  $message = 'Vui lòng đăng nhập lại !!';
		}
	}else  $message = 'Vui lòng chọn file to Upload !!';
	printf('<b>%s</b>', $message.'<br>');
}
echo '<p><a href="javascript:history.go(-1)" title="Return to the previous page">&laquo; Go back</a></p>';
// if (isset($_SERVER["HTTP_REFERER"])) {
//         header("Location: " . $_SERVER["HTTP_REFERER"]);
//     }

?>
