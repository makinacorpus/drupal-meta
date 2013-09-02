<?php
/*
 * Example of mapping
 */
array(
  "og" => array(                  // Use "og" identified plugin
    array(
      'name'  => 'og:title',      // Meta property name
      'input' => 'default:title', // Input class key and name identifier
    ),
    array(
      "name"  => "og:image", 
      "input" => "imagefield:field_image1",
    ),
    array(
      "name"  => "og:image",      // Items can be duplicated 
      "input" => "imagefield:field_image2",
    ),
  ),
  "html" => array(              // Use "html" plugin
    array(
      "name"  => "description",
      "input" => "textfield:body",
    ),
  ),
);
// Output will be driven by plugin itself
