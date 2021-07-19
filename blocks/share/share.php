<?php

/**
 * @param $title - string text - a subject line for the email sharing
 * @param $link - string URL - a URL that buttons should share to
 * @param $description - string text - description for pinterest
 * @param $image - string URL - image url for pinterest
 */

$title = !empty($title) ? $title : 'Share';

?>
<div class="share-button--container">
  <ul class="share-button--links flex align-center">
    <li>
      <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo $link; ?>"><?php echo render_svg('facebook'); ?></a>
    </li>
    <li>
      <a target="_blank" href="http://twitter.com/share?url=<?php echo $link; ?>"><?php echo render_svg('twitter'); ?></a>
     </li>
    <?php if(!empty($description) && !empty($image)): ?>
    <li>
      <a target="_blank" href="http://pinterest.com/pin/create/bookmarklet/?media=<?php echo $image; ?>&url=<?php echo $link; ?>&is_video=false&description=<?php echo $description; ?>" target="_blank">
        <?php echo render_svg('pinterest'); ?></a>
    </li>
    <?php endif; ?>
    <li>
      <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $link; ?>"><?php echo render_svg('linkedin'); ?></a>
    </li>
    <li> <a style="padding-top:2px" target="_blank" href="mailto:?subject=<?php echo $title; ?>&body=<?php echo $link; ?>"><?php echo render_svg('email'); ?></a>
    </li>
  </ul>
</div>