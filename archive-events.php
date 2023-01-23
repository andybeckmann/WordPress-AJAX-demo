<?php
/**
 * Archive: Events
 */
?>

<?php get_header(); 
global $wp_query; ?>

<main>

    <div class="events">
        <h1>Events</h1>
        <form class="events__form" id="events__form">
            <div class="events__form-fields">
                <div class="events__search" id="events__search">
                    <input type="text" placeholder="Search for event" name="search--text">
                </div>
                <div class="events__filters" id="events__filters">
                    <?php
                        function listTaxonomyTermsAsOptions ($taxonomies, $query) {
                            foreach ($taxonomies as $taxonomy) {
                                $terms = get_terms(['taxonomy' => $taxonomy]);
                                echo '<select name="' . $taxonomy . '">';

                                if ( isset( $query->get[$taxonomy] ) ) {
                                    echo '<option value="" disabled>' . ucfirst( substr( $taxonomy, 6 ) ) . '</option>';
                                } else {
                                    echo '<option value="" disabled selected>' . ucfirst( substr( $taxonomy, 6 ) ) . '</option>';
                                }

                                foreach ( $terms as $term ) {

                                    if ( strpos( $query->query[$taxonomy], strtolower($term->name) ) !== false ) {
                                        echo '<option value="' . $taxonomy . '--' . $term->name . '" selected>' . $term->name . '</option>';
                                    } else {
                                        echo '<option value="' . $taxonomy . '--' . $term->name . '">' . $term->name . '</option>';
                                    }
                                }

                                echo '</select>';
                            }
                        }
                        listTaxonomyTermsAsOptions(['event-length', 'event-time', 'event-location'], $wp_query);
                    ?>
                </div>
            </div>
            <div class="events__button">
                <input type="hidden" name="action" value="filter">
                <input type="submit" id="events__submit" value="Search">
                <input type="reset" id="events__reset" value="Reset">
            </div>
        </form>

        <ul class="events__list" id="events__list">
        <?php if ( have_posts() ) : ?>
            <?php while( have_posts() ) : the_post(); ?>
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

    </div>

</main>

<?php get_footer(); ?>
