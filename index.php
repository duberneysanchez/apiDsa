<?php

require_once('utilidades.php');
    //utf8
    function ConvertirUTF8($array){
        array_walk_recursive($array, function(&$item, $key){
            if (mb_detect_encoding($item, 'UTF-8', true)) {
                $item = mb_convert_encoding($item, 'UTF-8');

            }
        });
        return $array;
    }
    
if (isset($_GET['url'])) {
            $var = $_GET['url'];
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $numero = intval(preg_replace('/[^0-9]+/', '',$var), 10);
            print_r($numero);
    
            switch ($var) {
                case "productos":
                    $resp = TodosLosProductos();
                    print_r(json_encode($resp, JSON_UNESCAPED_UNICODE));
                    break;
                    case "productos/$numero":
                        $resp = ProductoPorID($numero);
                        print_r(json_encode($resp, JSON_UNESCAPED_UNICODE));
                        http_response_code(200);
                        break;
    
                default:

    }
    }else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //recibimos los datos enviados
        $postBody = file_get_contents("php://input");
        //print_r($postBody);
        //enviamos los datos al manejador
        $convert = json_decode($postBody, true);
        //delvovemos una respuesta 
         header('Content-Type: application/json');
         if(json_last_error()==0){
            //print_r($convert);
            switch ($var) {
                case "productos":
                     CrearProducto($convert);
                     http_response_code(200);
                break;
                default:
                    echo "No existe la ruta";
                    break;
            }

         }else{
             http_response_code(400);
         }
        
    }else {
        http_response_code(405);
    }
}
    
    



    
    
    