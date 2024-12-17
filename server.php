<?php
session_start();

// inicializando variables
$nombre = "";
$email = "";
$errors = array();

// conectarse a la base de datos
$db = mysqli_connect('localhost', 'root', '', 'login');

// REGISTRAR USUARIO
if (isset($_POST['reg_usuarios'])) {
    // recibimos todos los valores de entrada del formulario
    $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $contraseña_1 = mysqli_real_escape_string($db, $_POST['contraseña_1']);
    $contraseña_2 = mysqli_real_escape_string($db, $_POST['contraseña_2']);

    // validación del formulario
    if (empty($nombre)) { array_push($errors, "Se requiere nombre de usuario"); }
    if (empty($email)) { array_push($errors, "Correo electrónico es requerido"); }
    if (empty($contraseña_1)) { array_push($errors, "Se requiere contraseña"); }
    if ($contraseña_1 != $contraseña_2) {
        array_push($errors, "Las dos contraseñas no coinciden");
    }

    // revisa la base de datos para asegurarte de que no exista un usuario con el mismo nombre de usuario y/o correo electrónico
    $user_check_query = "SELECT * FROM usuarios WHERE nombre='$nombre' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

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
        // Almacena la contraseña en texto plano (no recomendado para producción)
        $query = "INSERT INTO usuarios (nombre, email, contraseña) 
                  VALUES('$nombre', '$email', '$contraseña_1')";
        mysqli_query($db, $query);
        $_SESSION['nombre'] = $nombre;
        $_SESSION['success'] = "Ahora está conectado";
        header('location: index.php');
    }
}

// INICIA SESIÓN DE USUARIO
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
