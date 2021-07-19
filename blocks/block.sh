#!/usr/bin/env bash

makeBlock() {
    read -r -p "Name of Block: " block
    mkdir "$block"
    touch "$block"/"$block".php
    echo "<?php
/**
 * Builds "$block" Module 
 * @param 
*/

if(empty($"$block")) {
    return false;
}
?>

<section class=\""$block"\">
</section>
" >> "$block"/"$block".php

    touch "$block"/"$block".sass
    echo ".$block" >> "$block"/"$block".sass
    touch "$block"/"$block".js
    echo "~($ => {
  function init(){
   // console.log('ready')
  }
  \$(document).ready(init)

})(jQuery)" >> "$block"/"$block".js

}

makeBlock
