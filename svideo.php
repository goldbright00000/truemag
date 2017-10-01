<?php
/**
 * Template Name: Svideo
 *
 */

get_header();
?>

<style>
    .row {
        padding: 40px 0;
    }
</style>

<?php

   $post = get_post($_REQUEST['pid']);

   echo "<pre>";
     print_r($post);
   echo "</pre>";

?>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <img src="<?= get_site_url() ?>/wp-content/themes/truemag/images/default/default-thumbnail.jpg" class="img-responsive"/>

        </div>
        <div  class="col-md-6">
            <h3><?= $post->post_title ?></h3>
            <p>By: author</p>

            <p><?= $post->post_content ?></p>
        </div>
    </div>
</div>


<?php get_footer(); ?>