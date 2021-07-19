<?php
  //current author
  $team_members = get_field('team_members');
  if(empty($team_members)){
    return false;
  }

  $team_members = array_map(function($team_member){
    $team_member->permalink = get_the_permalink($team_member);
    $team_member->author_title = get_field('title', $team_member);
    $team_member->mp4 = get_field('cinemagraph_mp4', $team_member);
    $team_member->webm = get_field('cinemagraph_Webm', $team_member);
    $profile_photo = get_field('profile_photo', $team_member);
    $team_member->author_badge = !empty($profile_photo) ? $profile_photo : get_field('default_author_photo', 'option');
    return $team_member;
  }, $team_members);

  //Apply now box, optional
  $apply_box = get_field('apply_box');
?>
<div class="team-members">
  <div class="wrapper">
    <div class="row">
      <?php foreach($team_members as $team_member): ?>
        <div class="col col-md-4 col-sm-6 col-xs-12">
          <a href="<?php echo $team_member->permalink; ?>" class="team-member relative">
            <?php echo wp_image($team_member->author_badge, 'large', ['class' => 'team-member--photo ' . (empty($team_member->mp4) && empty($team_member->webm) ? 'no-video' : '') ]); ?>
            
            <?php if(!empty($team_member->mp4) || !empty($team_member->webm)): ?>
              <video autoplay loop muted>
                <?php echo !empty($team_member->mp4) ? '<source src="'.$team_member->mp4.'" type="video/mp4">' : ''; ?>
                <?php echo !empty($team_member->webm) ? '<source src="'.$team_member->webm.'" type="video/webm">' : ''; ?>
              </video>
            <?php endif; ?>

            <div class="team-member--content text-center text-white">
              <h2 class="team-member--name"><?php echo $team_member->post_title; ?></h2>
              <?php echo !empty($team_member->author_title) ? '<p class="team-member--title">'.$team_member->author_title.'</p>' : ''; ?>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
      <?php if(!empty($apply_box['title']) || !empty($apply_box['content']) ||!empty($apply_box['button'])): ?>
        <div class="team-members--apply-box col col-md-4 col-sm-6 col-xs-12">
          <div class="team-members--apply-box--inner text-center text-white bg-dark-blue morph-icon--hover">
            <div>
              <div class="morph-icon--container"><?php block('morph-icon', [ 'icon' => $apply_box['icon'] ]); ?></div>
              <?php echo !empty($apply_box['title']) ? '<h2 class="team-members--apply-box--title">'.$apply_box['title'].'</h2>' : ''; ?>
              <div class="editor-content">
                <?php echo !empty($apply_box['content']) ? '<p class="team-members--apply-box--content">'.$apply_box['content'].'</p>' : ''; ?>
                <?php echo !empty($apply_box['button']) ? acf_link($apply_box['button'], 'button button--white button--outline') : ''; ?>
              </div>
            </div>
          </div>          
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>