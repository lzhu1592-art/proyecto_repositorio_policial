<?php

function conectar(){
    $conn= new mysqli("localhost","root","","registro_policial");
    if($conn->connect_error)
        {
            die("Error al conectar". $conn->connect_error);
        }

        return $conn;
}

?>