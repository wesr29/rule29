<?php
  /**
   * @param $featured_post - post with 'featured' category
   * @param $category - WP category taxonomy object
   */

  //if the featured post is empty, we should try and find one for them. 
  if(empty($featured_post)){
    $tax_query = [ 'relation' => 'AND' ];

    //if a category has been passed, make sure to look for a post in that category
    if(!empty($category)){
      $tax_query[] = [
        'taxonomy' => 'category',
        'field'    => 'slug',
        'terms'    => $category->slug
      ];
    }

    //pull off the featured post
    $posts = get_posts([ 'numberposts' => 1, 'tax_query' => $tax_query ]);
    $featured_post = !empty($posts) ? array_shift($posts) : null;    
  }

  //if there is no featured post even after trying to find one, just bail
  if(empty($featured_post)){
    return false;
  }

  //data to display
  $term = !empty($category) ? $category : get_primary_category($featured_post->ID);
  $author_credit = get_field('author_credit', $featured_post);
  if(!empty($author_credit)){
    $author = get_post($author_credit);
  }
  $image = get_post_thumbnail_id($featured_post);
  $permalink = get_the_permalink($featured_post);
?>
<section class="post-listing--featured-post bg-light-blue relative">
  <div class="wrapper">
    <div class="post-listing--featured-post--content">
      <h1 class="post-listing--featured-post--section-title">Featured Post</h1>
      <?php block('category-badge', [ 'category' => $term ]); ?>
      <a href="<?= $permalink ?>" class="post-listing--featured-post--title h3"><?= $featured_post->post_title ?></a>
      <?php if(!empty($author)): ?>
        <div class="featured-posts--author">
          <em>by</em> <a href="<?= get_the_permalink($author) ?>"><?= $author->post_title ?></a>
        </div>
      <?php endif; ?>
      <p class="featured-posts--copy"><?= $featured_post->post_excerpt ?></p>
      <a href="<?= $permalink ?>" class="button button--dark-blue post-listing--featured-post--read-more"><?= $term->name == 'Podcast' ? 'Listen Now' : 'Read More' ?></a>
    </div>
    <div class="post-listing--featured-post--image">
      <?= !empty($image) ? wp_image($image, 'large') : wp_image(get_field('default_post_image', 'option')) ?>
    </div>
  </div>
</section>