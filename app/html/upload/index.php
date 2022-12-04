<?php
session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
  <title>Kế toán ACN</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

</head>
<body>
  <?php
    if (isset($_SESSION['message']) && $_SESSION['message'])
    {
      printf('<b>%s</b>', $_SESSION['message']);
      unset($_SESSION['message']);
    }
  ?>

  <nav class="navbar navbar-expand-lg navbar-light"  style="background-color: #e3f2fd;">
    <a class="navbar-brand" href="http://nghiatv.tk/ketoan" > <img src="https://9d6891d6-a-62cb3a1a-s-sites.googlegroups.com/site/nghiatv02/home/phan-mem-quan-ly/TinHocXuanMai%20%26QR.png?attachauth=ANoY7cprAm64pBl6tgG7eZn-TY3UndHV6rcYhJAn7NiHn-kjoH9HZiOv2nNhqvp5BATFMNkeHyAXcslL7NpWugrGi1HqpRpmV8oPs9WSNiQFnfo2c8_5LE2wVlUkjT00ZxgCWmwm9e6F44idSU5pSCjvSLWKQDzrYv5QUH8_UtKVoJHPfBHTiJjtQwyZ1aNJZhv8gudZd5OrSRscuY88U_-3NvB7yDRPtDFBG1KFFhhrwxBNaj2kq-paUaks4Dzxy1ZPF5Ym6mtm&attredirects=0" alt="" width=150 height=30></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="http://nghiatv.googlepages.com">VFP Accounting <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="http://nghiatv.tk">Manager Website</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Hosting & Cloud
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="http://021customermj.svr.keyweb.vn:3636/CMD_FILE_MANAGER/domains/nghiatv.tk/public_html">DirectAdmin</a>
            <a class="dropdown-item" href="http://021customermj.svr.keyweb.vn/phpmyadmin/index.php">phpMyAdmin</a>

			<div class="dropdown-divider"></div>            

            <a class="dropdown-item" href="https://my.freenom.com/clientarea.php?action=domains">Domain freenom</a>
            <a class="dropdown-item" href="https://console.firebase.google.com/u/1/project/ketoan-acn1/overview">FireBase Cloud</a>
            <a class="dropdown-item" href="https://client.keyweb.vn/website/manage">Hosting Keyweb</a>

          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.html">Contact Form</a>
        </li>

      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>
    </div>
  </nav>
<!-- =================== -->

	<div class="container">
		<br>
		<div class="col-sm-7" style="background-color:Lavender;">	
		<!-- <div class="col-sm-6" style="background-color:LavenderBlush;"> -->
			<!-- http://www.htmlcodes.ws/color/html-color-code-generator.cfm?colorName=Lavender -->
			<div class="container">
				<br>
 	 		    <h4 class="brand"><span>Welcome</span> XuânMai.Corpt</h4>
 	 		    <br>
	 		    <form method="POST" action="upload.php" enctype="multipart/form-data">	
					<div class="form-group btn btn-warning row" id='uploadedFile'>
						<!-- <input type="file" name="uploadedFile" required /> -->
						<input type="file" name="uploadedFile"  />
					</div>
					<div class="row" id='isHost'> 
				         <div class="form-group form-check  col-sm-6">
				            <label class="form-check-label">&nbsp;&nbsp;&nbsp;
				            <input class="form-check-input" id='isHosting' type="checkbox" name="isHosting"> Upload Website to Hosting
				            </label>
				         </div>
				    </div>     
					<div class="row" id='login'> 
						 <div class="form-group col-sm-6">
							<label for="user">User name:</label>
							<input type="username" class="form-control" id="username" placeholder="Enter user name" name="username" required >
						 </div>

						 <div class="form-group col-sm-6">
							<label for="pwd">Password:</label>
							<input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pswd" required >
						 </div>
					</div>	 
					<div class="row" id='data'> 
						<div class="form-group col-sm-7">
							<input type="dataname" class="form-control" placeholder="Enter Database name" id='data_name' name="data_name">
						</div>

						<div class="form-group col-sm-5">
							<div class="dropdown">&nbsp;
								<select id="listData" class="btn btn-info" >
									<option value="" selected disabled>List Database</option>

								</select>
							</div>
						</div>	
					</div>	
					<br>
					 <hr>
					 <div class="row container">
						<button type="submit" name="uploadBtn" value="Upload" class="btn btn-info">Upload to Server</button>&nbsp;&nbsp;
						<button type="submit" id='backupBtn' name="backupBtn" value="Backup" class="btn btn-info">Backup Data</button>&nbsp;&nbsp;
						<button type="submit" name="loginBtn" value="Login" class="btn btn-info"> Kế toán ACN</button>&nbsp;&nbsp;
						<a href='contact.html' class="btn btn-info">Contact Form</a>
						
					 </div>
				</form>
			</div>   
			<br>
		</div>

	</div>
	
	<script src="updata.js"></script>

</body>

</html>
<style>
.form-group {
    margin-bottom: 1rem;
    margin-left: 0px;
}
.brand{
  text-align: center;
}

.brand1 span{
  color:#fcf;
}

.brand span{
  color:#fff;
}
</style>


