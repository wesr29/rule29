<?php
  block('header');
  block('hero-inner');
  block('content-capabilities', [
    'content' => get_field('intro_content'),
    'capabilities' => get_field('capabilities'),
    'color' => get_field('color')
  ]);
  echo build_modules(get_field('modules'));
  block('footer');
?>