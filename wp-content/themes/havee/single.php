single page<?php get_header(); ?>

		

		<div id="subhead_container" >



		<div id="subhead_wrapper" class="row">		

			<div id="subhead" class="seven columns">

		

		

			

			</div>

			

		<div id="search-header" class="four columns hide-on-phones"><?php get_search_form(); ?></div><!--search header end-->

			

				<div class="clear"></div>

			

		</div>

	</div>		

	

		<!--content-->

		<div id="content_container" class="row">

			

			

		
<?php
foreach (get_the_category() as $category) {
	echo  $category->name .'<br>';
    if ( $category->name == 'S-outlet' ) {
        echo '1 *******<br />'; //Markup as you see fit
    }
	else if ( $category->name == 'MOL' ) {
        echo '2 *******<br />'; 
    }
	else if ( $category->name == 'Showroom Outlet' ) {
        echo 'Showroom Outlet *******<br />'; 
    }
}
	?>
				

		




				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					
					
                    
					

					<div class="post-entry nine columns" >
						<div class="kaderinhoud" >

						<div class="hide-on-phones"><?php the_post_thumbnail('large');   ?></div>
                        <div class="show-on-phones"><?php the_post_thumbnail('thumbnail');   ?></div>
                        
             <br><h1 style="font-weight: normal; line-height: 16px; color: #333333;"><?php the_title(); ?></h1>
						<?php the_content(); ?>

						<div class="clear"></div>

						<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'target' ), 'after' => '' ) ); ?>

						
						</div>
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