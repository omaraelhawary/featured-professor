<?php

/**
 * Generates HTML for a list of posts that feature a specific professor.
 *
 * @param int $id The ID of the professor.
 * @return string The HTML for the list of posts.
 */
function relatdPostsHTML($id){
    $postsAboutThisProf = new WP_Query(array(
        'posts_per_page' => -1,
        'post_type' => 'post',
        'meta_query' => array(
            array(
                'key' => 'featured_professor',
                'compare' => '=',
                'value' => $id
            )
        )
    ));

    ob_start();

    if($postsAboutThisProf -> found_posts){ ?>
        <p> <?php the_title(); ?> is featured in the following posts: </p>
        <ul>
            <?php 
                while($postsAboutThisProf -> have_posts()){
                    $postsAboutThisProf -> the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php }
            ?>
        </ul>
    <?php }

    wp_reset_postdata();
    return ob_get_clean();
}