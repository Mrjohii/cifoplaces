<!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="UTF-8"> 
		<title>Borrar lugar</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="descripcion" content="<?= APP_NAME?>">
		<meta name="author" content="Jonathan Ortega">
		
		<link rel="shortcut icon" href="/favicon.ico" type="image/png">	
		<?= (TEMPLATE)::getCss()?>
		
	</head>
		
	<body>
		<?=(TEMPLATE)::getLogin()?>
		<?=(TEMPLATE)::getHeader()?>
		<?=(TEMPLATE)::getMenu()?>
		<?=(TEMPLATE)::getFlashes()?>
	<main>
		<h2>Borrar libro</h2>
		
		<form method="post" action="/Place/destroy">
			  <p>Confirmar el borrado del lugar <strong><?= $place->name?></strong></p>
			  
			  <input type ="hidden" name="id" value="<?=$place->id ?>">

			  <input class="button" type="submit" name="borrar" value="Borrar"> 
		</form>
		
		<div class="centrado">
			<a class="button" onclick="history.back()">Atrás</a>
			<a class="button" href="/Place/list">Lista de lugares</a>
			<a class="button" href="/Place/show/<?=$place->id ?>">Detalles</a>
			<a class="button" href="/Place/edit/<?=$place->id?>">Edición</a>
		</div>
	</main>

	</body>
</html>