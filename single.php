<?php
  /*$author_credit = get_field('author_credit');

  if(!empty($author_credit)){
    $authors_posts = get_posts([ 
      'post__not_in' => [ $post->ID ], 
      'numberposts' => 2,
      'meta_key' => 'author_credit',
      'meta_value' => $author_credit->ID
    ]);
  }*/
  $chosen_posts = get_field('related_posts');
  if(empty($chosen_posts)){
    $related_posts = get_posts([ 
      'post__not_in' => [ $post->ID ], 
      'numberposts' => 2,
    ]);
  }
  else{
    $related_posts = $chosen_posts;
  }

  block('header');
  block('hero-single-post');
  block('single-post');
  if(!empty($related_posts)){
    block('featured-posts', [ 
      'posts' => $related_posts,
      'twoposts' => true,
    ]);
  }
  block('footer');
?>