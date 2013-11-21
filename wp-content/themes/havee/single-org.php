<?php get_header(); ?>

	

		<!--sub head container--><div id="subhead_container">



			<div id="subhead_wrapper"  class="row">

				<div id="subhead">

		

<h1><?php //the_title(); ?></h1>

			

			</div>

			

			<!--search header end-->

			

			<div class="clear"></div>

			

		</div>

	</div>	





	<!--content-->

<div id="content_container"  class="row">

	

	<div id="content"  >

		

		<div id="left-col" class="nine colums">



			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>	



			<div class="post-entry ">



			<div class="meta-data">

			

			 <?php the_post_thumbnail( array(450,400), $attr ); ?>
             <br><h1 style="font-weight: normal; line-height: 16px; color: #333333;"><?php the_title(); ?></h1>
			 
			

			</div><!--meta data end-->

			


						<?php the_content( __( '', 'target' ) ); ?>

						<div class="clear"></div>

			<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'target' ), 'after' => '' ) ); ?>

						

						<?php the_tags('Social tagging: ',' > '); ?>

						

				 

						

					</div><!--post-entry end-->

	



				



<?php endwhile; ?>


</div> <!--left-col end-->

<div class="post-entry three columns hide-on-phones"

<?php get_sidebar(); ?>

</div> <!-- end sidebar -->



	</div> 

</div>

<!--content end-->

	

</div>

<!--wrapper end-->



<?php get_footer(); ?>