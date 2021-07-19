<?php 
  /**
   * @param $charts | required | array
   * @param $chart['title'] | optional | string html to display
   * @param $chart['data'] | required | array
   * @param $chart['data']['name'] | optional | string html to display
   * @param $chart['data']['percent'] | required | number
   * @param $classes | optional | string of extra classes for this section
   * @param $padding | array of ints with keys: desktop_top|desktop_bottom|mobile_top|mobile_bottom
   */

  if(empty($charts)){
    return false;
  }

  if(empty($classes)){
    $classes = '';
  }

  $custom_padding = !empty($padding) ? build_custom_padding_str($padding) : '';
  $classes .= !empty($custom_padding) ? ' custom-padding' : '';  
?>
<div class="bar-charts bg-light-blue <?php echo $classes; ?>" style="<?= $custom_padding ?>">
  <div class="wrapper">
    <div class="row">
      <?php foreach($charts as $chart): ?>
        <div class="col col-md-6 col-sm-12 col-xs-12 bar-charts--col will-animate" data-animation="fade-from-bottom">
            <?php echo !empty($chart['title']) ? '<h2 class="bar-charts--col--title">'.$chart['title'].'</h2>' : ''; ?>
            <table>
            <tbody>
              <?php foreach($chart['data'] as $data): ?>
                <tr class="bar-charts--dataset">
                  <?php echo !empty($data['name']) ? '<td><h3 class="bar-charts--dataset--title">'.$data['name'].'</h3></td>' : '<td></td>'; ?>
                  <td width="99%"><div class="bar-charts--dataset--bar" data-width="<?= $data['percent'] ?>"></div></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>