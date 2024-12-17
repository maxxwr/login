<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registro de usuarios</title>
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
  <div class="header">
  <img src="img/ojo1.gif" alt="Imagen Registro">
  	<h2>Registrarte</h2>
  </div>
	
  <form method="post" action="registro.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  	  <label>Nombre</label>
  	  <input type="text" name="nombre" value="<?php echo $nombre; ?>">
  	</div>
  	<div class="input-group">
  	  <label>Email</label>
  	  <input type="email" name="email" value="<?php echo $email; ?>">
  	</div>
  	<div class="input-group">
  	  <label>Contraseña</label>
  	  <input type="password" name="contraseña_1">
  	</div>
  	<div class="input-group">
  	  <label>Confirmar Contraseña</label>
  	  <input type="password" name="contraseña_2">
  	</div>
  	<div class="input-group">
  	  <button type="submit" class="btn btn-full" name="reg_usuarios">Registrarte</button>
  	</div>
  	<p>
	  ¿Ya eres usuario? 
  	</p><br>
	<hr>
	<div class="input-group">
      <button type="button" class="btn" onclick="window.location.href='acceso.php'">Iniciar sesión</button>
    </div>
	  
  </form>
</body>
</html>