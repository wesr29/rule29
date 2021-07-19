<?php

/**
 * Any AJAX functions can go here
 */

/**
 * Find posts based on search and category
 */
function fetch_posts(){
  $per_page = get_option('posts_per_page');

  $args = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'offset'         => ($_REQUEST['page'] - 1) * $per_page
  ];

  if(!empty($_REQUEST['search'])){
    $args['s'] = $_REQUEST['search'];
  }

  $args['tax_query'] = [ 'relation' => 'AND' ];

  if(!empty($_REQUEST['category'])){
    $args['tax_query'][] = [
      'taxonomy' => 'category',
      'terms'    => $_REQUEST['category']['term_id'],
      'field'    => 'term_id',
    ];
  }
  if(!empty($_REQUEST['topic'])){
    $args['tax_query'][] = [
      'taxonomy' => 'topic',
      'terms'    => $_REQUEST['topic'],
      'field'    => 'term_id',
    ];
  }

  if (!empty($_REQUEST['author'])){ 
    $args['meta_query'] = [[
      'value' => $_REQUEST['author'],
      'key' => 'author_credit'
    ]];
  }

  if(!empty($_REQUEST['tag'])){
    $args['tag_id'] = $_REQUEST['tag'];
  }

  $query = new WP_Query($args);

  //add any meta we need
  array_map(function($post){
    $post->permalink = get_the_permalink($post);
    $post->excerpt   = get_the_excerpt($post);

    // category
    $post->category = get_primary_category($post->ID);
    $post->category_color = get_field('color', $post->category);

    // author
    $author = get_field('author_credit', $post);
    if(!empty($author)){
      $post->author = $author->post_title;
      $post->author_permalink = get_the_permalink($author);
    }

    // image
    if(has_post_thumbnail($post->ID)){
      $image = get_post_thumbnail_id($post->ID);
    }
    else{
      $image = get_field('default_post_image', 'options');
    }


    $post->image = wp_image($image, 'large', ['class' => 'post-listing--featured-image']);
  }, $query->posts);

  wp_send_json_success([ 
    'posts'      => $query->posts, 
    'maxPages'   => $query->max_num_pages,
    'postsFound' => $query->found_posts
  ]);
}
add_action( 'wp_ajax_fetch_posts', 'fetch_posts');
add_action( 'wp_ajax_nopriv_fetch_posts', 'fetch_posts');


/**
 * Sends data from the contact form to sharpspring
 */
function send_contact_form_to_sharpspring(){
  // <script type="text/javascript">
  // var __ss_noform = __ss_noform || [];
  // __ss_noform.push(['baseURI', 'https://app-3QNBYBDKVE.marketingautomation.services/webforms/receivePostback/MzawMDEzNrIwBwA/']);
  // __ss_noform.push(['endpoint', 'bec62c3f-0de1-4c76-81dd-2171b7606975']);
  // </script>
  // <script type="text/javascript" src="https://koi-3QNBYBDKVE.marketingautomation.services/client/noform.js?ver=1.24" ></script>
  $baseURL = "https://app-3QNBYBDKVE.marketingautomation.services/webforms/receivePostback/MzawMDEzNrIwBwA/";
  $endPoint = "bec62c3f-0de1-4c76-81dd-2171b7606975";

  $result =  send_data_to_sharp_spring($baseURL, $endPoint, [
    'first_name' => $_REQUEST['firstName'],
    'last_name' => $_REQUEST['lastName'],
    'email'     => $_REQUEST['email'],
    'help_with' => $_REQUEST['helpWith'],
    'message'   => $_REQUEST['message']
  ]);

  if($result){
    wp_send_json_success();
  } else {
    wp_send_json_error();
  }
}
add_action( 'wp_ajax_send_contact_form_to_sharpspring', 'send_contact_form_to_sharpspring');
add_action( 'wp_ajax_nopriv_send_contact_form_to_sharpspring', 'send_contact_form_to_sharpspring');

function send_contact_page_form_to_sharpspring(){
  // https://help.sharpspring.com/hc/en-us/articles/115002321292-Submitting-Directly-to-Native-Form-Endpoints
  // <script type="text/javascript">
  //     var __ss_noform = __ss_noform || [];
  //     __ss_noform.push(['baseURI', 'https://app-3QNBYBDKVE.marketingautomation.services/webforms/receivePostback/MzawMDEzNrIwBwA/']);
  //     __ss_noform.push(['endpoint', '84fbedb0-fb7f-47f2-a816-580cec965c25']);
  // </script>
  // <script type="text/javascript" src="https://koi-3QNBYBDKVE.marketingautomation.services/client/noform.js?ver=1.24" ></script>
  $baseURL = "https://app-3QNBYBDKVE.marketingautomation.services/webforms/receivePostback/MzawMDEzNrIwBwA/";
  $endPoint = "84fbedb0-fb7f-47f2-a816-580cec965c25";

  $result =  send_data_to_sharp_spring($baseURL, $endPoint, [
    'userPurpose' =>  $_REQUEST['userPurpose'],
    'projectChoices' =>  $_REQUEST['projectChoices'],
    'firstName' =>  $_REQUEST['firstName'],
    'lastName' =>  $_REQUEST['lastName'],
    'company' =>  $_REQUEST['company'],
    'email' =>  $_REQUEST['email'],
    'jobTitle' =>  $_REQUEST['jobTitle'],
    'phone' =>  $_REQUEST['phone'],
    'message' =>  $_REQUEST['message'],
  ]);

  if($result){
    wp_send_json_success();
  } else {
    wp_send_json_error();
  }
}
add_action( 'wp_ajax_send_contact_page_form_to_sharpspring', 'send_contact_page_form_to_sharpspring');
add_action( 'wp_ajax_nopriv_send_contact_page_form_to_sharpspring', 'send_contact_page_form_to_sharpspring');

/**
 * Sends data to sharpspring
 */
function send_data_to_sharp_spring($baseURL, $endPoint, $data){
  $params = "";

  foreach($data as $key => $value){
    $params .= $key . '=' . urlencode($value) . '&';
  }

  if(isset($_COOKIE['__ss_tk'])){
    $trackingid__sb = $_COOKIE['__ss_tk'];
    $params .= "trackingid__sb=" . urlencode($trackingid__sb);
  }

  // Prepare URL
  $ssRequest = $baseURL . $endPoint . "/jsonp/?" . $params;

  // Send request
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $ssRequest); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);

  if($result === FALSE) {
    dd(curl_error($ch));
  }

  curl_close($ch);

  return $result;
}