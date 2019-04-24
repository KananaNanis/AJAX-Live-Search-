<?php
//connect to database
define("servername","localhost");
define("username","root");
define("password","");
define("database","shoppn");
$mysqli = new mysqli(servername, username, password, database);
$search = $_GET['search'];
$start= $_GET['start'];
// validation to trim spaces
$search = trim(htmlspecialchars($search));
$start = filter_var($start, FILTER_VALIDATE_INT);
$like = '%' . strtolower($search) . '%'; 
$statement = $mysqli -> prepare('SELECT product_title, product_image, product_desc from products
    WHERE lower(product_title) LIKE ? ORDER BY INSTR(product_title, ?), product_title LIMIT 6 OFFSET ?');
if (
	//binding paramaters to prevent SQL Injection
	$statement && $statement -> bind_param('ssi', $like, $search, $start ) && $statement -> execute() && $statement -> store_result() && $statement -> bind_result($product_title, $product_image, $product_desc)
) {
	// create an array to store data for display
	$array = [];
	while ($statement -> fetch()) {
		$array[] = [
			'product_title' => $product_title,
			'product_image' => $product_image,
			'product_desc' => $product_desc
		];
	}
    // header('Content-Type: application/json');
	echo json_encode($array);
	exit();


}
