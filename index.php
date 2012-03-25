<?php get_header(); ?>
<?php
////////////////////////////////
// KIS THUMBNAIL
////////////////////////////////
echo "KIS THUMBNAIL - kis_thumbnail_crop <br />";
echo kis_thumbnail_crop(1, 600, 200, "tag", array("class"=>"thumbnail"));

kis_social_google_plusone_button();
?>

<h2>Ajax Form</h2>
<form id="ajaxForm" method="post" action="<?php bloginfo("url"); ?>/wp-admin/admin-ajax.php">
	<div>
		<label>Nome: </label><br />
		<input type="text" name="nome" />
	</div>
	
	<div>
		<label>E-mail: </label><br />
		<input type="text" name="email" />
	</div>
	
	<div>
		<input type="submit" value="Enviar" />
	</div>
</form>

<h2>Placeholder</h2>
<form id="formPlaceholder" method="post" action="<?php bloginfo("url"); ?>/wp-admin/admin-ajax.php">
	<div>
		<label>Nome: </label><br />
		<input type="text" name="nome" data-placeHolder="Digite seu nome" />
	</div>
	
	<div>
		<label>E-mail: </label><br />
		<input type="text" name="email" data-placeHolder="Digite seu e-mail" />
	</div>
	
	<div>
		<input type="submit" value="Enviar" />
	</div>
</form>

<h2>Ajax Form with Placeholder</h2>
<form id="ajaxFormWithPlaceholder" method="post" action="<?php bloginfo("url"); ?>/wp-admin/admin-ajax.php">
	<div>
		<label>Nome: </label><br />
		<input type="text" name="nome" data-placeHolder="Digite seu nome" />
	</div>
	
	<div>
		<label>E-mail: </label><br />
		<input type="text" name="email" data-placeHolder="Digite seu e-mail" />
	</div>
	
	<div>
		<input type="submit" value="Enviar" />
	</div>
</form>
<?php get_footer(); ?>