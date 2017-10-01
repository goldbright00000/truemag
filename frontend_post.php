<?php 
 /*Template Name: Frontend post
 */
get_header();
?>

<form method="post" enctype="multipart/form-data" name="mainForm" action="">
	<div id="postTitleOuter">
		<label>Video Title</label>
		<input type="text" name="postTitle" class="postTitle"/>
	</div>
	<div id="postContentOuter">
		<label>Video Description</label>
		<textarea rows="4" cols="20" class="postContent" name="postContent"></textarea>
	</div>
	<div id="postContentOuter">
		<label>Video Url</label>
		
	</div>
	<input type="submit" name="add_post" id="add_post" value="Add Post">
</form>
<?php get_footer();?>