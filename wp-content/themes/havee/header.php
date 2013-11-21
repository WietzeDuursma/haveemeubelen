<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title() ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php wp_enqueue_style('smallbusiness-style', get_stylesheet_uri(), false, '1.01');?>
		

<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_uri(); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/stylesheets/foundation.css" />


<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />



	

<?php wp_head(); ?>



</head>



<body <?php body_class(); ?>>



	<!--wrapper-->
<?php 	  if (is_page()==1 or is_search()==1 ):?> 
	<div id="wrapper_sm">
<?php elseif (is_home()==1 ):?>
	<div id="wrapper">
<?php else:?>
	<div id="wrapper_sm">
<?php endif;?>
	

	<!--headercontainer-->

	<div id="header_container" class="row">

	

		<!--header-->

		<div id="header2" >



				<?php if ( ( of_get_option('logo_image') ) != '' ) { ?>

		<div id="logo" class="four columns"><a href="<?php echo home_url(); ?>" title="<?php bloginfo('description'); ?>"><img src="<?php echo of_get_option('logo_image'); ?>" alt="<?php echo of_get_option('footer_cr'); ?>" /></a></div><!--logo end-->

	<?php } else { ?>

			<div id="logo2" class="four columns"><a href="<?php echo home_url(); ?>" title="<?php bloginfo('description'); ?>"><?php bloginfo( 'name' ); ?></a></div><!--logo end-->

	<?php } ?>

			

			<!--menu-->

			

		<div id="menubar" class="eight columns" >

	

	<?php $navcheck = wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary', 'menu_class' => 'dropdown dropdown-horizontal reset' ,'fallback_cb' => '', 'echo' => false ) );  ?>

	

	 <?php  if ($navcheck == '') { ?>

	

	<ul class="dropdown dropdown-horizontal reset">

		<li class="page_item"><a href="<?php echo home_url(); ?>" title="Home"><?php _e( 'Home', 'target' ); ?></a></li>				

		<?php wp_list_pages('title_li=&sort_column=menu_order'); ?>



	</ul>

<?php } else echo($navcheck); ?> 



	</div>	<!--menu end-->

		

	



			

			<div class="clear"></div>

			

		</div><!-- header end-->

		

	</div><!--header container end-->	

		

