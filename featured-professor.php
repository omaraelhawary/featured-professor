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
  }

  function onInit() {
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