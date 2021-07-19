<?php
  /* Template name: Post Landing */
  $category = get_field('category');
  $featured_post = get_field('featured_post');  
  if(!empty($category)){
    $category = get_term_by('ID', $category, 'category');
  }
  block('header');
  block('featured-post', [ 'featured_post' => $featured_post, 'category' => $category ]);
  block('post-listing', [ 'category' => $category ]);
  block('footer');
?>