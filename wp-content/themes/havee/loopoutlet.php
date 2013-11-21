<?php /* If there are no posts to display, such as an empty archive page */ ?>

<?php if ( ! have_posts() ) : ?>

<h1><?php _e( 'Niets gevonden', 'target' ); ?></h1>

		<p><?php _e( 'Helaas, er is niets gevonden. Maak gebruik van het onderstaande zoekveld om uw zoekopdracht te specificeren.', 'target' ); ?></p>

		<?php get_search_form(); ?>



<?php endif; ?>



<!--loop starts here-->

<!--pagination-->

	<div class="navigation">

		<div class="alignright"><?php next_posts_link( __( '&#187;', 'target' ) ); ?></div>

		<div class="alignleft"><?php previous_posts_link( __( '&#171;', 'target' ) ); ?></div>

	</div>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>



<div id="postt-<?php the_ID(); ?>" <?php post_class(); ?>>
 <div id="loop">


			<div class="post-head">
           <h1><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'target' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
           <?php the_post_thumbnail( array(250,225), $attr ); ?>
			

	

			<?php if ( get_the_title() == '' ) { _e( '(No title)', 'target' ); } else { the_title(); } ?></a></h1>

			
			<div class="hide-on-phones"><span class="readmore"><?php the_subtitle(); ?></span></div>
            <div class="show-on-phones"><span class="onr"><?php the_subtitle(); ?></span></div>
			</div><!--post-heading end-->

			

			

			<div class="clear"></div>



<div class="post-entry">



	<?php if ( is_archive() || is_search() ) :  ?>

		

			
		
    			
			<div class="clear"></div>

			<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'target' ), 'after' => '' ) ); ?>

			

	<?php else : ?>

	

 	<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) { the_post_thumbnail(array(620,240), array("class" => "alignleft post_thumbnail")); } ?>

	

	

			<?php the_content( __( '<span class="read-more">Read More</span>', 'target' ) ); ?>

			<div class="clear"></div>

			<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'target' ), 'after' => '' ) ); ?>

	<?php endif; ?>

	

	<!--clear float--><div class="clear"></div>

				

				

				</div><!--post-entry end-->





		<?php comments_template( '', true ); ?>


</div><!--loop-->
</div> <!--post end-->



<?php endwhile; // End the loop. Whew. ?>



<!--pagination-->

	<div class="navigation">

		<div class="alignright"><?php next_posts_link( __( '&#187;', 'target' ) ); ?></div>

		<div class="alignleft"><?php previous_posts_link( __( '&#171;', 'target' ) ); ?></div>

	</div>