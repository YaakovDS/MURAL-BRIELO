<?php
$host = 'sql105.infinityfree.com';
$user = 'if0_40279055';
$pass = 'th96857412';
$db = 'if0_40279055_MURAL';


$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
die('Erro ao conectar: ' . $conn->connect_error);
}
?>