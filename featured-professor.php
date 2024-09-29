<?php

/*
  Plugin Name: Featured Professor Block
  Version: 1.0
  Author: Omar ElHawary
  Author URI: https://www.linkedin.com/in/omaraelhawary/
  Description:    Add featured professors to your posts
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FeaturedProfessor {
  function __construct() {
    add_action('init', [$this, 'onInit']);
  }

  function onInit() {
    register_block_type('ourplugin/featured-professor', array(
      'render_callback' => [$this, 'renderCallback'],
    ));
  }

  function renderCallback($attributes) {
    return '<p>We will replace this content soon.</p>';
  }

}

$featuredProfessor = new FeaturedProfessor();