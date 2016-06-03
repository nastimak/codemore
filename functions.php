function be_load_more_js() {
	global $wp_query;
	$args = array(
		'nonce' => wp_create_nonce( 'be-load-more-nonce' ),
		'url'   => admin_url( 'admin-ajax.php' ),
		'query' => $wp_query->query,
	);
			
	wp_enqueue_script( 'be-load-more', get_stylesheet_directory_uri() . '/js/main-load-click.js', array( 'jquery' ),null, true );
	wp_localize_script( 'be-load-more', 'beloadmore', $args );
	
}
add_action( 'wp_enqueue_scripts', 'be_load_more_js' );
/**
 * AJAX Load More 
 *
 */
function be_ajax_load_more() {
	check_ajax_referer( 'be-load-more-nonce', 'nonce' );
    
	$args = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();
	$args['post_type'] = isset( $args['post_type'] ) ? esc_attr( $args['post_type'] ) : 'post';
	$args['paged'] = esc_attr( $_POST['page'] );
    $args['category_name'] = 'video and text';
	$args['post_status'] = 'publish';
	ob_start();
	$loop = new WP_Query( $args );
	if( $loop->have_posts() ): while( $loop->have_posts() ): $loop->the_post();
   ?><h2><?php the_title(); ?></h2>
   <p><?php the_content(); ?></p>
   <?php
	endwhile; endif; wp_reset_postdata();
	$data = ob_get_clean();
	wp_send_json_success( $data );
	wp_die();
}
add_action( 'wp_ajax_be_ajax_load_more', 'be_ajax_load_more' );
add_action( 'wp_ajax_nopriv_be_ajax_load_more', 'be_ajax_load_more' );
/**
 * First Term 
 * Helper Function
 */
function ea_first_term( $taxonomy, $field ) {
	$terms = get_the_terms( get_the_ID(), $taxonomy );
  
	if( empty( $terms ) || is_wp_error( $terms ) )
		return false;
	
	// If there's only one term, use that
	if( 1 == count( $terms ) ) {
		$term = array_shift( $terms );
	} else {
		$term = array_shift( $list );
	}
	
	// Output 	
	if( $field && isset( $term->$field ) )
		return $term->$field;
	
	else
		return $term;	
	
}