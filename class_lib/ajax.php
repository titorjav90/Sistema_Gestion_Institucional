<?php
define('DB_SERVER', 'localhost');
define('DB_SERVER_USERNAME', 'root');
define('DB_SERVER_PASSWORD', 'usbw');
define('DB_DATABASE', 'php_factura');

$connexion = new mysqli(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);

$html = '';
$key = $_POST['key'];

$result = $connexion->query(
    "SELECT * FROM producto WHERE Cod LIKE '%".$key."%'"
);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {                
        $html .= '<div><a class="suggest-element" data="'.utf8_encode($row['Descrip']).'" id="'.$row['Cod'].'" price="'.$row['Precio'].'">'.utf8_encode($row['Descrip']).'</a></div>';
    }
}
echo $html;
?>