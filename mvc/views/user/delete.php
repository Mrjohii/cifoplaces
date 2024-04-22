<!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="UTF-8"> 
		<title>Borrar pERFIL</title>
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
		<h2>Borrar perfil</h2>
		
		<form method="post" action="/User/destroy">
			  <p>Confirmar el borrado del perfil<strong><?= $user->displayname?></strong></p>
			  
			  <input type ="hidden" name="id" value="<?=$user->id ?>">

			  <input class="button" type="submit" name="borrar" value="Borrar"> 
		</form>
		
		<div class="centrado">
			<a class="button" onclick="history.back()">Atrás</a>
			<a class="button" href="/User/show/<?=$user->id ?>">Detalles</a>
			<a class="button" href="/User/edit/<?=$user->id?>">Edición</a>
		</div>
	</main>

	</body>
</html>