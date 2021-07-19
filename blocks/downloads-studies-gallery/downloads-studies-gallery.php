<?php
 /**
  * @param $featured_posts- array of posts to feature
  * @param $callouts[] | array
  * @param $callouts[][image] | WP image ID
  * @param $callouts[][pretitle] | string text
  * @param $callouts[][title] | string text
  * @param $callouts[][button] | ACF link
  */

  if(empty($callouts) && empty($featured_posts)){
    return false;
  }

  if(!empty($featured_posts)){
    array_map(function($single){
      if(has_post_thumbnail($single)){
        $single->image = get_post_thumbnail_id($single);
      } else {
        $single->image = get_field('default_post_image', 'option');
      }
      $blurb = get_field('blurb', $single); 
      $word_limit = get_words($blurb, 10);
      $single->blurb = $word_limit == $blurb ? $word_limit : "$word_limit...";
      $single->permalink = get_the_permalink($single); 
      $single->file = get_field('file', $single); 
      $single->file_url = !empty( $single->file) ? $single->file['url'] : null;
      return $single;
    }, $featured_posts);
  }

  //fake callouts to act like posts so we don't have to double the HTML
  if(!empty($callouts)){
    $featured_posts = array_map(function($callout){
      $single = new stdClass();
      $single->image = $callout['image'];
      $single->post_title = $callout['pretitle'];
      $single->blurb = $callout['title'];
      $single->permalink = !empty($callout['button']['url']) ? $callout['button']['url'] : '';
      $single->button_text = !empty($callout['button']['title']) ? $callout['button']['title'] : '';
      return $single;
    }, $callouts);
  }

?>
<section class="downloads-studies-gallery p-t-70 p-b-70">
  <div class="wrapper">
    <div class="row">
      <?php foreach($featured_posts as $single): ?>
        <?php if(get_post_type($single) == 'case_studies' || !empty($callouts)): ?>
          <div class="col-lg-4 col-sm-4 col-xs-12 col case-study downloads-studies-gallery--post">
            <div class="downloads-studies-gallery--aspect-force">
              <div class="downloads-studies-gallery--post--inner relative will-animate" data-animation="fade-from-bottom">
                <?php echo wp_image($single->image, 'full', ['class' => 'downloads-studies-gallery--post--image']); ?>
                <div class="downloads-studies-gallery--post--hover">
                  <h2 class="downloads-studies-gallery--post--title"><?= $single->post_title; ?></h2>
                  <?php echo !empty($single->blurb) ? '<p class="downloads-studies-gallery--post--blurb">'.$single->blurb.'</p>' : ''; ?>
                  <a href="<?= $single->permalink; ?>" class="button button--white button--outline">
                    <?php if(!empty($callouts)): ?>
                      <?= $single->button_text ?>
                    <?php else: ?>
                      View the Case Study
                    <?php endif; ?>
                  </a>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <?php if(get_post_type($single) == 'downloads'): ?>
          <div class="col-lg-4 col-sm-4 col-xs-12 col download downloads-studies-gallery--post">
            <div class="downloads-studies-gallery--aspect-force">
              <div class="downloads-studies-gallery--post--inner relative will-animate" data-animation="fade-from-bottom">
                <?php echo wp_image($single->image, 'full', ['class' => 'downloads-studies-gallery--post--image']); ?>
                <div class="downloads-studies-gallery--post--hover">
                  <h2 class="downloads-studies-gallery--post--blurb"><?= $single->post_title; ?></h2>
                  <a href="<?php echo $single->file_url; ?>" class="button button--white button--outline" download>Download</a>
                  <div class="downloads-studies-gallery--post--share m-t-25">
                    <h3 class="downloads-studies-gallery--post--share-title">Share This Download</h3>
                    <?php block('share', [ 'title' => $single->post_title, 'link' => $single->file_url ]); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    
    </div>
  </div>
</section>