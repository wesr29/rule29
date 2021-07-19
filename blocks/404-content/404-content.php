<?php
  $content = get_field('404_content', 'options');
  $video = get_field('404_video', 'options');
  $video_ratio = get_field('404_video_ratio', 'options');
?>
<div class="error404--content bg-medium-light-blue">
  <?php echo !empty($video) ? '<iframe data-aspect-ratio="'.$video_ratio.'" class="error404--video" src="'.convert_video_link_for_iframe($video, [ 'autoplay' => 1, 'background' => 1, 'mute' => 1, 'controls' => 0, 'modestbranding' => 1, 'kbcontrols' => 0, 'loop' => 1 ]).'"></iframe>' : ''; ?>
  <div class="wrapper editor-content">
    <?= $content ?>
  </div>
</div>