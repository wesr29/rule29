<?php
  /**
   * Template Name: Green
   */

  $heating_and_cooling = get_field('heating_and_cooling');
  $energy_usage = get_field('energy_usage');
  $recycling = get_field('recycling');
  $recycling_2 = get_field('recycling_2');
  $composting = get_field('composting');
  $water_usage = get_field('water_usage');
  $links = get_field('resources');
  $links_button = get_field('button');
  $selected_featured_post = get_field('featured_post');
  /*$green_posts = get_posts([ 
    'numberposts' => -1,
    'tax_query' => array(
      [
        'taxonomy' => 'topic',
        'field'    => 'slug',
        'terms'    => 'green'
      ]
    )
  ]);*/

  block('header');
  block('hero-inner');
  block('content', [ 'content' => apply_filters('the_content', $post->post_content) ]);
  block('circle-graph', [ 
    'content'       => $heating_and_cooling['content'],
    'data'          => $heating_and_cooling['years']
  ]);
  block('line-graph', [ 
    'content'       => $energy_usage['content'],
    'number_label'  => $energy_usage['number_label'],
    'entries'       => $energy_usage['x_axis_entries']
  ]);
  block('donut-graph', [ 
    'content'       => $recycling['content'],
    'entries'       => $recycling['entries']
  ]);
  block('bar-graph', [ 
    'content'       => $recycling_2['content'], 
    'number_label'  => $recycling_2['number_label'], 
    'entries'       => $recycling_2['entries']
  ]);
  block('curve-graph', [ 
    'content'       => $composting['content'],
    'y_axis_label'  => $composting['y_axis_label'],
    'entries'       => $composting['entries'],
  ]);
  block('droplet-graph', [ 
    'content'       => $water_usage['content'],
    'entries'       => $water_usage['entries']
  ]);
  block('featured-posts', [ 
    //'posts' => $green_posts,
    'featured_title' => 'Green Stories',
    'additional_title' => 'Green Resources',
    'links' => $links,
    'links_button' => $links_button,
    'selected_featured_post' => $selected_featured_post
  ]);
  block('footer');
?>