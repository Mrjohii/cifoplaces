<!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="UTF-8"> 
		<title>Editar perfil</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="descripcion" content=" <?= APP_NAME?>">
		<meta name="author" content="Jonathan Ortega">
		
		<link rel="shortcut icon" href="/favicon.ico" type="image/png">
		<script src="/js/preview.js"></script>
		
		
		<?= (TEMPLATE)::getCss()?>
		
	</head>
		
	<body>
		<?=(TEMPLATE)::getLogin()?>
		<?=(TEMPLATE)::getHeader()?>
		<?=(TEMPLATE)::getMenu()?>
		<?=(TEMPLATE)::getFlashes()?>
	<main>
		<h2>Home de <?= $user->displayname?></h2>
		
		<div class="flex-container">
		<section class="flex1">
		<form method="post" action="/User/update"  enctype="multipart/form-data">
			 <!--  input oculto que contiene el Id del lugar a actualizar -->
			 <input type="hidden" name="id" value="<?=$user->id?>">
			 
			<!--  Resto del formulario... -->
			<label>Nombre:</label>
			<input type="text" name="displayname" value="<?= $user->displayname ?>">
			<br>
			<label>Email:</label>
			<input type="text" name="email" value="<?= $user->email?>">
			<br>
			<label>Telefono</label>
			<input type="number" name="phone" value="<?= $user->phone?>">
			<br>
		  	<label>Imagen de perfil:</label>
		  	<input type="file" name="picture" accept="image/*" id="file-with-preview">
		  	<br>
		  	<label>Eliminar portada</label>
		  	<input type="checkbox" name="eliminarportada">
		  	<br>
		  	<input class="button" type="submit" name="actualizar" value="Actualizar">
		</form>
		</section>
			<figure class="flex1 centrado">
				<img src="<?= USERS_IMAGE_FOLDER.'/'.($user->picture ?? DEFAULT_USER_IMAGE)?>"
					class="cover" id="preview-image" alt="Portada de <?= $user->name?>">
			</figure>
		</div>

		<div class="centrado">
			<a class="button" onclick="history.back()">Atrás</a>
			<a class="button" href="/User/delete/<?=$user->id ?>">Borrado</a>
		</div>
	</main>

	</body>
</html>