<?php
session_start();

// ena aca se inicializando variables
$nombre = "";
$email = "";
$errors = array();

// en aca se conectarse a la base de datos
$db = mysqli_connect('localhost', 'root', '', 'login');

if (!$db) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Se CREA TABLA SI NO EXISTE
$tabla = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    img VARCHAR(255),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($db, $tabla);

// Se REGISTRA USUARIO
if (isset($_POST['reg_usuarios'])) {
    $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $contraseña_1 = mysqli_real_escape_string($db, $_POST['contraseña_1']);
    $contraseña_2 = mysqli_real_escape_string($db, $_POST['contraseña_2']);

    if (empty($nombre)) { array_push($errors, "Se requiere nombre de usuario"); }
    if (empty($email)) { array_push($errors, "Correo electrónico es requerido"); }
    if (empty($contraseña_1)) { array_push($errors, "Se requiere contraseña"); }
    if ($contraseña_1 != $contraseña_2) {
        array_push($errors, "Las dos contraseñas no coinciden");
    }

    $user_check_query = "SELECT * FROM usuarios WHERE nombre='$nombre' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if ($user['nombre'] === $nombre) {
            array_push($errors, "Nombre de usuario ya existe");
        }
        if ($user['email'] === $email) {
            array_push($errors, "El email ya existe");
        }
    }

    // en aca se Escoge una imagen aleatoria
    if (count($errors) == 0) {
        
        $imagenes_disponibles = ['image/image1.jpg', 'image/image2.jpg', 'image/image3.jpg'];
        $imagen = $imagenes_disponibles[array_rand($imagenes_disponibles)];

        // y se Guarda la contraseña tal como lo tenías
        $query = "INSERT INTO usuarios (nombre, email, contraseña, img) 
                  VALUES('$nombre', '$email', '$contraseña_1', '$imagen')";
        mysqli_query($db, $query);

        $_SESSION['nombre'] = $nombre;
        $_SESSION['success'] = "Ahora está conectado";
        header('location: index.php');
    }
}

// Se INICIA SESIÓN DE USUARIO
if (isset($_POST['login_usuarios'])) {
    $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
    $contraseña = mysqli_real_escape_string($db, $_POST['contraseña']);

    if (empty($nombre)) {
        array_push($errors, "Se requiere nombre de usuario");
    }
    if (empty($contraseña)) {
        array_push($errors, "Se requiere contraseña");
    }

    if (count($errors) == 0) {
        $query = "SELECT * FROM usuarios WHERE nombre='$nombre'";
        $results = mysqli_query($db, $query);
        $user = mysqli_fetch_assoc($results);

        if ($user && $user['contraseña'] === $contraseña) {
            $_SESSION['nombre'] = $nombre;
            $_SESSION['success'] = "Ahora está conectado";
            header('location: index.php');
        } else {
            array_push($errors, "Combinación incorrecta de nombre de usuario y contraseña");
        }
    }
}
?>
