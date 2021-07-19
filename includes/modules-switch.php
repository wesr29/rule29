<?php 

function build_modules($modules) {
  if (!empty($modules)) {
    foreach ($modules as $module) {
       switch($module['acf_fc_layout']){
        case 'content':
            block('content', [
              'columns'          => $module['columns'],
              'content'          => $module['content'],
              'content_2'        => $module['content_2'],
              'content_3'        => $module['content_3'],
              'padding'          => !empty($module['padding']) ? $module['padding'] : null,
              'background_color' => !empty($module['background_color']) ? $module['background_color'] : null,
              'text_color'       => !empty($module['text_color']) ? $module['text_color'] : null,
            ]);
          break;
        case 'video':
            block('video', [
              'video'   => $module['video_url'],
              'image'   => $module['video_poster'],
              'padding' => !empty($module['padding']) ? $module['padding'] : null,
            ]);
          break;
        case 'image_blocks':
            block('image-blocks', [
              'columns' => $module['columns'],
              'caption_color_override' => $module['caption_color_override'],
              'blocks' => $module['blocks'],
              'padding' => !empty($module['padding']) ? $module['padding'] : null,
            ]);
          break;
        case 'client_divider':
            block('client-divider', [
              'title' => $module['title'],
              'content' => $module['content'],
              'color' => !empty($module['color']) ? $module['color'] : '#002E5D',
              'image' => $module['image'],
              'padding' => !empty($module['padding']) ? $module['padding'] : null,
            ]);
          break;
        case 'gallery':            
           block('gallery-slider', [
            'slides' => $module['images'],
            'padding' => !empty($module['padding']) ? $module['padding'] : null,
           ]);
          break;
        case 'testimonial':            
           block('testimonial', [
            'quote' => $module['quote'],
            'attribution_line_1' => $module['attribution_line_1'],
            'attribution_line_2' => $module['attribution_line_2'],
            'padding' => !empty($module['padding']) ? $module['padding'] : null,
           ]);
          break;
        case 'logos':            
           block('logos-grid', [ 
            'title' => $module['logos_title'],
            'logos' => $module['logos'],
            'classes' => !empty($module['background_color']) ? $module['background_color'] : 'bg-light-blue',
            'logos_animate_individual' => true,
            'padding' => !empty($module['padding']) ? $module['padding'] : null,            
          ]);
          break;
        case 'stats':            
           block('countup', [ 
            'stats' => $module['stats'],
            'padding' => !empty($module['padding']) ? $module['padding'] : null,            
          ]);
          break;
        case 'bar_chart':            
           block('bar-charts', [ 
            'padding' => !empty($module['padding']) ? $module['padding'] : null,
            'charts' => $module['charts'],
          ]);
          break;
        case 'case_studies':            
          block('case-studies', [ 
            'case_studies' => $module['case_studies'],
            'padding'      => $module['padding']
          ]);
          break;
        case 'featured_posts':            
          block('featured-posts', [ 
            'posts'    => $module['featured_posts'],
            'padding'  => $module['padding'],
            'twoposts' => $module['disable_autopopulate'],
            'selected_featured_post' => ''
          ]);
          break;
        case 'side_by_side_content_image':
          block('side-by-side-content-image', [
            'content' => $module['content'],
            'image' => $module['image'],
            'fullbleed_image' => '',
            'image_position' => $module['image_side'],
            'padding' => $module['padding'],
          ]);
          break;
        case 'gallery_callouts':
          block('downloads-studies-gallery', [ 'callouts' => $module['callouts'] ]);
          break;
        case 'spacer':
          echo '<div class="custom-padding bg-'.$module['color'].'" style="'.build_custom_padding_str($module['padding']).'"></div>';
          break;
        case 'image':
          echo '<div class="custom-padding" style="'.build_custom_padding_str($module['padding']).'"><div class="wrapper text-center">'.wp_image($module['image'], 'full').'</div></div>';
          break;
      }
    }
  }
}