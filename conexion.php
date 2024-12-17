<!DOCTYPE html>
<html>
<body>

<?php
session_start();

// inicializando variables xd
$nombre = "";
$email    = "";
$errors = array(); 

// conectarse a la base de datos gaa
$db = mysqli_connect('localhost', 'root', '', 'login');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}



$sql = "SELECT id, nombre, email, img FROM usuarios";
$result = $db->query($sql);


if ($result->num_rows > 0) {
    // datos de salida de cada fila 
    while($row = $result->fetch_assoc()) {
        print "<br> id: ". $row["id"]. "<br> - Name: ". $row["nombre"]. "<br> - Email: " . $row["email"] . "<br>";
      print "<img src=\"".$row["img"]."\">";
     
    }
} else {
    print "0 results";
}

$db->close();   
        ?> 

</body>
</html>