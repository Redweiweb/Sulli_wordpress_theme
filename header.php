<?php

/**
 * Header file for the Sulli theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bigfa
 * @subpackage Sulli
 * @since 1.0.0
 */

?>
<!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link type="image/vnd.microsoft.icon" href="<?php echo get_template_directory_uri() ?>/build/img/favicon.png" rel="shortcut icon">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<header class="sulli--header">
		<div class="sulli--header__content">
			<a href="/"><img src="<?php echo get_template_directory_uri() ?>/build/img/logo.png" width="64"></a>
			<nav class="sulli--nav sulli">
				<?php if (has_nav_menu('primary')) : ?>
					<?php wp_nav_menu(array('theme_location' => 'primary', 'menu_class' => 'sulli--menu', 'container' => 'ul')); ?>
				<?php endif; ?>
			</nav>
		</div>
	</header>