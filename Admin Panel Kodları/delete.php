<?php
ob_start();
$database = new PDO("mysql:host=localhost;dbname=stockapp;charset=utf8mb4", "root", "");
session_start();

if(!isset($_SESSION['user_id'])){
	header('location:login.php');
}
else{
	if(!isset($_GET['stock_id'])){
		header("location:index.php");
	}
	else{
		$data = array(
			':stock_id'  => trim(htmlspecialchars($_GET['stock_id']))
		);
		$query = '
		DELETE FROM stocks
		WHERE stock_id = :stock_id';
		$statement = $database->prepare($query);
		if($statement->execute($data)){
			header("location:dashboard.php");
		}
		else{
			header("location:index.php");
		}
	}
}
