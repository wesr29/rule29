<?php
/**
 * Builds accordion Module 
 * @param $accordion
 * @param $accordions[]['title']
 * @param $accordions[]['content']
*/

// assuming array
// $accordion = [
// 	['title' => 'Rob', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti molestiae, fugiat rem quis atque saepe ad. Earum magnam veniam, molestiae maxime praesentium cum beatae iste culpa adipisci quibusdam assumenda cupiditate.'],
// 	['title' => 'Andrew', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti molestiae, fugiat rem quis atque saepe ad. Earum magnam veniam, molestiae maxime praesentium cum beatae iste culpa adipisci quibusdam assumenda cupiditate.'],
// 	['title' => 'Brittany', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti molestiae, fugiat rem quis atque saepe ad. Earum magnam veniam, molestiae maxime praesentium cum beatae iste culpa adipisci quibusdam assumenda cupiditate.'],
// ];


if(empty($accordion)) {
    return false;
}

$accordion = array_map(function($section) {
	$section['id'] = 'accordion-'.sanitize_title_with_dashes($section['title']).'-'.uniqid();
	return $section;
}, $accordion);

?>

<section class="accordion">
	<div class="wrapper">
	<?php foreach ($accordion as $key => $section): ?>
		<div class="accordion--section">
			<a href="javascript:;" aria-expanded="false" aria-controls="#<?php echo $section['id']; ?>" class="accordion--title">
				<?php echo $section['title'] ?>
			</a>
			<div style="display: none;" id="<?php echo $section['id']; ?>" class="accordion--content content-editor">
				<?php echo $section['content'] ?>
			</div>
		</div>
	<?php endforeach ?>
	</div>
</section>