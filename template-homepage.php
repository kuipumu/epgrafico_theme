<?php
/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Homepage
 *
 * @category Theme
 *
 * @package Epgrafico
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php

            $args = array(
                // Arguments for your query.
                'posts_per_page' => 2,
                'ignore_sticky_posts' => 1,
            );

            // Custom query.
            $query = new WP_Query($args);

            // Check that we have query results.
            if ($query->have_posts()) {
                ?>
                <section class="homepage-top-section" data-masonry='{"percentPosition": true }'>
                    <ul>
                    <?php
                    // Start looping over the query results.
                    while ( $query->have_posts() ) {

                        $query->the_post();
                        // Contents of the queried post results go here.
                        ?>
                        <li>
                            <article
                            <?php post_class() ?>
                            itemscope itemtype="https://schema.org/Article">
                                <a href="<?php the_permalink() ?>">
                                    <?php the_post_thumbnail()?>
                                </a>
                                <header class="entry-header">
                                    <?php storefront_post_meta() ?>
                                    <h2 class="alpha entry-title">
                                        <a href="<?php the_permalink() ?>">
                                            <?php the_title() ?>
                                        </a>
                                    </h2>
                                </header>
                            </article>
                        </li>
                        <?php
                    }
                    ?>
                    </ul>
                </section>
                <hr>
                <?php

            }

            // Restore original post data.
            wp_reset_postdata();

            /**
             * Functions hooked in to homepage action
             *
             * @hooked storefront_homepage_content      - 10
             * @hooked storefront_product_categories    - 20
             * @hooked storefront_recent_products       - 30
             * @hooked storefront_featured_products     - 40
             * @hooked storefront_popular_products      - 50
             * @hooked storefront_on_sale_products      - 60
             * @hooked storefront_best_selling_products - 70
             */
            do_action('homepage');
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->
<?php
get_footer();
