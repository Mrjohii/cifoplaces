<!DOCTYPE html> 
<html lang="es">
	<head>
		<meta charset="UTF-8"> 
		<title>Home</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="descripcion" content="Home <?= APP_NAME?>">
		<meta name="author" content="Jonathan Ortega">
		
		<link rel="shortcut icon" href="/favicon.ico" type="image/png">
		
		
		<?= (TEMPLATE)::getCss()?>
		
	</head>
		
	<body>
		<?=(TEMPLATE)::getLogin()?>
		<?=(TEMPLATE)::getHeader('Home')?>
		<?=(TEMPLATE)::getMenu()?>
		<?= (TEMPLATE)::getBreadCrumbs(["Perfil" => "/user/home"]) ?>
		<?=(TEMPLATE)::getFlashes()?>
	<main>
	
		
    	<div class="flex-container">
    		<section class="flex2">
    			<h2><?= "home de $user->displayname"?></h2>
    			<p><strong>Nombre:</strong>				<?= $user->displayname?></p>
    			<p><strong>Email:</strong>				<?= $user->email ?>
    			<p><strong>Teléfono:</strong>			<?= $user->phone?></p>
				 
    		</section>
    			<!--  Fotos de perfil -->
    			<figure class="flex2 centrado">
    				<img src="<?= USERS_IMAGE_FOLDER.'/'.($user->picture ?? DEFAULT_USER_IMAGE)?>"
    					class="conver" alt="Imagen de perfil de <?= $user->displayname ?> ">
    				<figcaption>Imagen de perfil de <?= $user->displayname ?></figcaption>    			
    			</figure>
    	</div>

		<a class="button" href="/User/edit"> Editar perfil</a>
		<a class="button" href="/User/delete/<?=$user->id ?>">Borrar perfil</a>
	
	</main>
	<?= Template::getFooter()?>

	</body>
</html>