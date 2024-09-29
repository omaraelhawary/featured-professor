<?php

/*
  Plugin Name: Featured Professor Block Type
  Description: This plugin to show featured professor in Posts.
  Version: 1.0
  Author: Omar ElHawary
  Author URI: https://linkedIn.com/in/omar-elhawary
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
    return '<p>We will replace this content soon.</p>';
  }

}

$featuredProfessor = new FeaturedProfessor();