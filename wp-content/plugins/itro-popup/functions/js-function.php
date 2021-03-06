<?php
/*
Copyright 2013  I.T.RO.® (email : support.itro@live.com)
This file is part of ITRO Popup Plugin.
*/

//------------ LOAD SCRIPTS FOR POPUP VISUALIZATION
function itro_popup_js()
{ ?>
	<script type="text/javascript"> <?php
		if (itro_get_option('age_restriction') == NULL) //OFF age validation
		{
			if ( itro_get_option('preview_id') != get_the_id() )
			{ ?>
				itro_set_cookie("popup_cookie","one_time_popup",<?php echo itro_get_option('cookie_time_exp'); ?>); <?php
			}
			
			if( itro_get_option('popup_unlockable') != 'yes' )
			{ ?>
				document.onkeydown = function(event) 
				{
					event = event || window.event;
					var key = event.keyCode;
					if(key==27)
					{
						jQuery("#itro_popup").fadeOut(function() {itro_opaco.style.visibility='Hidden';});						
					} 
				}; <?php
			}
			
			if( itro_get_option('popup_delay') != 0 ) //if is set the delay
			{ ?>
				var delay = <?php echo itro_get_option('popup_delay') . '+' . '1'; ?> ;
				interval_id = setInterval(function(){popup_delay();},1000);
				function popup_delay() 
				{ 
					delay--;
					if(delay <= 0) 
					{
						clearInterval(interval_id); 
						jQuery("#itro_popup").fadeOut(1);
						jQuery("#itro_opaco").fadeOut(1);
						itro_popup.style.visibility = 'visible';
						itro_opaco.style.visibility = 'visible'; 
						jQuery("#itro_opaco").fadeIn(function() {jQuery("#itro_popup").fadeIn();});
					}
				}
			<?php
			}
			else //if popup delay is not setted
			{?>
				jQuery("#itro_popup").fadeOut(1);
				jQuery("#itro_opaco").fadeOut(1);
				itro_popup.style.visibility = 'visible';
				itro_opaco.style.visibility = 'visible'; 
				jQuery("#itro_opaco").fadeIn(function() {jQuery("#itro_popup").fadeIn();});
			<?php
			}
			
			//start the timer only if popup seconds are != 0 to avoid js errors
			if ( itro_get_option('popup_time') != 0 )
			{ ?>
				var popTime=<?php 
							if( itro_get_option('popup_delay')  != 0 ) { echo itro_get_option('popup_time') . '+' . itro_get_option('popup_delay'); }
							else { echo itro_get_option('popup_time'); }
							?>;
				setInterval(function(){popTimer()},1000); //the countdown 
				function popTimer()
				{
					if (popTime>0){
					document.getElementById("timer").innerHTML=popTime;
					popTime--;
					}
					else {itro_popup.style.visibility='Hidden'; itro_opaco.style.visibility='Hidden';
					}
				} <?php
			}
		}
		//if age restriction is enabled
		else
		{
			if( itro_get_option('popup_delay') != 0 )
			{ ?>
				var delay = <?php echo itro_get_option('popup_delay') . '+' . '1'; ?> ;
				interval_id = setInterval(function(){popup_delay();},1000);
				function popup_delay() 
				{ 
					delay--;
					if(delay <= 0) 
					{
						clearInterval(interval_id);
						jQuery("#itro_popup").fadeOut(1);
						jQuery("#itro_opaco").fadeOut(1);
						itro_popup.style.visibility = 'visible';
						itro_opaco.style.visibility = 'visible'; 
						jQuery("#itro_opaco").fadeIn(function() {jQuery("#itro_popup").fadeIn();});
					}
				}
			<?php
			}
			else
			{?>
				jQuery("#itro_popup").fadeOut(1);
				jQuery("#itro_opaco").fadeOut(1);
				itro_popup.style.visibility = 'visible';
				itro_opaco.style.visibility = 'visible'; 
				jQuery("#itro_opaco").fadeIn(function() {jQuery("#itro_popup").fadeIn();});
			  <?php
			}
		}?>
		
		function itro_set_cookie(c_name,value,exhours)
		{
			var exdate=new Date();
			exdate.setTime(exdate.getTime() + (exhours * 3600 * 1000));
			var c_value=escape(value) + ((exhours==null) ? "" : "; expires="+exdate.toUTCString());
			document.cookie=c_name + "=" + c_value + "; path=/";
		} 
		<?php
		//------- AUTOMATIC TOP MARGIN
		if( itro_get_option('auto_margin_check') != NULL )
		{?>
			var browserWidth = 0, browserHeight = 0;
			
			setInterval(function(){marginRefresh()},100); //refresh every 0.1 second the popup top margin (needed for browser window resizeing)
			function marginRefresh()
			{	
				if( typeof( window.innerWidth ) == 'number' ) 
				{
					//Non-IE
					browserWidth = window.innerWidth;
					browserHeight = window.innerHeight;
				} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) 
				{
					//IE 6+ in 'standards compliant mode'
					browserWidth = document.documentElement.clientWidth;
					browserHeight = document.documentElement.clientHeight;
				} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) 
				{
					//IE 4 compatible
					browserWidth = document.body.clientWidth;
					browserHeight = document.body.clientHeight;
				}
				popupHeight = document.getElementById('itro_popup').offsetHeight ; 			//get the actual px size of popup div
				document.getElementById('itro_popup').style.top = (browserHeight - popupHeight)/2 + "px"; //update the top margin of popup					
			}<?php 
		}?>
	</script>
