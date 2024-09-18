<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<?php 

	$body_classes = 'language--' . substr( get_bloginfo ( 'language' ), 0, 2 );

?>


<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class($body_classes); ?>>

	<?php wp_body_open(); ?>
	<?php get_template_part( 'templates/nav' );  ?>