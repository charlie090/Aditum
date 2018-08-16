<?php
$username = 'id6310769_momentum';
$password = 'kAnglee89CM';
$database = 'id6310769_momentum';
$hostname = 'localhost';

$stock_symbol = '1301.T';
try{
$dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
$stmt = "SELECT * FROM `price/volume`";
$qryResult = $dbh->prepare($stmt);
$qryResult->bindParam(':stock_symbol', $stock_symbol, PDO::PARAM_STR);
$qryResult->execute();
$result = $qryResult->fetchALL(PDO::FETCH_ASSOC);
echo json_encode($result);
} catch(PDOException $pdoe) {
 echo ($pdoe->getMessage());
 exit;
}
 ?>
