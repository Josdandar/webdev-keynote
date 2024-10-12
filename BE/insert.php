<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Configurar la conexión a la base de datos
$servername = "localhost";
$username = "root";  // Usuario por defecto de MySQL
$password = "";  // Sin contraseña por defecto en XAMPP
$dbname = "webdev";  // El nombre de la base de datos que creaste

// Crear la conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(array('error' => "Conexión fallida: " . $conn->connect_error)));
}

// Obtener los datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que se recibieron los datos correctamente
if ($data) {
    // Extraer y sanitizar los valores del objeto
    $nombre = $conn->real_escape_string($data['name']);  // Sanitizar entrada para evitar SQL injection
    $edad = intval($data['age']);
    $correo = $conn->real_escape_string($data['email']);  // Sanitizar entrada

    // Validar que los campos no estén vacíos y sean correctos
    if (!empty($nombre) && filter_var($correo, FILTER_VALIDATE_EMAIL) && $edad > 0) {
        // Consulta SQL para insertar los datos en la tabla 'usuarios'
        $sql = "INSERT INTO usuarios (nombre, edad, correo) VALUES ('$nombre', '$edad', '$correo')";

        if ($conn->query($sql) === TRUE) {
            // Enviar una respuesta de éxito
            echo json_encode(array('mensaje' => 'Usuario registrado correctamente en la base de datos.'));
        } else {
            // Enviar un mensaje de error si la inserción falla
            echo json_encode(array('error' => 'Error al registrar el usuario: ' . $conn->error));
        }
    } else {
        // Si los datos no son válidos, enviar un mensaje de error
        http_response_code(400);
        echo json_encode(array('error' => 'Datos inválidos. Verifica que todos los campos sean correctos.'));
    }
} else {
    // Si no se recibieron datos, enviar un error
    http_response_code(400);
    echo json_encode(array('error' => 'No se recibieron datos.'));
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
