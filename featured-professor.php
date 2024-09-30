<?php

/*
  Plugin Name: Featured Professor Block Type
  Description: This plugin to show featured professor in Posts.
  Version: 1.0
  Author: Omar ElHawary
  Author URI: https://linkedIn.com/in/omar-elhawary
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once plugin_dir_path( __FILE__ ). 'inc/generateProfHTML.php';
require_once plugin_dir_path( __FILE__ ). 'inc/relatedPostsHTML.php';

class FeaturedProfessor {
  /**
   * Initializes the FeaturedProfessor class by setting up WordPress hooks.
   *
   * @return void
   */
  function __construct() {
    add_action('init', [$this, 'onInit']);
    add_action('rest_api_init', [$this, 'profHTML']);
    add_filter('the_content', [$this, 'addRelatedPosts']);
  }

  /**
   *  Appends related posts HTML to the content if the current post is a professor post.
   *
   *  @param string $content The content to be appended to.
   *  @return string The content with related posts HTML appended if applicable.
   */
  function addRelatedPosts($content){
    if(is_singular('professor') && in_the_loop() && is_main_query()){
      return $content . relatdPostsHTML(get_the_id());
    }
    return $content;
  }

  /**
   * Registers a REST route to retrieve the HTML for a featured professor.
   *
   * @return void
   */
  function profHTML(){
    register_rest_route( 'featuredProfessor/v1', 'getHTML', array(
      'methods' => WP_REST_SERVER::READABLE,
      'callback' => [$this, 'getProfHTML'],
    ));
  }

  /**
   * Retrieves the HTML for a featured professor based on the provided data.
   *
   * @param array $data An array containing the professor's ID.
   * @return string The HTML for the featured professor.
   */
  function getProfHTML($data){
    return generateProfHTML($data['profId']);
  }

  /**
   * Initializes the plugin by registering a meta field and a block type.
   *
   * @return void
   */
  function onInit() {
    register_meta('post', 'featured_professor', array(
      'show_in_rest' => true,
      'type' => 'number',
      'single' => false
    ));
    register_block_type(__DIR__, array(
      'render_callback' => [$this, 'renderCallback'],
    ));
  }

  /**
   * Renders the HTML for a featured professor based on the provided attributes.
   *
   * @param array $attributes An array containing the professor's ID.
   * @return string|null The HTML for the featured professor, or NULL if no professor ID is provided.
   */
  function renderCallback($attributes) {
    if(isset($attributes['profId'])) {
      return generateProfHTML($attributes['profId']);
    } else {
        return NULL;
    }
  }

}

$featuredProfessor = new FeaturedProfessor();