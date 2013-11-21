<!--search form-->

				

				    <form method="get" id="search" action="<?php echo home_url(); ?>">



					<div>

					<?php $req=''; ?>

               		<input type="text" value="zoeken op deze site" name="s" id="s"  onfocus="if(this.value=='<?php _e( 'zoeken op deze site', 'target' ); ?>'){this.value=''};" onblur="if(this.value==''){this.value='zoeken op deze site'};" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />

               		<input type="submit" id="searchsubmit" value="" />

                	

					</div>

               		</form>