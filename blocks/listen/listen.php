<?php

/**
 * @param $spotify - String URL
 * @param $google - String URL
 * @param $apple - String URL
 * @param $stitcher - String URL
 */

if(empty($spotify) && empty($google) && empty($apple) && empty($stitcher)){
  return false;
}

?>
<div class="listen-button--container">
  <ul class="listen-button--links flex align-center">
    <?php if(!empty($spotify)): ?>
    <li>
      <a target="_blank" href="<?php echo $spotify; ?>"><?php echo render_svg('spotify'); ?></a>
    </li>
    <?php endif; ?>
    <?php if(!empty($google)): ?>
    <li>
      <a target="_blank" href="<?php echo $google; ?>"><?php echo render_svg('google-podcast'); ?></a>
    </li>
    <?php endif; ?>
    <?php if(!empty($apple)): ?>
    <li>
      <a target="_blank" href="<?php echo $apple; ?>"><?php echo render_svg('apple'); ?></a>
    </li>
    <?php endif; ?>
    <?php if(!empty($stitcher)): ?>
    <li>
      <a target="_blank" href="<?php echo $stitcher; ?>"><?php echo render_svg('stitcher'); ?></a>
    </li>
    <?php endif; ?>
  </ul>
</div>