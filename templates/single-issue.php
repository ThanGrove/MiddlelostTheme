
<?php
    get_header();
?>
<main id="primary" class="site-main container my-5">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <!-- Centered title -->
        <h1 class="text-center">
            <?php the_title(); ?><?php
                // Access an ACF field called "volume"
                $issue_date = get_field('issue_date');
                if ( $issue_date ) {
                    echo ', ' . esc_html( $issue_date ) . '</p>';
                }
            ?>
        </h1>
        <?php
            $cost = get_field('issue_cost');
            if ($cost) : ?>

            <div class="row text-center mb-3">
                <div class="col-md-6 offset-md-3">
                    Price:
                    <?php the_field('issue_cost'); ?>
                    <a href="/contact?action=buy&issue=<?php echo get_field('issue_number'); ?>&cost=<?php echo $cost; ?>"
                       class="btn btn-sm btn-success mx-3">Buy Now</a>
                </div>
            </div>
        <?php endif; ?>

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

