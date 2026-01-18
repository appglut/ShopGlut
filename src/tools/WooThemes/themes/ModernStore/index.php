<?php
/**
 * The main template file for ShopGlut Modern Store Theme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header(); ?>

<div class="container">
    <div class="site-content clearfix">
        <main class="content-area">
            <?php if ( have_posts() ) : ?>
                <header class="page-header">
                    <?php if ( is_home() && ! is_front_page() ) : ?>
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    <?php elseif ( is_archive() ) : ?>
                        <h1 class="page-title"><?php the_archive_title(); ?></h1>
                        <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
                    <?php elseif ( is_search() ) : ?>
                        <?php // translators: %s is the search query ?>
                        <h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'shopglut' ), esc_html( get_search_query() ) ); ?></h1>
                    <?php endif; ?>
                </header>

                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <?php if ( is_singular() ) : ?>
                                <h1 class="entry-title"><?php the_title(); ?></h1>
                            <?php else : ?>
                                <h2 class="entry-title">
                                    <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                                </h2>
                            <?php endif; ?>

                            <div class="entry-meta">
                                <span class="posted-on">
                                    <time datetime="<?php echo get_the_date( 'c' ); ?>">
                                        <?php echo get_the_date(); ?>
                                    </time>
                                </span>
                                <span class="byline">
                                    <?php echo esc_html__( 'by', 'shopglut' ); ?> 
                                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                                        <?php the_author(); ?>
                                    </a>
                                </span>
                                <?php if ( has_category() ) : ?>
                                    <span class="cat-links">
                                        <?php echo esc_html__( 'in', 'shopglut' ); ?> <?php the_category( ', ' ); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </header>

                        <?php if ( has_post_thumbnail() && ! is_singular() ) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'medium_large' ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php
                            if ( is_singular() ) {
                                the_content();
                            } else {
                                the_excerpt();
                            }
                            ?>
                        </div>

                        <?php if ( ! is_singular() ) : ?>
                            <footer class="entry-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php echo esc_html__( 'Read More', 'shopglut' ); ?>
                                </a>
                            </footer>
                        <?php endif; ?>
                    </article>
                <?php endwhile; ?>

                <?php
                // Pagination
                the_posts_pagination( array(
                    'prev_text' => __( 'Previous', 'shopglut' ),
                    'next_text' => __( 'Next', 'shopglut' ),
                ) );
                ?>

            <?php else : ?>
                <section class="no-results">
                    <header class="page-header">
                        <h1 class="page-title"><?php echo esc_html__( 'Nothing here', 'shopglut' ); ?></h1>
                    </header>

                    <div class="page-content">
                        <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
                            <p>
                            <?php
                           
                            printf( /* translators: %1$s is the URL to create a new post */
                                wp_kses_post(/* translators: %1$s is the URL to create a new post */
                                    __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'shopglut' )
                                ),
                                esc_url( admin_url( 'post-new.php' ) )
                            );
                            ?>
                            </p>
                            <p><?php echo esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'shopglut' ); ?></p>
                            <?php get_search_form(); ?>
                        <?php else : ?>
                            <p><?php echo esc_html__( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'shopglut' ); ?></p>
                            <?php get_search_form(); ?>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>
        </main>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>