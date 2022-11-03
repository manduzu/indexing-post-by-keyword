 <?php 

 // code goes to FUNCTION.PHP



// Hook into the WP_Query filter to modify the search query
add_filter( 'posts_search', 'make_search_any_terms', 10, 2 );

/**
 * Function to convert search to "any" terms instead of "all" terms.
 * WordPress automatically passes in the two arguments.
 * 
 * @param string $search
 * @param WP_Query $query
 *
 * @return string
 */
function make_search_any_terms( $search, $query ) {
    // If it's not a search, then do nothing
    if ( empty( $query->is_search ) ) {
        return $search;
    }

    global $wpdb;

    $search_terms = get_query_var( 'search_terms' );

    // This code adapted from WP_Query "parse_search" function
    $search    = '';
    $searchand = '';

    if (is_array($search_terms) || is_object($search_terms)){
    foreach ($search_terms as $term) {
        $like                        = '%' . $wpdb->esc_like( $term ) . '%';
        $q['search_orderby_title'][] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $like );

        $like = '%' . $wpdb->esc_like( $term ) . '%';
        $search .= $wpdb->prepare( "{$searchand}(($wpdb->posts.post_title LIKE %s) OR ($wpdb->posts.post_content LIKE %s))", $like, $like );
        // This is the significant change - originally is "AND", change to "OR"
        $searchand = ' OR ';
    }
}

    if ( ! empty( $search ) ) {
        $search = " AND ({$search}) ";
        if ( ! is_user_logged_in() ) {
            $search .= " AND ($wpdb->posts.post_password = '') ";
        }
    }

    return $search;
}


// display function IN QUERY 



	<?php
		// Output the menu modal.
	get_template_part( 'template-parts/modal-menu' );//

	$args = array(
		'fields'         => 'ids',
		'order'          => 'ASC',
		's'              => 'post',//post keyword to index
		'posts_per_page' => -1,
		'post_type'      => 'post',
	);
	?>
	<?php $query = new WP_Query( $args ); ?>
	<?php if ( $query->have_posts() ) : ?>
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<h4><?php echo get_the_title(); ?></h4>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<p><?php _e( 'No posts found' ); ?></p>
	<?php endif; ?>









