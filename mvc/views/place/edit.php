<!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="UTF-8"> 
		<title>Editar libro</title>
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
		<h2>Edición del lugar <?= $place->name?></h2>
		
		<div class="flex-container">
		<section class="flex1">
		<form method="post" action="/Place/update"  enctype="multipart/form-data">
			 <!--  input oculto que contiene el Id del lugar a actualizar -->
			 <input type="hidden" name="id" value="<?= $place->id?>">
			 
			<!--  Resto del formulario... -->
			<label>Nombre:</label>
			<input type="text" name="name" value="<?= $place->name ?>">
			<br>
			<label>Tipo</label>
			<input type="text" name="type" value="<?= $place->type ?>">
			<br>
			<label>Localización:</label>
			<input type="text" name="location" value="<?= $place->location ?>">
			<br>
			<label>Descripción:</label>
			<input type="text" name="description" value="<?= $place->description ?>">
			<br>
		  	<label>Portada</label>
		  	<input type="file" name="cover" accept="image/*" id="file-with-preview">
		  	<br>
		  	<label>Eliminar portada</label>
		  	<input type="checkbox" name="eliminarportada">
		  	<br>
		  	<input class="button" type="submit" name="actualizar" value="Actualizar">
		</form>
		</section>
			<figure class="flex1 centrado">
				<img src="<?= PLACES_IMAGE_FOLDER.'/'.($place->cover ?? DEFAULT_PLACE_IMAGE) ?>"
					class="cover" id="preview-image" alt="Portada de <?= $place->name?>">
				<figcaption> Portada de <?= "$place->name"?></figcaption>
			</figure>
		</div>

		<div class="centrado">
			<a class="button" onclick="history.back()">Atrás</a>
			<a class="button" href="/Place/list">Lista de lugares</a>
			<a class="button" href="/Place/show/<?=$place->id ?>">Detalles</a>
			<a class="button" href="/Place/delete/<?=$place->id ?>">Borrado</a>
		</div>
	</main>

	</body>
</html>