<?php	
}

//------------- LOAD SCRIPT TO SHOW SLIDEBAR
function itro_slidebar($slider_target_id,$slider_value,$slider_min,$slider_max,$slider_step,$slider_tofixed,$multi_slider)
{
	if($multi_slider != NULL)
	{
		if( itro_get_option('select_' . $slider_target_id) != $multi_slider ) 
		{ 
			$slider_display = 'display:none;';
			//$js_input_display = 'document.getElementById("'. $slider_target_id .'").style.display = "none";' ;
		}
		$target_opt_name = $slider_target_id;
		$slider_container_id = $slider_target_id . '_slider_container';
		
		$js_slider_container_id = $multi_slider . '_' . $slider_target_id . '_slider_container';
		$js_slider_id = $multi_slider . '_' . $slider_target_id . '_slider';
		$slider_target_id = $multi_slider . '_' . $slider_target_id;
	}
	else
	{
		$js_slider_container_id = $slider_target_id . '_slider_container';
		$js_slider_id = $slider_target_id . '_slider';
	}
	?>

	<div id="<?php echo $js_slider_container_id; ?>" style="<?php echo $slider_display; ?>position: relative; float:right; top:12px; left:25px; width:150px; height:2px; background-color:black; border-radius:15px;">
		<div id="<?php echo $js_slider_id; ?>" style="left:<?php echo ( (itro_get_option($slider_target_id)/$slider_max)*150 );  ?>px; border-radius:15px; position: relative; top:-5px; cursor:pointer; width:15px; height:12px; background-color:gray;"></div>
	</div>
	<script type="text/javascript">
		document.getElementById("<?php echo $js_slider_container_id; ?>").addEventListener("mousedown", <?php echo $slider_target_id; ?>_start_slider,false);
		document.addEventListener("mousemove", itro_pos,false);
		
		function <?php echo $slider_target_id ?>_start_slider()
		{	
			document.addEventListener("mousemove",<?php echo $slider_target_id ?>_move_slider);
			document.addEventListener("mouseup",<?php echo $slider_target_id ?>_stop_slider)
			if( (x_pos - document.getElementById("<?php echo $js_slider_container_id; ?>").getBoundingClientRect().left) >= 0 && (x_pos - document.getElementById("<?php echo $js_slider_container_id; ?>").getBoundingClientRect().left) <= parseInt(document.getElementById("<?php echo $js_slider_container_id; ?>").style.width))
			{
				document.getElementById("<?php echo $js_slider_id;?>").style.left = x_pos - document.getElementById("<?php echo $js_slider_container_id; ?>").getBoundingClientRect().left - 7 + "px";
				document.getElementById("<?php echo $slider_target_id; ?>").value = (Math.round( ( (x_pos - document.getElementById("<?php echo $js_slider_container_id; ?>").getBoundingClientRect().left)/150*<?php echo $slider_max; ?> )/<?php echo $slider_step; ?> )*<?php echo $slider_step; ?>).toFixed(<?php echo $slider_tofixed; ?>);
			}
		}

		function <?php echo $slider_target_id ?>_move_slider()
		{
			if( (x_pos - document.getElementById("<?php echo $js_slider_container_id; ?>").getBoundingClientRect().left) >= 0 && (x_pos - document.getElementById("<?php echo $js_slider_container_id; ?>").getBoundingClientRect().left) <= parseInt(document.getElementById("<?php echo $js_slider_container_id; ?>").style.width) )
			{
				document.getElementById("<?php echo $js_slider_id;?>").style.left = x_pos - document.getElementById("<?php echo $js_slider_container_id; ?>").getBoundingClientRect().left - 7 + "px";
				document.getElementById("<?php echo $slider_target_id; ?>").value = (Math.round( ( (x_pos - document.getElementById("<?php echo $js_slider_container_id; ?>").getBoundingClientRect().left)/150*<?php echo $slider_max; ?> )/<?php echo $slider_step; ?> )*<?php echo $slider_step; ?>).toFixed(<?php echo $slider_tofixed; ?>);
			}
			if(x_pos - document.getElementById("<?php echo $js_slider_container_id; ?>").getBoundingClientRect().left < 0)
			{
				document.getElementById("<?php echo $js_slider_id;?>").style.left = -7 + "px";
				<?php echo $slider_target_id; ?>_temp1 = 0;
				document.getElementById("<?php echo $slider_target_id; ?>").value = <?php echo $slider_target_id; ?>_temp1.toFixed(<?php echo $slider_tofixed; ?>);
			}
			if(x_pos - document.getElementById("<?php echo $js_slider_container_id; ?>").getBoundingClientRect().left > parseInt(document.getElementById("<?php echo $js_slider_container_id; ?>").style.width))
			{
				document.getElementById("<?php echo $js_slider_id;?>").style.left = parseInt(document.getElementById("<?php echo $js_slider_container_id; ?>").style.width) - 7 + "px";
				<?php echo $slider_target_id; ?>_temp2 = <?php echo $slider_max; ?>;
				document.getElementById("<?php echo $slider_target_id; ?>").value = <?php echo $slider_target_id; ?>_temp2.toFixed(<?php echo $slider_tofixed; ?>);
			}
		}

		function <?php echo $slider_target_id ?>_stop_slider()
		{
			document.removeEventListener("mousemove",<?php echo $slider_target_id ?>_move_slider)
			
		}
		
		<?php $slider_target_id = $target_opt_name; ?>
		
		//---function disable
		function itro_disable_<?php echo $slider_target_id; ?>()
		{
			document.getElementById("px_<?php echo $slider_target_id; ?>").style.display = "none";
			document.getElementById("perc_<?php echo $slider_target_id; ?>").style.display = "none";
			document.getElementById("px_<?php echo $slider_container_id; ?>").style.display = "none";
			document.getElementById("perc_<?php echo $slider_container_id; ?>").style.display = "none";
		}
		
		//---function enable
		function itro_enable_<?php echo $slider_target_id; ?>(dim_type)
		{
			if(dim_type == 'perc') 
			{
				document.getElementById("perc_<?php echo $slider_container_id; ?>").style.display = "block";
				document.getElementById("perc_<?php echo $slider_target_id; ?>").style.display = "inline";
				document.getElementById("px_<?php echo $slider_target_id; ?>").style.display = "none";
				document.getElementById("px_<?php echo $slider_container_id; ?>").style.display = "none";
			}
			if(dim_type == 'px') 
			{
				document.getElementById("px_<?php echo $slider_container_id; ?>").style.display = "block";
				document.getElementById("px_<?php echo $slider_target_id; ?>").style.display = "inline";
				document.getElementById("perc_<?php echo $slider_target_id; ?>").style.display = "none";
				document.getElementById("perc_<?php echo $slider_container_id; ?>").style.display = "none";
			}
		}
	</script><?php
}

