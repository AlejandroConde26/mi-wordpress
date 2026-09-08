<?php
/**
 * Blog Sidebar Template Part
 *
 * @package shopnex
 */
?>

<aside class="sidebar">

    <!-- Search -->
    <div class="sidebar-block">
        <div class="sidebar-block-title"><?php echo esc_html__('Search', 'shopnex'); ?></div>
        <?php get_search_form(); ?>
    </div>

    <!-- Categories -->
    <div class="sidebar-block">
        <div class="sidebar-block-title"><?php echo esc_html__('Categories', 'shopnex'); ?></div>
        <ul class="sidebar-cat-list">
            <?php
            $categories = get_categories(array(
                'orderby' => 'name',
                'order' => 'ASC',
                'number' => 10
            ));
            foreach ($categories as $category) :
            ?>
            <li class="sidebar-cat-item">
                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                    <?php echo esc_html($category->name); ?>
                </a>
                <span class="cat-count"><?php echo esc_html($category->count); ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Popular Posts -->
    <div class="sidebar-block">
        <div class="sidebar-block-title"><?php echo esc_html__('Popular Articles', 'shopnex'); ?></div>
        <?php
        $popular_posts = new WP_Query(array(
            'posts_per_page' => 4,
            'meta_key' => 'post_views_count',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'ignore_sticky_posts' => 1
        ));

        if ($popular_posts->have_posts()) :
            while ($popular_posts->have_posts()) : $popular_posts->the_post();
                $categories = get_the_category();
                $primary_category = !empty($categories) ? $categories[0] : null;
        ?>
        <div class="popular-post">
            <a href="<?php the_permalink(); ?>" class="popular-thumb<?php echo ! has_post_thumbnail() ? ' no-image' : ''; ?>">
                <?php
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail('thumbnail');
                }
                ?>
            </a>
            <div class="popular-info">
                <?php if ($primary_category) : ?>
                <span class="pop-cat"><?php echo esc_html($primary_category->name); ?></span>
                <?php endif; ?>
                <a href="<?php the_permalink(); ?>">
                    <h4><?php the_title(); ?></h4>
                </a>
                <span class="pop-date"><?php echo get_the_date(); ?></span>
            </div>
        </div>
        <?php
            endwhile;
            wp_reset_postdata();
        else :
            // Fallback: show recent posts if no view count
            $recent_posts = new WP_Query(array(
                'posts_per_page' => 4,
                'ignore_sticky_posts' => 1
            ));
            if ($recent_posts->have_posts()) :
                while ($recent_posts->have_posts()) : $recent_posts->the_post();
                    $categories = get_the_category();
                    $primary_category = !empty($categories) ? $categories[0] : null;
        ?>
        <div class="popular-post">
            <a href="<?php the_permalink(); ?>" class="popular-thumb<?php echo ! has_post_thumbnail() ? ' no-image' : ''; ?>">
                <?php
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail('thumbnail');
                }
                ?>
            </a>
            <div class="popular-info">
                <?php if ($primary_category) : ?>
                <span class="pop-cat"><?php echo esc_html($primary_category->name); ?></span>
                <?php endif; ?>
                <a href="<?php the_permalink(); ?>">
                    <h4><?php the_title(); ?></h4>
                </a>
                <span class="pop-date"><?php echo get_the_date(); ?></span>
            </div>
        </div>
        <?php
                endwhile;
                wp_reset_postdata();
            endif;
        endif;
        ?>
    </div>

    <!-- Tags -->
    <div class="sidebar-block">
        <div class="sidebar-block-title"><?php echo esc_html__('Topics', 'shopnex'); ?></div>
        <div class="tags-cloud">
            <?php
            $tags = get_tags(array(
                'orderby' => 'name',
                'order' => 'ASC',
                'number' => 12
            ));
            foreach ($tags as $tag) :
            ?>
            <button class="tag-pill" data-toggle-tag><?php echo esc_html($tag->name); ?></button>
            <?php endforeach; ?>
        </div>
    </div>

</aside>
