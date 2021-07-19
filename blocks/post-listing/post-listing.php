<?php
  /**
   * Creates a post listing app, 
   * We really just use this to pass data into a component
   * @param $post_type - a WP post_type slug
   * @param $categories - an array of WP categories to filter by
   * @param $tags - an array of WP tags to filter by
   * @param $category - a WP category taxonomy object to prefilter by
   */

  // if we are on a category or tag page, handle that
  if(empty($post_type)){

    $search_term = ( !empty($_GET) && !empty($_GET['search']) ) ? $_GET['search'] : '';
    
    $queried_object = get_queried_object();

    global $wpdb;
    $author_ids = $wpdb->get_results("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = 'author_credit'");
    $authors = [];

    if(!empty($author_ids)){
      foreach($author_ids as $id){
        $authors = get_posts([
          'numberposts' => -1,
          'post_type' => 'team-members',
          'post__in' => wp_list_pluck($author_ids, 'meta_value')
        ]);
      }
    }
    
    $topics = get_terms([
      'taxonomy' => 'topic',
      'hide_empty' => true,
    ]);

    if(!empty($queried_object)){
      if(!empty($queried_object->taxonomy)){
        if($queried_object->taxonomy == 'post_tag'){
          $categories = get_categories();
          $tags = get_tags();
          $queried_tag_id = $queried_object->term_id;
          $preset_tag = $queried_object;
        } else if($queried_object->taxonomy == 'category'){
          $categories = get_categories();
          $tags = get_tags();
          $queried_category_id = $queried_object->term_id;
          $preset_category = $queried_object;
        } else if($queried_object->taxonomy == 'topic'){
          $categories = get_categories();
          $tags = get_tags();
          $queried_topic_id = $queried_object->term_id;
          $preset_topic = $queried_object;
        }
      } else {
        $categories = get_categories();
        $tags = get_tags();
      }
    }
  }

  if(!empty($category)){
    $preset_category = $category;

    //remove any topics and authors who don't have any posts in this category
    // dd($category);
  }
?>
<div id="post-app">
  <post-search 
    :categories='<?php echo !empty($categories) ? vue_data($categories) : "[]"; ?>'
    :tags='<?php echo !empty($tags) ? vue_data($tags) : "[]"; ?>'
    :authors='<?php echo !empty($authors) ? vue_data($authors) : "[]"; ?>'
    :topics='<?php echo !empty($topics) ? vue_data($topics) : "{}"; ?>'
    :preset-category='<?php echo !empty($preset_category) ? vue_data($preset_category) : "{}"; ?>'
    :preset-topic='<?php echo !empty($preset_topic) ? vue_data($preset_topic) : "{}"; ?>'
    :preset-tag='<?php echo !empty($preset_tag) ? vue_data($preset_tag) : "{}"; ?>'
    :search-term='<?php echo !empty($search_term) ? vue_data($search_term) : "{}"; ?>'
    :pagination="false" 
  ></post-search>
</div>