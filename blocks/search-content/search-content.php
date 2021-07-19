<?php
  global $wp_query;
  $found_posts = $wp_query->posts;
  $term = (isset($_GET['s'])) ? $_GET['s'] : ''; // Get 's' querystring param

  //add any extra data to posts
  array_map(function($single){
    $single->highlighted_title = preg_replace('/('.implode('|', explode(" ", get_search_query(false))) .')/iu', '<span class="search-content--highlight">\0</span>', $single->post_title);
    //Using Relevanssi's excerpt instead
    //$single->highlighted_content = preg_replace('/('.implode('|', explode(" ", get_search_query(false))) .')/iu', '<span class="search-content--highlight">\0</span>', get_words(strip_tags(strip_shortcodes($single->post_content)), 50));
  }, $found_posts);
?>
<div class="wrapper search--wrapper">
  <?php if ( !empty($found_posts) ) : ?>
    <h1 class="search--title">Showing Results for: "<?php echo get_search_query(); ?>"</h1>
    <?php foreach($found_posts as $single): ?>
      <article class="search--post content-editor">
        <?php block('category-badge', ['category' => get_primary_category($single->ID)]); ?>
        <h2 class="search--post--title"><a href="<?php echo get_the_permalink($single->ID); ?>"><?php echo $single->highlighted_title; ?></a></h2>
        <p class="search--post--excerpt"><?php echo relevanssi_do_excerpt($single, $term); ?></p>
      </article>
    <?php endforeach; ?>
    <?php echo do_shortcode('[ajax_load_more pause="true" search="'. $term .'" scroll="false" button_label="Load 5 More Results" post_type="post, page, case_studies, rules, downloads"]'); ?>
  <?php else: ?>
    <div class="block-section content-editor"><h2>No results found</h2></div>
  <?php endif; ?>
</div>