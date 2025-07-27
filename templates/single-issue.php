
<?php get_header(); ?>

<main id="primary" class="site-main container my-5">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <!-- Centered title -->
        <h1 class="text-center mb-4">
            <?php the_title(); ?><?php
                // Access an ACF field called "volume"
                $issue_date = get_field('issue_date');
                if ( $issue_date ) {
                    echo ', ' . esc_html( $issue_date ) . '</p>';
                }
            ?>
        </h1>

        <!-- Two-column layout -->
        <div class="row">
            <!-- Content -->
            <div class="col-md-6">
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </div>

            <!-- Featured image -->
            <div class="col-md-6">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="featured-image mb-3">
                        <?php the_post_thumbnail( 'large', [ 'class' => 'img-fluid rounded' ] ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>

