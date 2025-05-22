<?php 
header('Content-Type: text/plain');
echo "¡Funciona!";
file_put_contents('test.txt', 'Prueba de escritura');
?>