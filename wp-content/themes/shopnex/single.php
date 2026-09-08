<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package shopnex
 */

get_header();

?>

<!-- Article Hero Image -->
<?php if ( has_post_thumbnail() ) : ?>
<div class="article-hero fade-in">
    <?php the_post_thumbnail( 'full' ); ?>
</div>
<?php endif; ?>

<!-- Article Content -->
<?php
while ( have_posts() ) :
    the_post();
?>
<article class="article-container fade-in" id="articleContainer">

    <!-- Breadcrumb -->
    <div class="article-breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'shopnex' ); ?></a>
        <span class="sep">/</span>
        <?php
        $blog_page_id = get_option( 'page_for_posts' );
        if ( $blog_page_id ) :
        ?>
        <a href="<?php echo esc_url( get_permalink( $blog_page_id ) ); ?>"><?php esc_html_e( 'Journal', 'shopnex' ); ?></a>
        <?php else : ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Blog', 'shopnex' ); ?></a>
        <?php endif; ?>
        <span class="sep">/</span>
        <span><?php the_title(); ?></span>
    </div>

    <?php
    $categories = get_the_category();
    if ( ! empty( $categories ) ) :
    ?>
    <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>" class="article-category">
        <?php echo esc_html( $categories[0]->name ); ?>
    </a>
    <?php endif; ?>

    <h1 class="article-title font-display"><?php the_title(); ?></h1>

    <div class="article-meta">
        <span class="article-author">
            <?php echo get_avatar( get_the_author_meta( 'ID' ), 32, '', '', array( 'class' => 'article-author-avatar' ) ); ?>
            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="article-author-name"><?php the_author(); ?></a>
        </span>
        <span class="article-meta-dot">·</span>
        <span class="article-meta-date"><?php echo esc_html( get_the_date() ); ?></span>
        <span class="article-meta-dot">·</span>
        <span class="article-meta-read"><?php echo esc_html( shopnex_estimated_reading_time( get_the_content() ) ); ?> <?php esc_html_e( 'min read', 'shopnex' ); ?></span>
    </div>

    <hr class="article-divider">

    <!-- Article Body -->
    <div class="article-body">
        <?php the_content(); ?>
    </div>

    <!-- Share Row -->
    <div class="article-share-row">
        <span><?php esc_html_e( 'Share this story', 'shopnex' ); ?></span>
        <a href="https://x.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>" class="share-pill" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'X', 'shopnex' ); ?></a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( get_permalink() ); ?>" class="share-pill" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Facebook', 'shopnex' ); ?></a>
    </div>

</article>
<?php endwhile; ?>

<!-- Comments Section -->
<?php
if ( comments_open() || get_comments_number() ) :
?>
<div class="article-comments-container fade-in">
    <div class="comments-inner">
        <div class="comments-header">
            <h2 class="comments-title font-display"><?php esc_html_e( 'Discussion', 'shopnex' ); ?></h2>
            <span class="comments-count"><?php echo esc_html( get_comments_number() ); ?> <?php esc_html_e( 'Comments', 'shopnex' ); ?></span>
        </div>
        <?php comments_template(); ?>
    </div>
</div>
<?php endif; ?>

<?php shopnex_related_blog_posts(); ?>

<?php
get_footer();
