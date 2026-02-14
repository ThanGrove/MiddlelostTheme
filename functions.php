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

add_filter('kadence_form_submission', function ($submission) {
    // Check the honeypot field
    if (!empty($_POST['AddressHP'])) {
        wp_die('Form submission rejected as spam.');
    }
    return $submission;
});

add_action('after_setup_theme', function() {
    add_image_size('teaser-thumb', 150, 225, true); // exact width/height, hard crop
});

function issues_list_shortcode() {
    $args = [
        'post_type' => 'issue',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    // Map seasons to a numeric value for sorting
    $season_order = array(
        'Winter' => 1,
        'Spring' => 2,
        'Summer' => 3,
        'Fall'   => 4
    );

    $issues = new WP_Query($args);
    $output = '';

    // Collect all issues first
    $all_issues = array();
    while ($issues->have_posts()) {
        $issues->the_post();

        $issue_date = get_field('issue_date'); // e.g. "Winter 2025"
        if ($issue_date) {
            list($season, $year) = explode(' ', $issue_date);
            $all_issues[] = array(
                'ID' => get_the_ID(),        // store post ID
                'title' => get_the_title(),
                'thumbnail' => get_the_post_thumbnail(get_the_ID(), 'teaser-thumb'),
                'subtitle' => get_field('issue_title'),
                'description' => get_field('issue_description'),
                'issue_date' => $issue_date,
                'year' => (int) $year,
                'season' => $season_order[$season] ?? 0,
                'permalink' => get_permalink(),
            );
        }
    }

    // Sort reverse chronological: latest year first, then latest season first
    usort($all_issues, function($a, $b) {
        return ($b['year'] <=> $a['year']) ?: ($b['season'] <=> $a['season']);
    });


// Build output
    $output = '<div id="issues-list" class="container">';
    foreach ($all_issues as $item) {
        $link_start = '<a href="' . esc_url($item['permalink']) . '">';
        $link_end = '</a>';

        $output .= '<div class="issue row align-items-center mb-4">';
        $output .= '<div class="col-12 col-md-3">' . $link_start . $item['thumbnail'] . $link_end . '</div>';
        $output .= '<div class="col-12 col-md-9"><h2 class="mb-0">' . $link_start . esc_html($item['title']) . $link_end . '</h2>';

        if ($item['subtitle']) {
            $output .= '<h3 class="issue-subtitle m-0">' . $link_start . esc_html($item['subtitle']) . $link_end . '</h3>';
        }

        $output .= '<div class="issue-date">' . esc_html($item['issue_date']) . '</div>';

        if ($item['description']) {
            $output .= '<div class="issue-desc">' . esc_html($item['description']) . '</div>';
        }

        $output .= '</div></div>';
    }
    $output .= '</div>';
    return $output;
}
add_shortcode('issues_list', 'issues_list_shortcode');
