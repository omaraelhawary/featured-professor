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

class FeaturedProfessor {
  function __construct() {
    add_action('init', [$this, 'onInit']);
    add_action('rest_api_init', [$this, 'profHTML']);
  }

  function profHTML(){
    register_rest_route( 'featuredProfessor/v1', 'getHTML', array(
      'methods' => WP_REST_SERVER::READABLE,
      'callback' => [$this, 'getProfHTML'],
    ));
  }

  function getProfHTML($data){
    return generateProfHTML($data['profId']);
  }

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

  function renderCallback($attributes) {
    if(isset($attributes['profId'])) {
      return generateProfHTML($attributes['profId']);
    } else {
        return NULL;
    }
  }

}

$featuredProfessor = new FeaturedProfessor();