<!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="UTF-8"> 
		<title>Detalles de la fotografia</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="descripcion" content=" <?= APP_NAME?>">
		<meta name="author" content="Jonathan Ortega">
		
		<link rel="shortcut icon" href="/favicon.ico" type="image/png">
		<?= (TEMPLATE)::getCss()?>
		
	</head>
		
	<body>
		<?=(TEMPLATE)::getLogin()?>
		<?=(TEMPLATE)::getHeader('')?>
		<?=(TEMPLATE)::getMenu()?>
		<?=(TEMPLATE)::getFlashes()?>
	<main>
	<div class="flex-container">
		<section class="flex1"> 
		 <h2><?= $photo->name ?></h2>
		 
		 <p><strong>Nombre:</strong>    			<?= $place->name?></p>
		 <p><strong>Fecha:</strong>    			    <?= $photo->date?></p>
		 <p><strong>Time:</strong>    			    <?= $photo->time?></p>
		 <p><strong>Decripción:</strong>    		<?= $place->description?></p>
		 
		 </section>
		 <figure class="flex1 centrado">
		 	<img src="<?= PHOTOS_IMAGE_FOLDER.'/'.($photo->file ?? DEFAULT_PHOTO_IMAGE)?>"
		 		class="cover" alt="Fotografia de<?= $photo->name ?>">
		 </figure>
	</div>

 
		 <div class="centrado">
		 	<a class="button" onclick="history.back()">Atrás</a>
			<?php if (Login::user()->id == $place->iduser){?>
		 	<a class="button" href="/Place/edit/<?= $place->id ?>">Editar</a>
		 	<a class="button" href="/Place/delete/<?= $place->id ?>">Borrado</a>
			<?php }?>
			 
		 </div>
	</main>
		  
	</body>
</html>