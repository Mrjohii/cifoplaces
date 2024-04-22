<!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="UTF-8"> 
		<title>Crear lugar</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="descripcion" content="Crear nuevo lugar  <?= APP_NAME?>">
		<meta name="author" content="Jonathan Ortega">
		
		<link rel="shortcut icon" href="/favicon.ico" type="image/png">

		<script	src="/js/preview.js"></script>
				
		<?= (TEMPLATE)::getCss()?>
		
	</head>
		
	<body>
		<?=(TEMPLATE)::getLogin()?>
		<?=(TEMPLATE)::getHeader('Nuevo lugar')?>
		<?=(TEMPLATE)::getMenu()?>
		<?=(TEMPLATE)::getFlashes()?>
	<main>
		<h2>Nuevo lugar</h2>
		<div class="flex-container">
		<section class="flex1">
    		<form method="post" action="/Place/store" enctype="multipart/form-data">
    			
				<label>Nombre</label>
    			<input type="text" minlength="1" maxlength="64" name="name" value ="<?= old('name')?>">
    			<br>
				<label>Tipo</label>
    			<input type="text" minlength="1" maxlength="64" name="type" value ="<?= old('type')?>">
    			<br>
				<label>Localización</label>
    			<input type="text" minlength="1" maxlength="64" name="location" value ="<?= old('location')?>">
    			<br>
    			<label>Descripcion</label>
    			<input type="text" minlength="1" maxlength="64" name="description" value ="<?= old('decription')?>">
    			<br>
    			<label>Portada</label>
    			<input type="file" name="cover" accept="image/*" id="file-with-preview">
    			<br>
    			<input type="submit" class="button" name="guardar" value="Guardar">
    		</form>
			</section>
			
			<figure class="flex1 centrado">
				<img src="<?= PLACES_IMAGE_FOLDER.'/'.DEFAULT_PLACE_IMAGE ?>" id="preview-image"
					class="cover" alt="Previsualización de la portada">
				<figcaption>Previsualización de la portada</figcaption>
			</figure>
			</div>
			
		
		<div class="centrado">
			<a class="button" onclick="history.back()">Atrás</a>
		</div>
		
	</main>
		  
	</body>
</html>