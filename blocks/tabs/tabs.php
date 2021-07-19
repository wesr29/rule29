<?php 

  /**
   * @param $title - string text
   * @param $tabs[][title] - string text
   * @param $tabs[][content] - string HTML 
   */

  $tabs = array_map(function($tab){
    $tab['slug'] = sanitize_title_with_dashes($tab['title']);
    return $tab;
  }, $tabs);
?>

<div class="tabs">
  <div role="tablist" aria-label="<?php echo !empty($title) ? $title : ''; ?>">
    <?php foreach($tabs as $key => $tab): ?>
      <a 
        role="tab" 
        aria-selected="<?php echo $key == 0 ? 'true' : 'false'; ?>" 
        aria-controls="<?php echo $tab['slug']; ?>-tab" 
        id="<?php echo $tab['slug']; ?>"
        href="javascript:;"
        <?php echo $key !== 0 ? 'tabindex="-1"' : ''; ?>
      >
        <?php echo $tab['title']; ?>
      </a>
    <?php endforeach; ?>
  </div>

  <?php foreach($tabs as $key => $tab): ?>
    <div 
      tabindex="0"
      role="tabpanel"
      id="<?php echo $tab['slug']; ?>-tab"
      aria-labelledby="<?php echo $tab['slug']; ?>"
      <?php echo $key !== 0 ? 'hidden=""' : ''; ?>
      class="editor-content"
    >
      <?php echo $tab['content']; ?>
    </div>
  <?php endforeach; ?>
</div>