<!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="UTF-8"> 
		<title>Lista de lugares</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="descripcion" content="Lista de lugares  <?= APP_NAME?>">
		<meta name="author" content="Jonathan Ortega">
		
		<link rel="shortcut icon" href="/favicon.ico" type="image/png">

		<?= (TEMPLATE)::getCss()?>
		
	</head>
		
	<body>
		<?=(TEMPLATE)::getLogin()?>
		<?=(TEMPLATE)::getHeader('Lista de lugares')?>
		<?=(TEMPLATE)::getMenu()?>
		<?=(TEMPLATE)::getFlashes()?>
	<main>
		<h2>Lista de lugares</h2>
		
		<?php 
		// coloca el formulario de filtro
        echo isset($filtro) ?
        	(TEMPLATE)::removeFilterForm($filtro, '/Place/list'):
        			     
            (TEMPLATE)::filterForm(
                '/Place/list',
            [
                 'Nombre' => 'nombre',
            	 'Tipo' => 'tipo',
                 'Localizacion' => 'localiazcion',
            			          
           ],[
				'Nombre' => 'nombre',
				'Tipo' => 'tipo',
				'Localizacion' => 'localiazcion',
                			          
          ] 
		   );
        			     
     ?>

	 <!-- Paginator -->
	 <?= $paginator->stats()?>
						
		<table>
			<tr>
				<th>Portada</th><th>Nombre</th><th>Tipo</th><th>Localización</th><th>Descripción</th><th>Operaciones</th>
			</tr>
			<?php foreach($places as $place){?>
				<tr>
					<td class="centrado">
						<img src= "<?= PLACES_IMAGE_FOLDER.'/'.($place->cover ?? DEFAULT_PLACE_IMAGE)?>"
							class="cover-mini" alt ="Portada de <?= $place->name ?>">
					</td>
					<td><?= $place->name?></td>
					<td><?= $place->type?></td>
					<td><?= $place->location?></td>
					<td><?= $place->description?></td>

					<td class="centrado">
					<a href='/Place/show/<?= $place->id ?>'>Ver</a>
					<?php if(Login::user()->id == $place->iduser){?>
					- <a href='/Place/edit/<?= $place->id?>'>Actualizar</a>
					<?php }?>
					<?php if(Login::user()->id == $place->iduser || Login::isAdmin()){?>
					- <a href='/Place/delete/<?= $place->id?>'>Borrar</a>
					<?php } ?>
				</td>
				</tr>
			<?php }?>
		</table>
		
		
		<div class="centrado">
			<?=$paginator->ellipsislinks()?>
		</div>
		
	</main>
	<?= Template::getFooter()?>

	</body>
</html>