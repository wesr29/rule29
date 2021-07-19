<?php
  $posts_page = get_option('page_for_posts');
  $category = get_field('category', $posts_page);
  $featured_post = get_field('featured_post', $posts_page);

  if(!empty($category)){
    $category = get_term_by('ID', $category, 'category');
  }

  block('header');
  block('featured-post', [ 'featured_post' => $featured_post ]);
  block('post-listing', [ 'category' => $category ]);
  block('footer');
?>