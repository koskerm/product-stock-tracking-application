<?php
	ob_start();
	$database = new PDO("mysql:host=localhost;dbname=stockapp;charset=utf8mb4", "root", "");
	session_start();

	if(!isset($_SESSION['user_id'])){
		header('location:login.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Stok APP - Arama</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/all.min.css" rel="stylesheet">
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light border-bottom bg-white">
		<div class="container">
			<a class="navbar-brand" href="index.php">Stok APP / Arama</a>
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
			<div class="row">
				<div class="col-12">
					<div class="mb-3 d-flex justify-content-between">
						<h5 class="">Ürünler</h5>
						<form method="POST" action="search.php">
							<input type="text" class="form-control" id="search" name="search" placeholder="Stok ara">
						</form>
					</div>
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th scope="col">ID</th>
									<th scope="col">Başlık</th>
									<th scope="col">Açıklama</th>
									<th scope="col">Miktar</th>
								</tr>
							</thead>
							<tbody>
							<?php
								$search = "%".$_POST['search']."%";
								$query = "
								SELECT * FROM stocks
								WHERE title LIKE :search 
								OR description LIKE :search 
								OR stock_id LIKE :search
								";
								$statement = $database->prepare($query);
						        $statement->execute([':search' => $search]);
								if($statement->rowCount() > 0){
									$result = $statement->fetchAll();
									foreach($result as $row){
										echo '
											<tr>
												<td>'.$row['stock_id'].'</td>
												<td>'.substr($row['title'], 0,25).'...</td>
												<td>'.substr($row['description'], 0,50).'...</td>
												<td>'.$row['stock'].'</td>
											</tr>
										';
									}
								}
								else{
									echo '
										<tr>
											<td>Ürün bulunmuyor!</td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									';
								}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="js/jquery-3.6.4.min.js" type="text/javascript"></script>
	<script src="js/popper.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>