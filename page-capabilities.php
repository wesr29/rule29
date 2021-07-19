<?php
  /**
   * Template Name: Capabilities
   */
  block('header');
  block('hero-inner');
  block('content', [ 'content' => apply_filters('the_content', $post->post_content) ]);
  block('content', [
    'content' => get_field('intro_content'),
    'classes' => 'capabilities--intro-content',
    'columns' => 'one'
  ]);
  block('capabilities', ['capabilities' => get_field('sections')]);
  block('footer');
?>
