temp<?php get_header(); ?>



<div id="home-container" class="row">



	<!--slideshow-->

	

	<div id="slideshow" class="twelve columns">





		<?php echo ctslider_slider_template( $id ); ?>

		</div> <!--slideshow end-->



  </div><!--home container end-->



<div class="clear"></div>

				

		<!--boxes-->

		<div id="box_container" class="row">

				

		<?php for ($i = 1; $i <= 4; $i++) { ?>

		

		

				<div class="boxes <?php if ($i == 1) {echo "box1";} ?><?php if($i == 2) {echo "box2";} ?> <?php if($i == 3) {echo "box3";} ?> three columns">
				<div class="box-pack ">
						<div class="box-head">

								

	<a href="<?php echo of_get_option('box_link' . $i); ?>"><img src="<?php if(of_get_option('box_image' . $i) != NULL){ echo of_get_option('box_image' . $i);} else echo get_template_directory_uri() . '/images/box' .$i. '.png' ?>" alt="<?php echo of_get_option('box_head' . $i); ?>" /></a>



					

					</div> <!--box-head close-->

					

				<div class="title-box">						

						

				<div class="title-head"><?php if(of_get_option('box_head' . $i) != NULL){ echo of_get_option('box_head' . $i);} else echo "Box heading" ?></div></div>

					

					<div class="box-content">



				<?php if(of_get_option('box_text' . $i) != NULL){ echo of_get_option('box_text' . $i);} else echo "Nullam posuere felis a lacus tempor eget dignissim arcu adipiscing. Donec est est, rutrum vitae bibendum vel, suscipit non metus." ?>

					

					</div> <!--box-content close-->



				
				</div> <!--box-pack close-->
				</div><!--boxes  end-->

				

		<?php } ?>

		

		</div><!--box-container end-->

			

			<div class="clear"></div>

		

	<!--welcome-->

	<div id="welcome_container" class="row">



		<div id="welcome-box" class="twelve columns">

		

	<h1><?php if(of_get_option('welcome_text') != NULL){ echo of_get_option('welcome_text');} else echo "write your headline here" ?></h1>

		

	</div>



</div><!--welcome end-->



<div class="clear"></div>

		

</div>

<!--wrapper end-->

<?php get_footer(); ?>

