<?php

//require vendor files
require_once(__DIR__ . '/includes/vendor/autoload.php');

//require extra theme files
foreach (glob(__DIR__ . '/includes/*.php') as $auto_load) {
  require_once $auto_load;
}

//global theme options
add_theme_support('post-thumbnails');

theme_configuration([
  'remove_comments'             => true,
  'disable_gutenberg'           => true,
  'show_pages_submenu'          => false,
  'acf_cptui_whitelist_ids'     => [ 1 ],
  'acf_options_pages'           => [ 'Theme Options' ],
  'menus'                       => [ 'Main Menu', 'Footer Menu', 'Site Map' ],
  'wysiwyg_colors'              => [ 
    'black'             => '#333',
    'dark-blue'         => '#002E5D',
    'medium-dark-blue'  => '#466C92',
    'medium-blue'       => '#9BB8D3',
    'medium-light-blue' => '#D7E2ED',
    'light-blue'        => '#F1F5F9',
    'green'              => '#307411',
    'teal'              => '#15B5AD',
    'red'               => '#F9423A',
    'white'             => '#fff',
  ],
  'hide_wysiwyg_from_templates' => [ 'page-home.php' ],
  // 'allow_mime_uploads'          => [ [ 'svg' => 'image/svg+xml' ] ],
  'post_types'                  => [
    [
      'plural'       => 'Case Studies',
      'singular'     => 'Case Study',
      'slug'         => 'case_studies',
      'rewrite_slug' => 'case-study',
      'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
      'icon'         => 'dashicons-search'
    ],
    [
      'plural'       => 'Downloads',
      'singular'     => 'Download',
      'slug'         => 'downloads',
      'rewrite_slug' => 'download',
      'public'       => false,
      'supports'     => [ 'title', 'thumbnail' ],
      'icon'         => 'dashicons-download'
    ],
    [
      'plural'       => 'Rules',
      'singular'     => 'Rule',
      'slug'         => 'rules',
      'rewrite_slug' => 'rule',
      'supports'     => [ 'title', 'page-attributes' ],
      'icon'         => 'dashicons-book-alt'
    ],
    [
      'plural'       => 'Team Members',
      'singular'     => 'Team',
      'slug'         => 'team-members',
      'rewrite_slug' => 'team',
      'supports'     => [ 'title' ],
      'icon'         => 'dashicons-groups'
    ]
  ],
  'taxonomies'                  => [
    [
      'slug'       => 'topic',
      'singular'   => 'Topic',
      'plural'     => 'Topics',
      'post_types' => [ 'post' ],
      'hierarchical' => true
    ]
  ]
]);