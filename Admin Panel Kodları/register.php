<?php
	ob_start();
	$database = new PDO("mysql:host=localhost;dbname=stockapp;charset=utf8mb4", "root", "");
	session_start();

	if(isset($_SESSION['user_id'])){
		header('location:index.php');
	}
	else{
		$return = '';
		if(isset($_POST['register'])){
			$first_name = trim(htmlspecialchars($_POST['first_name']));
			$last_name = trim(htmlspecialchars($_POST['last_name']));
			$email_address = trim(htmlspecialchars($_POST['email_address']));
			$password = $_POST['password'];

		    if(empty($first_name)){
		        $return = '<div class="alert alert-danger" role="alert">Ad girin</div>';
		    }
		    else{
		    	if(empty($last_name)){
		    		$return = '<div class="alert alert-danger" role="alert">Soyad girin</div>';
		    	}
		    	else{
				    if(empty($email_address)){
				        $return = '<div class="alert alert-danger" role="alert">E-posta adresi geçersizdir</div>';
				    }
				    else{
				        $query = "SELECT * FROM users WHERE email_address = :email_address";
				        $statement = $database->prepare($query);
				        $statement->execute(
				          array(
				              ':email_address' => $email_address
				             )
				        );
				        if($statement->rowCount() > 0){
				        	$return = '<div class="alert alert-danger" role="alert">E-posta adresi zaten kayıtlıdır</div>';
				        }
				        else{
				        	if(empty($password)){
				        		$return = '<div class="alert alert-danger" role="alert">Şifre girin</div>';
				        	}
				        	else{
				        		$return = '';
				        	}
				        }
				    }
		    	}
		    }

		    if($return == ''){
				$data = array(
					':first_name'  => $first_name,
					':last_name'  => $last_name,
					':email_address'  => $email_address,
					':password'  => password_hash($password, PASSWORD_DEFAULT)
				);
				$query = "INSERT INTO users (first_name, last_name, email_address, password) VALUES (:first_name, :last_name, :email_address, :password)";
				$statement = $database->prepare($query);
				if($statement->execute($data)){
					$return = '<div class="alert alert-success" role="alert">Hesabınız başarıyla oluşturuldu</div>';
					header('Refresh: 1; url=login.php');
					
				}
				else{
					$return = '<div class="alert alert-primary" role="alert">Hesap oluşturulamadı!</div>';
				}
		    }

		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>StokAPP - Kaydol</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light border-bottom bg-white">
		<div class="container">
			<a class="navbar-brand" href="index.php">Stok APP / Kaydol</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
				<ul class="navbar-nav"></ul>
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" aria-current="page" href="index.php">Ana sayfa</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" aria-current="page" href="login.php">Giriş</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="py-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-5 col-12">
					<form method="POST">
						<div class="card">
							<div class="card-header">Kaydol</div>
							<div class="card-body">
								<?php echo $return;?>
								<div class="row mb-3">
									<div class="col-6">
										<label for="first_name" class="form-label">Ad
											<span class="text-danger">*</span>
										</label>
										<input type="text" class="form-control" placeholder="Ad" id="first_name" name="first_name" value="Domain">
									</div>
									<div class="col-6">
										<label for="last_name" class="form-label">Soyad
											<span class="text-danger">*</span>
										</label>
										<input type="text" class="form-control" placeholder="Ad" id="last_name" name="last_name" value="Admin">
									</div>
								</div>
								<div class="mb-3">
									<label for="email_address" class="form-label">E-posta adresi
										<span class="text-danger">*</span>
									</label>
									<input type="text" class="form-control" placeholder="E-posta adresini gir" id="email_address" name="email_address" value="admin@example.com">
								</div>
								<div class="mb-3">
									<label for="password" class="form-label">Şifre
										<span class="text-danger">*</span>
									</label>
									<input type="password" class="form-control" placeholder="Şifreni gir" id="password" name="password" value="123456">
								</div>
							</div>
							<div class="card-footer">
								<button type="submit" name="register" class="btn btn-primary w-100">Kaydol</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script src="js/jquery-3.6.4.min.js" type="text/javascript"></script>
	<script src="js/popper.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>