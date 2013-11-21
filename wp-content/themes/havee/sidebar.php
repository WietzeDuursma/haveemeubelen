		<!--right-col-->

		<div id="right-col">

		

		

			

				<!--sidebar-->

				<div id="sidebar">

			

			<ul class="xoxo">



<?php



	if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : ?>





		<?php endif; // end primary widget area ?>

			</ul>
            <h1>Nieuws:</h1>
            <?php 
			$i=0;
			for ($x = 1; $x <= 2; $x++) { ?>
					<?php $p= rand(1, 4); 
					
					if ( $i==$p ) { $p=$p+1;} 
					if ( $p==5  ) { $p=1; }
					$i=$p;
					
					?>
                                <div class="boxes <?php if ($i == 1) {echo "box1";} ?><?php if($i == 2) {echo "box2";} ?> <?php if($i == 3) {echo "box3";} ?> ">
                                <div class="box-pack ">
                                  <div class="title-box-left"> <div class="title-head"><?php if(of_get_option('box_head' . $i) != NULL){ echo of_get_option('box_head' . $i);} else echo "Box heading" ?></div></div>			       
                                  <div class="box-head">
                							<a href="<?php echo of_get_option('box_link' . $i); ?>"><img src="<?php if(of_get_option('box_image' . $i) != NULL){ echo of_get_option('box_image' . $i);} else echo get_template_directory_uri() . '/images/box' .$i. '.png' ?>" alt="<?php echo of_get_option('box_head' . $i); ?>"  width="200px"/></a>
                						</div> <!--box-head close-->
                
                                  <div class="box-content-left">
									<?php if(of_get_option('box_text' . $i) != NULL){ echo of_get_option('box_text' . $i);} else echo "Nullam posuere felis a lacus tempor eget dignissim arcu adipiscing. Donec est est, rutrum vitae bibendum vel, suscipit non metus." ?></div> <!--box-content close-->  



				
					

					
                
                               			
                
                                        
                
                               
						</div>
                        </div>
                        <?PHP  } ?> 

<?php

	// A second sidebar for widgets, just because.

	if ( is_active_sidebar( 'secondary-widget-area' ) ) : ?>



			<ul class="xoxo">

				<?php dynamic_sidebar( 'secondary-widget-area' ); ?>

			</ul>

			



<?php endif; ?>



				</div><!--sb end-->

				

				<div class="clear"></div>

				

<img src="<?php echo get_template_directory_uri(); ?>/images/sidebar-bottom.png" alt="" />

				

			</div> <!--right-col-->