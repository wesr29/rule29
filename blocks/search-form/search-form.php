<div class="search-form wrapper flex"> 
  <button type="button" class="search-form--toggle flex align-center text-white">
    <div class="search-form--toggle--open flex align-center">
      <span class="search-form--search-icon"><?= render_svg('search') ?></span>
      <strong>Search</strong>
    </div>
    <div class="search-form--toggle--close flex align-center">
      <?= render_svg('white-x-2') ?>   <strong class="m-l-10">Close</strong>
    </div>
  </button>

  <form aria-hidden="true" class="bg-medium-blue" method="get" action="<?php echo home_url(); ?>">
    <div class="wrapper">
      <input type="search" name="s" placeholder="Search...">
      <button type="submit">Search</button>
    </div>
  </form>
</div>