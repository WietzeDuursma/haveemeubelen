<div class="wrap">
<center><h2>amtyThumbPosts Admin page</h2></center>
<br />
<div>
<h3>Get post stats of a particular post</h3>
<form name="amtyThumbPosts_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="amty_hidden" value="Y" />
	Post ID : <input type="text" name="post_id" /><span class="submit"><input type="submit" name="Submit" value="submit" /></span>
	<br />
	<?php
	if($_POST['amty_hidden'] == 'Y') {
		$pid = $_POST['post_id'];
		$post = get_post($pid);
		if($post){?>
			<strong>Title</strong> : <?php echo "<a href='". get_permalink($post->ID). "' >". $post->post_title ."</a>"; ?> <br />
			<strong>Last visited at</strong> : <?php  echo getLastVisitedTime($post->ID); ?> <br />
			<strong>Total views</strong> : <?php  echo get_post_meta($post->ID, 'amtyviewcount', true); ?> <br />
			<strong>Total Comments</strong> : <?php echo $post->comment_count; ?> <br />
			<strong>Author ID</strong>: <?php echo $post->post_author; ?> <br />
			<strong>Posted on</strong> : <?php echo $post->post_date; ?>	<br />
	<?php }
		else{echo "Invalid post id";}
	}?>
</form>
</div>
<hr />
<div>
<h3>Post stats</h3>
<form name="amtyThumbPostsList_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="showlist" value="Y" />
	<strong>Filter on Category</strong>
	<select name="category">
		 <option>All</option>
		 <?php
		  $categories=  get_categories();
		  foreach ($categories as $cat) { ?>
		   <option><?php echo $cat->cat_name ?> </option>;
  <?php }?>
	</select>
	<strong>Select List type</strong>
	<select name="listtype">
		<option value="Recently Written">Recently Written</option>
		<option value="Most Commented">Most Commented</option>
		<option value="Most Viewed">Most Viewed</option>
		<option value="Least Viewed">Least Viewed</option>
		<option value="Recently Viewed">Recently Viewed</option>
		<option value="Historical">Historical</option>
	</select>
	Number of posts : <input type="text" name="maxpost" />
	<input type="submit" name="Submit" value="submit" />
</form>
	<?php if($_POST['showlist'] == 'Y') {
	
	$listType = $_POST['listtype'];
	$maxpost = $_POST['maxpost'];
	$categoryName = $_POST['category'];
	
	echo "Showing ".$maxpost." posts '". $listType ."' in ". $categoryName ." category";
	global $wpdb;
	if ($categoryName != 'All') {
		$theCatId = get_cat_id($categoryName );
		$category = "cat=" . $theCatId . "&";
	}else{
			$category = '';
	}
	
	
	if($listType == 'Recently Written')
		$amty_posts = get_posts($category . "showposts=" . $maxpost . "&orderby=date");
	elseif($listType == 'Most Commented')
		$amty_posts = get_posts($category . "showposts=" . $maxpost . "&orderby=comment_count");
	elseif($listType == 'Most Viewed')
		$amty_posts = get_posts($category . "showposts=" . $maxpost . "&meta_key=amtyviewcount&orderby=meta_value_num&order=DESC");
	elseif($listType == 'Least Viewed')
		$amty_posts = get_posts($category . "showposts=" . $maxpost . "&meta_key=amtyviewcount&orderby=meta_value_num&order=ASC");
	elseif($listType == 'Recently Viewed')
		$amty_posts = get_posts($category . "showposts=" . $maxpost . "&meta_key=amtylastviewed&orderby=meta_value_num&order=DESC");
	elseif($listType == 'Historical')
		$amty_posts = get_posts($category . "showposts=" . $maxpost . "&meta_key=amtylastviewed&orderby=meta_value_num&order=ASC");
	?>
	
	<br />
	<table width="100%">
	<?php if($amty_posts) {	?>
		<tr align="center"><td width="30px"><strong>Post ID</strong></td><td width="350px"><strong>Title</strong></td><td width="250px"><strong>Last visited at</strong></td><td><strong>Total views</strong></td><td><strong>Total Comments</strong></td><td><strong>Author ID</strong></td><td><strong>Posted on</strong></td></tr>
		<?php foreach ($amty_posts as $post) { ?>
			<tr border="1">
				<td align="left"><?php echo $post->ID; ?></td>
				<td align="left"><?php echo "<a href='".get_permalink($post->ID)."' >".substr($post->post_title,0,50) ."</a>"; ?></td>
				<td align="right"><?php echo getLastVisitedTime($post->ID); ?></td>
				<td align="right"><?php echo get_post_meta($post->ID, 'amtyviewcount', true); ?></td>
				<td align="right"><?php echo $post->comment_count; ?></td>
				<td align="right"><?php echo $post->post_author; ?></td>
				<td align="right"><?php echo $post->post_date; ?></td>
			</tr>
		<?php }
	}else{
		echo "No post found for selected list";
	} ?>
	</table>
	<?php } ?>


</div>
<div style="clear:both;"></div>
<hr />
</div>