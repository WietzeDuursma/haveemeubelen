seee<?php get_header(); ?>



<div id="subhead_container" >



	<div id="subhead_wrapper" class="row">

			<div id="subhead" class="seven columns">

		

		<h1><?php printf( __( 'Gezocht naar: %s', 'target' ), '' . get_search_query() . '' ); ?></h1>

			

			</div>

			

			<div id="search-header" class="four columns hide-on-phones"><?php get_search_form(); ?></div><!--search header end-->

	

	<div class="clear"></div>	

			

		</div>

	</div>



	<!--inside container-->

	<div id="content_container" class="row">

		

		<div id="content" class="eight columns">

		

			<!-- left-col-->

			<div id="left-col">



			<?php if ( have_posts() ) : ?>

				

				<?php get_template_part( 'loop', 'search' ); ?>

<?php else : ?>



					<div class="post-head-notfound">

					

						<h1><?php _e( 'Niets gevonden', 'target' ); ?></h1>

					

					</div><!--head end-->

					

					<div class="helaas"><p><?php _e( 'Helaas, niets voldeed aan uw zoek criteria, probeert u het nog eens met andere zoekworden.', 'target' ); ?></p></div>

					

<?php endif; ?>



</div> <!--left-col end-->







	</div> 

</div> <!--content end-->

	

</div>

<!--wrapper end-->



<?php get_footer(); ?>

