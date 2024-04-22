 <!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="UTF-8"> 
		<title>Contacto</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="descripcion" content=" <?= APP_NAME?>">
		<meta name="author" content="Jonathan Ortega">
		
		<link rel="shortcut icon" href="/favicon.ico" type="image/png">
		
		<script src="https://www.google.com/recaptcha/api.js"></script>
				
		<?= (TEMPLATE)::getCss()?>
		
	</head>
		
	<body>
		<?=(TEMPLATE)::getLogin()?>
		<?=(TEMPLATE)::getHeader('Contacto')?>
		<?=(TEMPLATE)::getMenu()?>
		<?= (TEMPLATE)::getBreadCrumbs(["Contacto" => "/contacto"]) ?>
		<?=(TEMPLATE)::getFlashes()?>
	<main>
		
		<div class="flex-container">
			<section class="flex1">
			<h2>Contacto</h2>
			
			<p>Utiliza el formulario de contacto para enviar un mensaje al administrador </p>
			
			<form method ="POST" action="/contacto/send">
				<label>Email</label>
				<input type="email" name="email" required>
				<br>
				<label>Nombre</label>
				<input type="text" name="nombre" required>
				<br>
				<label>Asunto</label>
				<input type="text" name="asunto" required>
				<br>
				<label>Mensaje</label>
				<textarea name="mensaje" required></textarea>
				<br>
				<br>
				<div class="g-recaptcha"
					data-sitekey="6Lf6SaYpAAAAAA49-QaOfZPTN5DM9xEugo60EZzY">
				</div>
				
				<input class="button" type="submit" name="enviar" value="Enviar">
			</form>
			</section>
			
			<section class="flex1">
				<h2>Ubicación y mapa</h2>
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2985.640103091587!2d2.0540061593055747!3d41.55538912267976!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12a4952ef0b8c6e9%3A0xb6f080d2f180b111!2sCIFO%20Valles!5e0!3m2!1ses!2ses!4v1711095000514!5m2!1ses!2ses" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
				
				<h3>Datos</h3>
				<p><strong>Cifo Sabadell</strong> - Carreterea Nacional 150 km.15, 08227 Terrassa<br>
				Telefono : 93 736 29 10 
				<br>
				cifo_valles.soc@gencat.cat
				</p>
			
			</section>
		</div>
		
	</main>
	<?= Template::getFooter()?>

	</body>
</html>