//-------------- LOAD SCRIPTS FOR ADMIN PANNEL
function itro_admin_js()
{ ?>
	<script type="text/javascript">
		function itro_pos(e)
		{
			e = e || window.event;
			x_pos = e.clientX;
		}
		
		function itro_mutual_check(checkbox_id_1,checkbox_id_2,box)
		{
			if (!box)
			{
				if( checkbox_id_2 == '' ) {document.getElementById(checkbox_id_1).checked = !document.getElementById(checkbox_id_1).checked; return 1;}
				if( checkbox_id_1 == '' ) {return 0;}
				if(checkbox_id_1 == checkbox_id_2) { return 0; }
				document.getElementById(checkbox_id_1).checked = !document.getElementById(checkbox_id_1).checked;
				if( document.getElementById(checkbox_id_1).checked || document.getElementById(checkbox_id_2).checked )
				{ document.getElementById(checkbox_id_2).checked = !document.getElementById(checkbox_id_1).checked; }
			}
			else
			{
				if( document.getElementById(checkbox_id_1).checked || document.getElementById(checkbox_id_2).checked )
				{ document.getElementById(checkbox_id_2).checked = !document.getElementById(checkbox_id_1).checked; }
			}
		}
		jQuery(document).ready(function() {
		
		var orig_send_to_editor = window.send_to_editor;
		var uploadID = ''; /*setup the var in a global scope*/

		jQuery('#upload_button').click(function() {
		uploadID = jQuery(this).prev('input'); /*set the uploadID variable to the value of the input before the upload button*/
		formfield = jQuery('.upload').attr('name');
		tb_show('', 'media-upload.php?type=image&amp;amp;amp;TB_iframe=true');
		
		//restore send_to_editor() when tb closed
		jQuery("#TB_window").bind('tb_unload', function () {
		window.send_to_editor = orig_send_to_editor;
		});
		
		//temporarily redefine send_to_editor()
		window.send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src');
		uploadID.val(imgurl); /*assign the value of the image src to the input*/
		document.getElementById('yes_bg').checked=true
		tb_remove();
		};
		return false;
		});
		});
		
		var itro_option_state;
		function itro_check_state(state_check_id)
		{
			itro_option_state = state_check_id.selected;
		}

		function itro_select(target_id)
		{
			target_id.selected = !itro_option_state;
			itro_option_state = !itro_option_state;
		}
		
		function itro_set_cookie(c_name,value,exhours)
		{
			var exdate=new Date();
			exdate.setTime(exdate.getTime() + (exhours * 3600 * 1000));
			var c_value=escape(value) + ((exhours==null) ? "" : "; expires="+exdate.toUTCString());
			document.cookie=c_name + "=" + c_value + "; path=/";
		}
	</script><?php
} 

