<?php
require_once 'db.php';
/*// Incluir el archivo que contiene la conexión a la base de datos y las funciones necesarias


// Temporizador interno que se ejecuta continuamente
while (true) {
    // Obtener la hora actual menos 5 minutos
    $limit_timestamp = time() - (5 * 60);

    // Consultar tokens activos que han superado el límite de tiempo
    $query = "SELECT * FROM usuarios_token WHERE Estado = 'Activo' AND Fecha < '$limit_timestamp'";
    $result = NonQuery($query);

    // Actualizar los tokens encontrados a estado 'Inactivo'
    foreach ($result as $row) {
        $token_id = $row['ID']; // Suponiendo que 'ID' es la columna que identifica de forma única cada token en tu tabla
        $update_query = "UPDATE usuarios_token SET Estado = 'Inactivo' WHERE ID = '$token_id'";
        NonQuery($update_query);
    }

    // Esperar 5 minutos antes de volver a verificar
    sleep(5 * 60);
}*/

// Temporizador interno que se ejecuta continuamente
/*while (true) {
    // Obtener la hora actual menos 10 días (864000 segundos)
    $limit_timestamp = time() - (10 * 24 * 60 * 60);

    // Consultar tokens activos que han superado el límite de tiempo y eliminarlos
    $query_delete = "DELETE FROM usuarios_token WHERE Fecha < '$limit_timestamp'";
    NonQuery($query_delete);

    // Consultar tokens activos que han superado el límite de tiempo y marcarlos como inactivos
    $query_update = "UPDATE usuarios_token SET Estado = 'Inactivo' WHERE Estado = 'Activo' AND Fecha < '$limit_timestamp'";
    NonQuery($query_update);

    // Esperar 5 minutos antes de volver a verificar
    sleep(5 * 60);
}*/



// Definir constantes
define('LIMIT_TIME_DAYS', 10);

function deleteExpiredTokens($limitTimestamp) {
    $query = "DELETE FROM usuarios_token WHERE Fecha < ?";
    $stmt = $GLOBALS['db']->prepare($query);
    if ($stmt) {
        $stmt->bind_param('i', $limitTimestamp);
        if (!$stmt->execute()) {
            logError('Error executing delete query: ' . $stmt->error);
        } else {
            echo "Expired tokens deleted successfully.\n";
        }
        $stmt->close();
    } else {
        logError('Error preparing delete query: ' . $GLOBALS['db']->error);
    }
}

function markTokensInactive($limitTimestamp) {
    $query = "UPDATE usuarios_token SET Estado = 'Inactivo' WHERE Estado = 'Activo' AND Fecha < ?";
    $stmt = $GLOBALS['db']->prepare($query);
    if ($stmt) {
        $stmt->bind_param('i', $limitTimestamp);
        if (!$stmt->execute()) {
            logError('Error executing update query: ' . $stmt->error);
        } else {
            echo "Tokens marked as inactive successfully.\n";
        }
        $stmt->close();
    } else {
        logError('Error preparing update query: ' . $GLOBALS['db']->error);
    }
}

function logError($message) {
    error_log($message);
}

function processTokens() {
    $limitTimestamp = time() - (LIMIT_TIME_DAYS * 24 * 60 * 60);
    deleteExpiredTokens($limitTimestamp);
    markTokensInactive($limitTimestamp);
}

// Ejecutar la limpieza de tokens
processTokens();
