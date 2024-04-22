<!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="UTF-8"> 
		<title>Editar fotografia</title>
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
		<h2>Edición de fotografia <?= $photo->name?></h2>
		
		<div class="flex-container">
		<section class="flex1">
		<form method="post" action="/Photo/update"  enctype="multipart/form-data">
			 <!--  input oculto que contiene el Id del lugar a actualizar -->
			 <input type="hidden" name="id" value="<?= $photo->id?>">
			 
			<!--  Resto del formulario... -->
			<label>Nombre:</label>
			<input type="text" name="name" value="<?= $photo->name ?>">
			<br>
			<label>Fecha</label>
			<input type="text" name="date" value="<?= $photo->date ?>">
			<br>
			<label>Time:</label>
			<input type="text" name="time" value="<?= $photo->time?>">
			<br>
			<label>Descripción:</label>
			<input type="text" name="description" value="<?= $photo->description ?>">
			<br>
		  	<label>Portada</label>
		  	<input type="file" name="photo" accept="image/*" id="file-with-preview">
		  	<br>
		  	<label>Eliminar fotografia</label>
		  	<input type="checkbox" name="eliminarportada">
		  	<br>
		  	<input class="button" type="submit" name="actualizar" value="Actualizar">
		</form>
		</section>
			<figure class="flex1 centrado">
				<img src="<?= PHOTOS_IMAGE_FOLDER.'/'.($photo->file ?? DEFAULT_PHOTO_IMAGE) ?>"
					class="cover" id="preview-image" alt="Fotografia <?= $photo->name?>">
			</figure>
		</div>

		<div class="centrado">
			<a class="button" onclick="history.back()">Atrás</a>
			<a class="button" href="/Place/list">Lista de lugares</a>
			<a class="button" href="/Photo/show/<?=$photo->id ?>">Detalles</a>
			<a class="button" href="/Photo/delete/<?=$photo->id ?>">Borrado</a>
		</div>
	</main>

	</body>
</html>