<!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="UTF-8"> 
		<title>Home</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="descripcion" content="Home <?= APP_NAME?>">
		<meta name="author" content="Jonathan Ortega">
		
		<link rel="shortcut icon" href="/favicon.ico" type="image/png">
		<script	src="/js/Preview.js"></script>
		
		<?= (TEMPLATE)::getCss()?>
		<script>
			window.addEventListener('load',function(){
				inputEmail.addEventListener('change',function(){
					fetch("/User/registered/"+this.value,{
						"method":"GET"
					})
					.then(function(respuesta){
						return respuesta.json();
					})
					.then(function(json){
						if(json.status == 'OK')
							comprobacion.innerHTML = json.registered ?
												'El usuario ya existe.':'';
					else
						comprobacion.innerHTML = 'No se pudo comprobar';
					});
				})
			});
		</script>
	</head>
		
	<body>
		<?=(TEMPLATE)::getLogin()?>
		<?=(TEMPLATE)::getHeader()?>
		<?=(TEMPLATE)::getMenu()?>
		<?= (TEMPLATE)::getBreadCrumbs(["Registro" => "/user/create"]) ?>
		<?=(TEMPLATE)::getFlashes()?>
	<main>
 		<section>
 			<h2>Registro</h2>
 			
 			<div class="flex-container">
				<section class="flex2">
 				<form method="post" action="/User/store" enctype="multipart/form-data" >
 					<label>Nombre</label>
 					<input type="text" name="displayname"  required ="<?=old ('displayname')?>">
 					<br>
					<label>Email</label>
 					<input id="inputEmail" type="email" name="email" required value="<?=old ('email')?>"> <span id="comprobacion" ></span>
					<br>
					<label>Telefono</label>
 					<input type="text" name="phone" required value="<?=old ('phone')?>">
 					<br>
 					<label>Password</label>
 					<input type="password" name="password" required value="<?=old ('password')?>">
 					<br>
 					<label>Repetir Password</label>
 					<input type="password" name="repeatpassword" required value="<?=old ('repeatpassword')?>">
 					<br>
 					<label>Imagen de perfil</label>
 					<input type="file" name="picture" accept="image/*" id="file-with-preview">
 					<br>

 					<input type="submit" class="button" name="enviar" value="Enviar">
 				</form>
				</section>
			 
 				<figure class="flex1 centrado">
 					<img src="<?= USERS_IMAGE_FOLDER.'/'.DEFAULT_USER_IMAGE ?>" id="preview-image"
 						class="cover" alt="Previsualización de la imagen del perfil">
 				</figure>
 			</div>

			<div class="centrado">
 				<a class="button" onclick="history.back()">Atrás</a>
 			</div>
 		</section>
	</main>
	<?= Template::getFooter()?>

	</body>
</html>


