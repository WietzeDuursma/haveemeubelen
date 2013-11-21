image<?php get_header(); ?>



		<div id="subhead_container" >



			<div id="subhead_wrapper" class="row">

				<div id="subhead">

		

		<h1><?php if ( is_category() ) {

		single_cat_title();

		} elseif (is_tag() ) {

		echo (__( 'Archives for ', 'target' )); single_tag_title();

	} elseif (is_archive() ) {

	echo (__( 'Archives for ', 'target' )); single_month_title();

	} else {

		wp_title('',true);  

	} ?></h1>

			

			</div>

			

	

	

	<div class="clear"></div>		

			

			</div>

		</div>

		

			



		<!--content-->

		<div id="content_container" class="row" >

			
			<?php if(is_category('showroom-outlet')){ ?>
			<div id="content" class="nine columns" >
            <?PHP } else { ?>
            <div id="content" class="twelve columns" style="border: 1px dotted #000">
            <?PHP } ?>
            
 					<div class="kaderinhoud">
								<?php
            
                               
                                get_template_part( 'looppro', 'index' );
                                
                                
                                ?>
		
					</div>
				</div>
                
				<?PHP if(is_category('showroom-outlet')){ ?>
                                
                    <div id="left-col" class="three columns hide-on-phones"><?php get_sidebar(); ?></div>
                
                <?php } ?>

			



 






	 

</div>

<!--content end-->

	

</div>

<!--wrapper end-->



<?php get_footer(); ?>