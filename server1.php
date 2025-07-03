<?php
session_start();

// se inicia variables
$nombre = "";
$email = "";
$errors = array(); 

// se conecta a la base de datos
$db = new mysqli('localhost', 'root', '', 'login');
if ($db->connect_error) {
    die("Conexión fallida: " . $db->connect_error);
}

// se crea la tabla usuarios si no existe
$tabla = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    img VARCHAR(255),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($db, $tabla);

// se REGISTRA USUARIO
if (isset($_POST['reg_usuarios'])) {
    
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña_1 = $_POST['contraseña_1'];
    $contraseña_2 = $_POST['contraseña_2'];

    // se valida el formulario
    if (empty($nombre)) { array_push($errors, "Se requiere nombre de usuario"); }
    if (empty($email)) { array_push($errors, "Correo electrónico es requerido"); }
    if (empty($contraseña_1)) { array_push($errors, "Se requiere contraseña"); }
    if ($contraseña_1 != $contraseña_2) {
        array_push($errors, "Las dos contraseñas no coinciden");
    }

    // se revisa si el usuario ya existe
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE nombre=? OR email=? LIMIT 1");
    $stmt->bind_param("ss", $nombre, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($user['nombre'] === $nombre) {
            array_push($errors, "Nombre de usuario ya existe");
        }
        if ($user['email'] === $email) {
            array_push($errors, "El email ya existe");
        }
    }

    // se registra usuario si no hay errores
    if (count($errors) == 0) {
        $hash = password_hash($contraseña_1, PASSWORD_DEFAULT);

       
        $imagenes = ["image1.jpg", "image2.jpg", "image3.jpg"];
        $img = "image/" . $imagenes[array_rand($imagenes)];

        $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, contraseña, img) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $email, $hash, $img);
        $stmt->execute();
        
        $_SESSION['nombre'] = $nombre;
        $_SESSION['success'] = "Ahora está conectado";
        header('location: index.php');
    }
}

// se INICIA SESIÓN
if (isset($_POST['login_usuarios'])) {
    $nombre = $_POST['nombre'];
    $contraseña = $_POST['contraseña'];

    if (empty($nombre)) {
        array_push($errors, "Se requiere nombre de usuario");
    }
    if (empty($contraseña)) {
        array_push($errors, "Se requiere contraseña");
    }

    if (count($errors) == 0) {
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE nombre=?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($contraseña, $user['contraseña'])) {
            $_SESSION['nombre'] = $nombre;
            $_SESSION['success'] = "Ahora está conectado";
            header('location: index.php');
        } else {
            array_push($errors, "Combinación incorrecta de nombre de usuario y contraseña");
        }
    }
}
?>
