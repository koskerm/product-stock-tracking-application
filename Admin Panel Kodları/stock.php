<?php
	ob_start();
	$database = new PDO("mysql:host=localhost;dbname=stockapp;charset=utf8mb4", "root", "");
	session_start();

	if(!isset($_SESSION['user_id'])){
		header('location:login.php');
	}
	else{
		$return = '';
		if(!isset($_GET['stock_id'])){
			header('location:index.php');
		}
		else{
		    $query = "
		    SELECT * FROM stocks 
		    WHERE stock_id = :stock_id
		    ";
		    $statement = $database->prepare($query);
		    $statement->execute(
		      array(
		          ':stock_id' => trim(htmlspecialchars($_GET['stock_id']))
		         )
		    );
		    if($statement->rowCount() > 0){
		    	$result = $statement->fetchAll();
		    	foreach($result as $row){
		    		$title = $row['title'];
		    		$description = $row['description'];
		    		$stock = $row['stock'];
		    		$price = $row['price'];
		    	}
		    }
		}


	    if(isset($_POST['update'])){
		    if(empty($_POST['title'])){
		        $return = '<div class="alert alert-primary" role="alert">Başlık gir!</div>';
		    }
		    else{
			    if(empty($_POST['description'])){
			        $return = '<div class="alert alert-primary" role="alert">Açıklama gir!</div>';
			    }
			    else{
				    if(empty($_POST['stock'])){
				        $return = '<div class="alert alert-primary" role="alert">Stok gir!</div>';
				    }
				    else{
					    if(empty($_POST['price'])){
					        $return = '<div class="alert alert-primary" role="alert">Fiyat gir!</div>';
					    }
					    else{
					    	$return = '';
					    }
				    }
			    }
		    }

		    if($return == ''){
				$data = array(
					':stock_id'  => trim(htmlspecialchars($_GET['stock_id'])),
					':title'  => trim(htmlspecialchars($_POST['title'])),
					':description'  => trim(htmlspecialchars($_POST['description'])),
					':stock'  => trim(htmlspecialchars($_POST['stock'])),
					':price'  => trim(htmlspecialchars($_POST['price']))
				);
				$query = 'UPDATE `stocks`
				SET
				`title`=:title,
				`description`=:description,
				`stock`=:stock,
				`price`=:price
				WHERE
				stock_id=:stock_id';
				$statement = $database->prepare($query);
				if($statement->execute($data)){
					$return = '<div class="alert alert-success" role="alert">Düzenleme başarılı</div>';
					header('Refresh: 1; url=stock.php?stock_id='.trim(htmlspecialchars($_GET['stock_id'])).'');
				}
				else{
					$return = '<div class="alert alert-primary" role="alert">Düzenleme başarısız!</div>';
				}
		    }
	    }

	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Stok APP - Düzenle</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light border-bottom bg-white">
		<div class="container">
			<a class="navbar-brand" href="index.php">Stok APP / Düzenle</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
				<ul class="navbar-nav"></ul>
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" aria-current="page" href="index.php">Ana sayfa</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo $_SESSION['first_name'].'&nbsp;'.$_SESSION['last_name'];?></a>
						<ul class="dropdown-menu text-center" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="dashboard.php">Yönetim Paneli</a></li>
							<li><a class="dropdown-item" href="logout.php">Çıkış yap</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="py-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8 col-12">
					<form method="POST">
						<div class="card">
							<div class="card-header">Düzenle</div>
							<div class="card-body">
								<?php echo $return;?>
								<div class="mb-3">
									<label for="title" class="form-label">Başlık
										<span class="text-danger">*</span>
									</label>
									<input type="text" class="form-control" placeholder="Başlık gir" id="title" name="title" value="<?php echo $title;?>">
								</div>
								<div class="mb-3">
									<label for="description" class="form-label">Açıklama
										<span class="text-danger">*</span>
									</label>
									<textarea type="text" class="form-control" placeholder="Açıklama" id="description" name="description"><?php echo $description;?></textarea>
								</div>
								<div class="mb-3">
									<label for="stock" class="form-label">Miktar
										<span class="text-danger">*</span>
									</label>
									<input type="number" class="form-control" placeholder="Miktar" id="stock" name="stock" value="<?php echo $stock;?>">
								</div>
								<div class="mb-3">
									<label for="price" class="form-label">Fiyat
										<span class="text-danger">*</span>
									</label>
									<input type="number" class="form-control" placeholder="Fiyat" id="price" name="price" value="<?php echo $price;?>">
								</div>
							</div>
							<div class="card-footer">
								<button type="submit" name="update" class="btn btn-primary w-100">Düzenle</button>
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