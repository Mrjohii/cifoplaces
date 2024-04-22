<!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="UTF-8"> 
		<title>Detalles del lugar</title>
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
		 <h2><?= $place->name ?></h2>
		 
		 <p><strong>Nombre:</strong>    			<?= $place->name?></p>
		 <p><strong>Tipo:</strong>    		        <?= $place->type?></p>
		 <p><strong>Localización:</strong>    		<?= $place->location?></p>
		 <p><strong>Decripción:</strong>    		<?= $place->description?></p>
		 
		 </section>
		 <figure class="flex1 centrado">
		 	<img src="<?= PLACES_IMAGE_FOLDER.'/'.($place->cover ?? DEFAULT_PLACE_IMAGE)?>"
		 		class="cover" alt="Portada de  <?= $place->name ?>">
		 	<figcaption>Portada de <?= "$place->name "?></figcaption>
		 </figure>
	</div>

	<section>
		 	<h2>fotografias</h2>
			 <table>
			<tr>
				<th>Imagen</th><th>Título</th><th>Descripción</th><th>Fecha</th><th>Hora</th><th>Operaciones</th>
			</tr>
			<?php foreach($photos as $photo){?>
				<tr>
					<td class="centrado">
						<img src= "<?=PHOTOS_IMAGE_FOLDER.'/'.($photo->file ?? DEFAULT_PHOTO_IMAGE)?>"
							class="cover-mini" alt ="Fotografia de <?= $photo->iduser ?>">
					</td>
					<td><?= $photo->name?></td>
					<td><?= $photo->description?></td>
					<td><?= $photo->date?></td>
					<td><?= $photo->time?></td>
				<td class="centrado">
					<a href='/Photo/show/<?= $photo->id ?>'>Ver</a>
					- <a href='/Photo/edit/<?= $photo->id?>'>Actualizar</a>-
					<a href='/Photo/delete/<?= $photo->id?>'>Borrar</a>
					<?php } ?>
				</td>
				</tr>
		</table>
		 </section>
		 
	
		 <div class="centrado">
		 	<a class="button" onclick="history.back()">Atrás</a>
			<?php if (Login::user()->id == $place->iduser){?>
		 	<a class="button" href="/Place/edit/<?= $place->id ?>">Editar</a>
		 	<a class="button" href="/Place/delete/<?= $place->id ?>">Borrado</a>
			<?php }?>
			 <a class="button" href="/Photo/create/<?= $place->id ?>">Nueva fotografia</a>
		 </div>
	</main>
		  
	</body>
</html>