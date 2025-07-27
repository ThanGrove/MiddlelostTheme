<?php
// Enqueue Parent Theme Styles
function kadence_child_enqueue_styles() {
    wp_enqueue_style( 'kadence-parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'kadence_child_enqueue_styles' );

// Use Bootstrap and custom styles
function enqueue_css_js() {
    // Enqueue Theme styles
    wp_enqueue_style('middlelost-css', get_stylesheet_directory_uri() . '/css/middelost.css' );

    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css', false, '5.3.0', 'all');

    // Enqueue Bootstrap JS (optional, if you need JavaScript components like modals, carousels, etc.)
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.0', true);

}
add_action('wp_enqueue_scripts', 'enqueue_css_js');

// Look for templates in the /templates folder
add_filter( 'single_template', function( $template ) {
    if ( is_singular( 'issue' ) ) {
        $new_template = locate_template( array( 'templates/single-issue.php' ) );
        if ( $new_template ) {
            return $new_template;
        }
    }
    return $template;
});
