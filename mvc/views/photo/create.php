<!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="UTF-8"> 
		<title>Nueva fotografia</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="descripcion" content="Nueva fotografia <?= APP_NAME?>">
		<meta name="author" content="Jonathan Ortega">
		
		<link rel="shortcut icon" href="/favicon.ico" type="image/png">

		<script	src="/js/preview.js"></script>
				
		<?= (TEMPLATE)::getCss()?>
		
	</head>
		
	<body>
		<?=(TEMPLATE)::getLogin()?>
		<?=(TEMPLATE)::getHeader('Nueva fotografia')?>
		<?=(TEMPLATE)::getMenu()?>
		<?=(TEMPLATE)::getFlashes()?>
	<main>
		<h2>Nueva fotografia</h2>
		<div class="flex-container">
		<section class="flex1">
    		<form method="post" action="/Photo/store" enctype="multipart/form-data">
				 <!--  input oculto que contiene el Id del libro a actualizar -->
				 <input type="hidden" name="idplace" value="<?=$place->id?>">
    			
				<label>Nombre</label>
    			<input type="text" minlength="1" maxlength="64" name="name" value ="<?= old('name')?>">
    			<br>
    			<label>Descripción</label>
    			<input type="text" minlength="1" maxlength="64" name="description" value ="<?= old('description')?>">
    			<br>
				<label>Fecha</label>
    			<input type="date" name="date" value ="<?= old('date')?>">
    			<br>
				<label>Hora</label>
    			<input type="time" name="time" value ="<?= old('time')?>">
    			<br>
    			<label>fotografia</label>
    			<input type="file" name="photo" accept="image/*" id="file-with-preview">
    			<br>
    			<input type="submit" class="button" name="guardar" value="Guardar">
    		</form>
			</section>
			
			<figure class="flex1 centrado">
				<img src="<?= PHOTOS_IMAGE_FOLDER.'/'.DEFAULT_PHOTO_IMAGE ?>" id="preview-image"
					class="cover" alt="Previsualización de la fotografia">
				<figcaption>Previsualización de la fotografia</figcaption>
			</figure>
			</div>
			
		<div class="centrado">
			<a class="button" onclick="history.back()">Atrás</a>
		</div>
		
	</main>
		  
	</body>
</html>