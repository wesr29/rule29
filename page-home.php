<?php
  /**
   * Template Name: Home
   */
  block('header');
  block('hero');  
  block('card-callouts', [ 'cards' => get_field('card_callouts') ]);
  build_modules(get_field('modules'));
  block('footer');
?>
