<?php
 /**
  * @param $capabilities - repeater array
  * @param $content - string html
  * @param $image - WP Image ID
  * @param $links - repeater array
  * @param $icon - select, svg
  */
  if(empty($capabilities)){
    return; 
  }
?>
<section class="capabilities">
  <div class="wrapper">
    <?php foreach($capabilities as $capability): ?>
      <div class="capability">
        <div class="capability--content morph-icon--hover">
          <div class="will-animate" data-animation="fade-from-bottom"><?php block('morph-icon', [ 'icon' => $capability['icon'] ]); ?></div>
          <?php echo !empty($capability['content']) ? '<div class="editor-content will-animate" data-animation="fade-from-bottom">'.$capability['content'].'</div>' : ''; ?>

          <?php if(!empty($capability['links'])): 
            if(count($capability['links']) > 3){
              $capabilityClass = 'flex flex-wrap';
            }
            else{
              $capabilityClass = '';
            }
          ?>
            <ul class="capability--links will-animate <?php echo $capabilityClass; ?>" data-animation="fade-from-bottom">
              <?php foreach($capability['links'] as $link): ?>
                <li class="capability--link"><?php echo acf_link($link['link']); ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
        <div class="capability--image">
          <?php echo !empty($capability['image']) ? wp_image($capability['image'], 'large') : ''; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>