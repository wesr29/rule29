# iziTheme

This is a theme meant to be expanded upon while being super easy to use. 

## Todo/Improvements
- Give iziSlider the ability to fade instead of slide

## File Structure
- `acf-json/` Holds generated ACF JSON files
- `blocks/` Create reusable blocks with all the sass/php/js in one place
- `includes/` PHP includes and extra functions. Any new files here will automatically be included via functions.php
-- `vendor/` Vendor files from composer
-- `_base.php` Powers the base theme, you probably don't need to go in here
-- `_helper-functions.php` Very helpful functions, use these all the time
-- `ajax-functions.php` AJAX functions for WordPress
-- `composer.json` Tells composer what php plugins we need in `vender`
-- `shortcodes.php` Add custom WordPress shortcodes
-- `theme-functions.php` This is where you add on custom functions specific to this theme
- `public/` Generated CSS/JS and images compile to here
- `resources/` This is where the raw/precompiled JS/CSS and images live
-- `images/` Images needed for the theme
-- `js/` Global JS files
---- `includes/` Custom JS plugins that can be required in main or blocks
---- `main.js` The main JS file, duh
-- `node_modules/` Node modules needed for compiling resources (these don't matter they are all generated)
-- `sass/` Raw sass files
---- `global/` Sass that affects the entire site
---- `editor-style.sass` Admin WYSIWYG styles
---- `style.sass` Main site sass file
- Everything else in the main directory is standard WordPress stuff

## Resources
-------------------
Resources are the theme images, css, and js files. They are all minified and injected into the theme automagically. 

First get into the resources directory
`cd izi-theme/resources`
Install dependencies
`npm install`
Start watching files to compile for development
`npm run dev`
Or compile everything for production
`npm run prod`

#### CSS
The file that compiles everything is `resources/sass/style.sass`
All sass files from the blocks directory is automagically appended to this when compiling
Any global CSS can go into partials under `resources/sass/globals/` and imported into the `style.sass`
#### JS
The file that compiles everything is `resources/js/main.js`
All js files from the blocks directory is automagically appended to this when compiling
Any global JS can go into this file, custom js plugins and stuff like that can go into `resources/js/includes/` because any js file in the `/js/` directory is going to get compiled into `public/`
#### Images
Theme images should go into `resources/images/`
Any images here will be optimized and transformed into nextgen images when possible. You should use the helper function `theme_image($name)` to render them.

## Blocks
-------------------
Each block can have up to 3 files: one for php, js, and sass. These are all compiled automatically with the resources scripts