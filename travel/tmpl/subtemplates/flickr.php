<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Travel
 *
 * @copyright   Copyright (C) 2017 Elisa Foltyn.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$value = $field->rawvalue;

if (!$field->value || $field->value == '-1') {
	return;
}

/*making sure jQuery is loaded first */
JHtml::_('jQuery.Framework');

/* get the document */
$doc = JFactory::getDocument();

/* load the style for the masonry effect */
$doc->addStyleSheet('media/plg_fields_travel/css/masonry.css');

/* load the style for lightbox */
$doc->addStyleSheet('media/plg_fields_travel/css/magnific-popup.css');

/* load the script for the masonry effect */
$doc->addScript('media/plg_fields_travel/js/masonry.pkgd.min.js',array(),array('async'=>'async'));

/* load the script for the lightbox */
$doc->addScript('media/plg_fields_travel/js/jquery.magnific-popup.js');

/* load the imageloaded script for the masonry effect */
$doc->addScript('media/plg_fields_travel/js/imagesloaded.js',array(),array('async'=>'async'));


/* Fill in the gaps in adress to make a proper call in the url */
$address = JFilterOutput::stringURLSafe($field->value);

/* How many images should be loaded */
$imgnum = $field->fieldparams['imgnum'];


/* get the API Key that is set in the global params */
$key = $this->params['flickrapi'];

if (!$key)
{
	JFactory::getApplication()->enqueueMessage(JText::_('PLG_TRAVEL_DEFINE_FLICKRKEY'), 'error');
	return;
}

/* Find out the place id */
$placeurl = "https://api.flickr.com/services/rest/?method=flickr.places.find&api_key=$key&query=$address&format=json&nojsoncallback=1";

/* Getting the JSON DATA for Place */
$placecall = curl_init();
curl_setopt($placecall,CURLOPT_URL,$placeurl);
curl_setopt($placecall,CURLOPT_RETURNTRANSFER,true);

$json_output_place = curl_exec($placecall);

$place = json_decode($json_output_place);

$place_id = $place->places->place[0]->place_id;

/* setting up the call url */
$url = "https://api.flickr.com/services/rest/?format=json&api_key=$key&method=flickr.photos.search&sort=interestingness-desc&nojsoncallback=1&per_page=$imgnum&media=photos&place_id=$place_id&accuracy=10&content_type=1&extras=url_l,tags,owner_name,description";

/* Getting the JSON DATA */
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

$json_output=curl_exec($ch);

$images = json_decode($json_output);

$images = $images->photos->photo;

?>

<?php /* output the flickr images in masonry gallery grid */ ?>
<div class="masonry">

	<?php /* We need this to define the gridsize (see .css file) */ ?>
    <div class="grid-sizer"></div>

	<?php /* We need this to define the guttersize (see .css file) */ ?>
    <div class="gutter-sizer"></div>

	<?php

    foreach ($images as $image){

	echo '<div class="masonry-item">';
	echo '<a href="' . $image->url_l . '" class="travelimage">';
	echo '<img src="' . 'http://farm' . $image->farm . '.static.flickr.com/' . $image->server . '/' . $image->id . '_' . $image->secret . '_z.jpg">';
	echo '</a>';
	echo '<span class="attribution">' . $image->ownername .'</span>';
	echo '</div>';

}

?>
    <div style="clear:both"></div>
</div>


<?php /* We add the script at the end */ ?>

<script>
jQuery.noConflict();
jQuery( document ).ready(function($) {

	var grid = $('.masonry').imagesLoaded( function() {
		// init Masonry after all images have loaded
		grid.masonry({
			itemSelector: '.masonry-item',
			columnWidth: '.grid-sizer',
			gutter: '.gutter-sizer'

		});

	});

	$('.travelimage').magnificPopup({type:'image'});

});

</script>


