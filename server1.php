<?php
session_start();

// inicializando variables
$nombre = "";
$email = "";
$errors = array(); 

// conectarse a la base de datos
$db = new mysqli('localhost', 'root', '', 'login');
if ($db->connect_error) {
    die("Conexión fallida: " . $db->connect_error);
}

// REGISTRAR USUARIO
if (isset($_POST['reg_usuarios'])) {
    // recibimos todos los valores de entrada del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña_1 = $_POST['contraseña_1'];
    $contraseña_2 = $_POST['contraseña_2'];

    // validación del formulario
    if (empty($nombre)) { array_push($errors, "Se requiere nombre de usuario"); }
    if (empty($email)) { array_push($errors, "Correo electrónico es requerido"); }
    if (empty($contraseña_1)) { array_push($errors, "Se requiere contraseña"); }
    if ($contraseña_1 != $contraseña_2) {
        array_push($errors, "Las dos contraseñas no coinciden");
    }

    // revisa la base de datos para asegurarte de que no exista un usuario con el mismo nombre de usuario y/o correo electrónico
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE nombre=? OR email=? LIMIT 1");
    $stmt->bind_param("ss", $nombre, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) { // si el usuario existe
        if ($user['nombre'] === $nombre) {
            array_push($errors, "Nombre de usuario ya existe");
        }
        if ($user['email'] === $email) {
            array_push($errors, "El email ya existe");
        }
    }

    // Finalmente, registramos al usuario si no hay errores en el formulario
    if (count($errors) == 0) {
        $hash = password_hash($contraseña_1, PASSWORD_DEFAULT); // Cifrar la contraseña

        $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $hash);
        $stmt->execute();
        
        $_SESSION['nombre'] = $nombre;
        $_SESSION['success'] = "Ahora está conectado";
        header('location: index.php');
    }
}

// INICIA SESIÓN DE USUARIO
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
