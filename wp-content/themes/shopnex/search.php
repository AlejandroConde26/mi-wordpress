<?php
/**
 * The template for displaying search results.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package shopnex
 */

get_header();
?>

<div id="primary" class="content-area">
    <div id="main-content" class="site-main" role="main">

    <?php get_template_part( 'template-parts/page/content-search' ); ?>

    </div>
</div>

<?php get_footer(); ?>
