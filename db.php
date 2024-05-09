<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "apidsa";
$port = "3306";

//conexión
$conexion = new mysqli($server, $user, $pass, $db, $port);
if($conexion -> connect_errno){
    die($conexion ->connect_error);
}

//guardar, modificar , eliminar
/*function NonQuery ($sqlstr, &$conexion = null ){
    if(!$conexion)global $conexion;
    $result = $conexion->query($sqlstr);
    return $conexion->affected_rows;
}*/

// Ejecutar consultas de modificación (INSERT, UPDATE, DELETE)
function NonQuery ($sqlstr, &$conexion = null ){
    if(!$conexion) global $conexion;
    if($conexion->query($sqlstr) === TRUE){
        return $conexion->affected_rows;
    } else {
        // Manejar el error de consulta
        echo "Error en la consulta: " . $conexion->error;
        return -1; // O cualquier otro valor que indique un error
    }
}

//select
function ObtenerRegistros ($sqlstr, &$conexion = null ){
    if(!$conexion)global $conexion;
    $result = $conexion->query($sqlstr);
    $resultArray = array();
    foreach($result as $registros){
        $resultArray[] = $registros;
    }
    return $resultArray;

}
    

