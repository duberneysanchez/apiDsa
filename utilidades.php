<?php
require_once ('db.php');

function TodosLosProductos(){
    $Query = "SELECT * FROM productos";
    $respuesta = ObtenerRegistros($Query);
    return ConvertirUTF8($respuesta);
}

function ProductoPorID($id){
    $Query = "SELECT * FROM `productos` WHERE ProductoId = $id";
    $respuesta = ObtenerRegistros($Query);
    return ConvertirUTF8($respuesta);
}

function CrearProducto($array){
    //var_dump($array);
    $ProductoId = $array['ProductoId'];
    $Codigo = $array['Codigo'];
    $Nombre = $array['Nombre'];
    $Direccion = $array['Direccion'];
    $CodigoPostal= $array['CodigoPostal'];
    $Telefono = $array['Telefono'];
    $Genero = $array['Genero'];
    $FechaNacimiento = $array['FechaNacimiento'];
    $Correo = $array['Correo'];
    //var_dump($array); 
    $query = "INSERT INTO productos (ProductoId, Codigo, Nombre, Direccion, CodigoPostal, Telefono, Genero, FechaNacimiento, Correo)
    VALUES('$ProductoId', '$Codigo', '$Nombre', '$Direccion', '$CodigoPostal', '$Telefono', '$Genero', '$FechaNacimiento', '$Correo')";
    NonQuery($query);
    return true;
    
}
