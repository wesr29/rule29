<?php
  /**
   * Template Name: Contact
   */
  block('header');
  block('hero-inner');
  block('content', [ 'content' => apply_filters('the_content', $post->post_content) ]);
  block('contact-page-form');
  block('footer');
?>
