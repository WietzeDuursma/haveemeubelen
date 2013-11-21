	<!--footer-->

	<div class="clear"></div>

		

		<div id="footer">
			<div class="row">
		

	<!--footer container--><div id="footer-container" >

		

		<div id="footer-widget" >

			

			<?php

			/* A sidebar in the footer? Yep. You can can customize

			 * your footer with four columns of widgets.

			 */

			get_sidebar( 'footer' );

			?>

			

			

			

			

			

			

				

			</div><!--footer info end-->

			

		</div><!-- footer container-->

		

	<div class="clear"></div>		

			
	
    <div id="copyright" class="twelve columns"><?php _e( 'Copyright', 'Havee' ); ?> <?php echo get_the_date( 'Y' ); ?> <?php echo of_get_option('footer_cr'); ?> | <?php _e( 'Havee Meubelen BV', 'target' ); ?>  </div>	
	</div>
    </div>

	

	<?php wp_footer(); ?>



</body>



</html>