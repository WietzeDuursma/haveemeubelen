<?php get_header(); ?>

		

		<div id="subhead_container" >



		<div id="subhead_wrapper" class="row">		

			<div id="subhead" class="seven columns">

		

		<h1><?php the_title(); ?></h1>

			

			</div>

			

		<div id="search-header" class="four columns hide-on-phones"><?php get_search_form(); ?></div><!--search header end-->

			

				<div class="clear"></div>

			

		</div>

	</div>		

	

		<!--content-->

		<div id="content_container" class="row">

			

			

		

				

		



				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

					

					<div class="post-entry eight columns" >



						<?php the_content(); ?>

						<div class="clear"></div>

						<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'target' ), 'after' => '' ) ); ?>

						

					</div><!--post-entry end-->

					
					<div class="post-entry three columns hide-on-phones" >
                    <?php get_sidebar(); ?>
                    </div>
					



<?php endwhile; ?>









	

</div>

<!--content end-->

	

</div>

<!--wrapper end-->

<?php get_footer(); ?>