<?php
  /**
   * Template Name: Our Team
   */
  block('header');
  block('hero-inner');
  block('content', [ 'content' => apply_filters('the_content', $post->post_content) ]);
  block('team-grid');
  block('footer');
?>