//show and hide parts of admin pannel such as top margin and basic settings
function itro_show_hide($hide_target_id, $hide_shooter_id, $display_val, $inverted, $highlight_opt)
{?>
	<script type="text/javascript">
	
	<?php 
	if ($inverted == 'false') //decide if elements start hidden or visible: if inverted==true -> if $hide_shooter_id is checked -> start visible else start hidden
	{ $check_condition = 'yes'; }
	else
	{ $check_condition = NULL; }
	
	if( itro_get_option($hide_shooter_id) == $check_condition)
	{
		foreach($hide_target_id as $single_targer_id)
		{
			echo 'document.getElementById("' . $single_targer_id . '").style.display = "table";';
		}
		unset($single_targer_id);
	}
	else
	{
		foreach($hide_target_id as $single_targer_id)
		{
			echo 'document.getElementById("' . $single_targer_id . '").style.display = "none";';
		}
		unset($single_targer_id);
	}
	?>

	function <?php echo $hide_shooter_id; ?>_itro_show_hide()
	{<?php
		foreach($hide_target_id as $single_targer_id)
		{?>
			if( document.getElementById("<?php echo $single_targer_id; ?>").style.display != "none" ) 
				{jQuery("#<?php echo $single_targer_id; ?>").fadeOut("fast");}
			else 
				{
					jQuery("#<?php echo $single_targer_id; ?>").fadeIn("fast" , function() {jQuery("#<?php echo $single_targer_id; ?>").effect( "highlight", {color:"<?php echo $highlight_opt[0];?>"}, <?php echo $highlight_opt[1];?> );});
					document.getElementById("<?php echo $single_targer_id; ?>").style.display = "table";
				}<?php
		}
		unset($single_targer_id);?>
	}
	
	function <?php echo $hide_shooter_id; ?>_stop_anim()
	{ <?php
		foreach($hide_target_id as $single_targer_id)
		{ ?>
			if ( document.getElementById("<?php echo $single_targer_id; ?>").style.display != "none" )
			{ jQuery("#<?php echo $single_targer_id; ?>").stop(true, true); } <?php
		} ?>
	}
	
	document.getElementById("<?php echo 'span_' . $hide_shooter_id; ?>").addEventListener("mousedown" , <?php echo $hide_shooter_id; ?>_stop_anim);
	document.getElementById("<?php echo $hide_shooter_id; ?>").addEventListener("mousedown" , <?php echo $hide_shooter_id; ?>_stop_anim);
	
	document.getElementById("<?php echo 'span_' . $hide_shooter_id; ?>").addEventListener("mousedown" , <?php echo $hide_shooter_id; ?>_itro_show_hide);
	document.getElementById("<?php echo $hide_shooter_id; ?>").addEventListener("mousedown" , <?php echo $hide_shooter_id; ?>_itro_show_hide);
	
	</script> <?php
}

function itro_onOff($tag_id,$overflow){
if( $overflow == 'hidden') {?>
	<style>#<?php echo $tag_id;?>{overflow:hidden;}</style><?php
} ?>
<script type="text/javascript">
	var <?php echo $tag_id;?>_flag=true;
	function onOff_<?php echo $tag_id;?>() {
	   if (<?php echo $tag_id;?>_flag==true) { document.getElementById('<?php echo $tag_id;?>').style.height='0px'; }
	   else { document.getElementById('<?php echo $tag_id;?>').style.height='auto'; }
	<?php echo $tag_id;?>_flag=!<?php echo $tag_id;?>_flag;
	}
</script>
<?php 
}

function itro_onOff_checkbox($box_id,$tag_id,$init_state){
?>
<style>#<?php echo $tag_id;?>{overflow:hidden;}</style>
<script type="text/javascript">
	function <?php echo $box_id;?>_checkbox_<?php echo $tag_id;?>()
	{
		if (<?php echo $box_id;?>.checked==<?php echo $init_state ?>) {document.getElementById('<?php echo $tag_id;?>').style.height='0px';}
		else {document.getElementById('<?php echo $tag_id;?>').style.height='auto';}
	}
</script>
<?php 
}
?>