<?php
//require_once ('cron.php');
require_once('utilidades.php');
use Firebase\JWT\JWT;

//utf8
function ConvertirUTF8($array){
    array_walk_recursive($array, function(&$item, $key){
        if (mb_detect_encoding($item, 'UTF-8', true)) {
            $item = mb_convert_encoding($item, 'UTF-8');
        }
    });
    return $array;
}
$postBody = null;
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

    } else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $usuarioid = isset($_POST['usuarioid']) ? $_POST['usuarioid'] : null;
        //el auth se utliza en el GET
        if ($var == 'auth') {
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
        $date = date("Y-m-d H:i");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha) VALUES ('$usuarioid','$token','$estado','$date')";
        NonQuery($query);

        $now = time();
        $key = bin2hex(openssl_random_pseudo_bytes(16));
        $payload = [
            'iat' => $now,
            'exp' => $now + 60,
            'data' => '1',
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');
        print_r($jwt);
        sleep(60); // Espera 60 segundos
        $query = "UPDATE usuarios_token SET Estado = 'Inactivo' WHERE Token = '$token'";
        NonQuery($query);
        }else {
            //recibimos los datos enviados
            $postBody = file_get_contents("php://input");

            //verificar si hay datos recibidos
            if ($postBody !== null) {
                //enviamos los datos al manejador
                $convert = json_decode($postBody, true);

                //verificar si la decodificación fue exitosa
                if (json_last_error() === JSON_ERROR_NONE) {
                    header('Content-Type: application/json');
                    switch ($var) {
                        case "productos":
                            CrearProducto($convert);
                            http_response_code(200);
                            break;
                        default:
                            echo "No existe la ruta";
                            break;
                    }
                } else {
                    http_response_code(400); // Si la decodificación JSON falla
                }
            } else {
                http_response_code(400); // Si no hay datos enviados
            }
        }
        
    } else {
        http_response_code(405);
    }
}

// Función de ejemplo para obtener el ID del usuario (debes implementarla según tus necesidades)
function obtener_id_del_usuario() {
    // Esta es solo una implementación de ejemplo, debes reemplazarla con la forma en que obtienes el ID del usuario en tu aplicación
    return isset($_POST['usuarioid']) ? $_POST['usuarioid'] : null;
}
        
    
    



    
    
    