<?php 
  session_start(); 

  if (!isset($_SESSION['nombre'])) {
  	$_SESSION['msg'] = "Debes iniciar sesión primero";
  	header('location: acceso.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['nombre']);
  	header("location: acceso.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Hogar</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" type="image/svg+xml" href="img/log3.jpg" />
</head>

<style>
	body {
		background-color: black;
		overflow: hidden;
	}
	.dots {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: transparent;
		z-index: -1;
	}
	.dot {
		position: absolute;
		width: 2px;
		height: 2px;
		background-color: #fff;
		border-radius: 50%;
		opacity: 1;
	}
	@keyframes animateDots {
		0% {
			transform: translateY(-10%);
			opacity: 1;
		}

		100% {
			transform: translateY(110vh);
			opacity: 0;
		}
	}
	@media only screen and (max-width: 768px) {
		.dots {
			display: none;
		}
	}
</style>

<body>
<div class="dots" id="dots-container"></div>
<!------------------------------------------------------------------------------>
<script>
  function generarLluviaContinua(velocidad) {
    const container = document.getElementById('dots-container');
    const numDots = 40;
    for (let i = 0; i < numDots; i++) {
      const dot = document.createElement('div');
      dot.classList.add('dot');
      dot.style.top = `${Math.random() * 100}%`;
      dot.style.left = `${Math.random() * 100}%`;
      container.appendChild(dot);
    }
    function moverPuntos() {
      const dots = document.querySelectorAll('.dot');
      dots.forEach(dot => {
        let top = parseFloat(dot.style.top) || 0;
        top += velocidad;
        dot.style.top = `${top}%`;
        if (top > 100) {
          dot.style.top = `${-Math.random() * 10}%`; 
          dot.style.left = `${Math.random() * 100}%`; 
        }
      });
      requestAnimationFrame(moverPuntos);
    }
    moverPuntos();
  }
  generarLluviaContinua(0.5); 
</script>
<!------------------------------------------------------------------------------------->

<body>
<div class="header">
<img src="img/ojo1.gif" alt="Imagen de perfil">
	<h2>Página de inicio</h2>
</div>
<!--Bienvenido a mi página de proyecto-->
<div class="content">
  	<!-- mensaje de notificación -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

    <!-- información del usuario registrado -->
    <?php  if (isset($_SESSION['nombre'])) : ?>
    	<p>Bienvenido <strong><?php echo $_SESSION['nombre']; ?></strong></p><br><hr>
	<div class="input-group">
      <button type="button" class="btn btn-logout" onclick="window.location.href='index.php?logout=1'">Cerrar Sesión</button>
    </div>	
    <?php endif ?>
</div>

</body>
</html>