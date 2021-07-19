<?php
  block('header');
  block('hero-inner');
  build_modules(get_field('modules'));

  // block('default-page');
  // block('content', [ 'content' => apply_filters('the_content', $post->post_content) ]);
  // block('video', [
  //   'video' => get_field('video_url'),
  //   'image' => get_field('video_poster'),
  // ]);
  // if(!empty(get_field('sections'))){
  //   foreach(get_field('sections') as $section){
  //     block('side-by-side-content-image', [
  //       'content' => $section['content'],
  //       'image' => $section['image'],
  //       'fullbleed_image' => '',
  //       'image_position' => $section['image_position']
  //     ]);
  //   }
  // }
  // if(!empty(get_field('content_sections'))){
  //   foreach(get_field('content_sections') as $section){
  //     block('content', [
  //       'content' => $section['content'],
  //       'content_2' => !empty($section['content_2']) ? $section['content_2'] : '',
  //       'content_3' => !empty($section['content_3']) ? $section['content_3'] : '',
  //       'classes' => '',
  //       'columns' => $section['columns']
  //     ]);
  //   }
  // }
  // if(!empty(get_field('featured_blog_posts'))){
  //   $posts = get_field('featured_blog_posts');
  // }
  // else{
  //   $posts = get_posts([
  //     'numberposts' => 2,
  //     'post__not_in' => wp_list_pluck($posts, 'ID')
  //   ]);
  // }
  // block('featured-posts', [ 
  //   'posts' => $posts,
  //   'twoposts' => 'true'
  // ]);
  
  block('footer');
?>