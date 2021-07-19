<?php
  /**
   * Template Name: Our Process
   */

  block('header');
  block('content', [ 'content' => apply_filters('the_content', $post->post_content ) ] );
  block('our-process');
  block('footer');
