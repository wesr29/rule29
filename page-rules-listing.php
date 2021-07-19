<?php
  /**
   * Template Name: Rules Listing
   */
  block('header');
  block('hero-inner');
  block('content', [ 'content' => apply_filters('the_content', $post->post_content) ]);
  block('rules-listing');
  block('footer');
?>
