<?php
  /**
   * Template Name: Content Only
   */
  block('header');
  block('hero');
  block('content', [
    'columns' => 'one',
    'content' => apply_filters('the_content', $post->post_content) ]),
    'content_2' => '',
    'content_3' => '',
  ]);
  block('footer');
?>