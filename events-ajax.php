<?php 

add_action( 'wp_ajax_nopriv_filter', 'filter_ajax');
add_action( 'wp_ajax_filter', 'filter_ajax' );

function filter_ajax() {

	// Check for nonce
	if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
		die('Invalid request');
	}

	// Init empty arrays to be used in tax_query
	$searchText = NULL;
	$lengths = [];
	$times = [];
	$locations = [];

	// Loop through $_POST and assign tax terms to matching array
	foreach ( $_POST as $key => $value ) {

		if ( str_contains( $key, 'search--text' ) ) {
			$searchText = sanitize_text_field( $value );
		}

		if ( str_contains( $key, 'event-length' ) ) {
			array_push( $lengths, sanitize_text_field( strtolower( substr( $value, 14 ) ) ) );
		}

		if ( str_contains( $key, 'event-time' ) ) {
			array_push( $times, sanitize_text_field( strtolower( substr( $value, 12 ) ) ) );
		}

		if ( str_contains( $key, 'event-location' ) ) {
			array_push( $locations, sanitize_text_field( strtolower( substr( $value, 16 ) ) ) );
		}
	}

	// If newly created array contains any terms build tax_query
	$selectedLengths = null;
	if ( count( $lengths ) > 0 ) {
		$selectedLengths = array(
			'taxonomy' => 'event-length',
			'field' => 'slug',
			'terms' => $lengths
		);
	}

	$selectedTimes = null;
	if ( count( $times ) > 0 ) {
		$selectedTimes = array(
			'taxonomy' => 'event-time',
			'field' => 'slug',
			'terms' => $times
		);
	}

	$selectedLocations = null;
	if ( count( $locations ) > 0 ) {
		$selectedLocations = array(
			'taxonomy' => 'event-location',
			'field' => 'slug',
			'terms' => $locations
		);
	}

	// Final args
	$args = array(
		'post_type' => 'events',
		'posts_per_page' => -1,

		'tax_query' => array(

			'relation' => 'AND',

			$selectedLengths,
			$selectedTimes,
			$selectedLocations

		),

		's' => $searchText
	);
	
	// Query
	$query = new WP_Query ( $args );

	// The code below is very helpful for debugging
	/*
	echo '<h3>$_POST</h3>';
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	echo '<br>';
	echo '<br>';
	echo '<h3>WP_Query</h3>';
	echo '<pre>';
	print_r($args);
	echo '</pre>';
	*/
	
?>
<ul class="events__list" id="events__list">
<?php if ( $query->have_posts() ) : ?>
    <?php while( $query->have_posts() ) : $query->the_post(); ?>
    <li class="event">
        <div class="event__header">
            <div class="event__location">
                <?php if ( get_the_terms( get_the_ID(), 'event-length' ) != null ) : ?>
                <?php foreach ( get_the_terms( get_the_ID(), 'event-length' ) as $length ) : ?>
                <span class="length--<?php echo strtolower( $length->name ); ?>"><?php echo $length->name ?></span>
                <?php endforeach; ?>
                <?php endif; ?>

                <?php if ( get_the_terms( get_the_ID(), 'event-time' ) != null ) : ?>
                <?php foreach ( get_the_terms( get_the_ID(), 'event-time' ) as $time ) : ?>
                <span class="time--<?php echo strtolower( $time->name ); ?>"><?php echo $time->name ?></span>
                <?php endforeach; ?>
                <?php endif; ?>

                <?php if ( get_the_terms( get_the_ID(), 'event-location' ) != null ) : ?>
                <?php foreach ( get_the_terms( get_the_ID(), 'event-location' ) as $location ) : ?>
                <span class="location--<?php echo strtolower( $location->name ); ?>"><?php echo $location->name ?></span>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <a class=" event__title" href="<?php the_permalink(); ?>"><h2><?php the_title(); ?></h2></a>
        </div>
        <div class="event__description">
            <?php the_excerpt(); ?>
        </div>
    </li>
    <?php endwhile; ?>
    <?php else : ?>
    <li>0 matches</li>
<?php endif; ?>
</ul>
<?php wp_reset_postdata(); die(); } ?>
