<?php

use Meta\Output\Structure;

/*
 * Arrays
 * ------
 *
 *    array(
 *        "foo" => array(1, 2, 3),
 *    ),
 *
 * Will give you:
 *
 *    <meta name="foo" content="1">
 *    <meta name="toto" content="2">
 *    <meta name="toto" content="3">
 *
 * Structure
 * ---------
 * 
 *   array(
 *      "foo" => array(
 *          "bar" => "baz",
 *      ),
 *   ),
 *
 * While give you:
 *
 *    <meta name="foo:bar" content="baz">
 *
 * Mixing structure and arrays
 * ---------------------------
 *
 *     'image' => array(
 *         array(
 *             'http://perdu.com/here.jpg',
 *             array(
 *                 'height' => 12,
 *                 'width' => 123,
 *             ),
 *        ),
 *        'http://perdu.com/there.jpg',
 *     ),
 *
 * Will give you an array of "image" structures:
 *
 *      <meta name="image" content="http://perdu.com/here.jpg">
 *      <meta name="image:height" content="12">
 *      <meta name="image:width" content="123">
 *      <meta name="image" content="http://perdu.com/there.jpg">
 */

$data = array(
    'toto' => array(
        1,
        2,
        3,
    ),
    'foo' => array(
        'bar' => 'baz',
    ),
    'image' => array(
        array(
            'http://perdu.com/here.jpg',
            array(
                'height' => 12,
                'width' => 123,
            ),
        ),
        'http://perdu.com/there.jpg',
    ),
);

$output = new Structure();
$nodes  = $output->buildNode(null, array($data));

// This will print the given array
$data = '';
foreach ($nodes as $node) {
  $data .= (string)$node;
}
