<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Uikitgallery
 *
 * @copyright   Copyright (C) 2017 JUGN.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (!key_exists('field', $displayData))
{
	return;
}

$field = $displayData['field'];
$value = $field->rawvalue;

/* Map Height */
$mapheight = $field->fieldparams['mapheight'];

/* get the document */
$doc = JFactory::getDocument();

/* get the style for the leaflet map */

/*making sure jQuery is loaded first */
JHtml::_('jQuery.Framework');

$doc->addStyleSheet('https://cdn.jsdelivr.net/leaflet/1/leaflet.css');
$doc->addStyleDeclaration('#map {height: ' . $mapheight . 'px; }');


/* get the script for the leaflet map */
$doc->addScript('https://cdn.jsdelivr.net/leaflet/1/leaflet.js');


/* Fill in the gaps in adress to make a proper call in the url */
$address = str_replace(" ", "%20", $field->rawvalue);

/* setting up the call url */
$url = "http://nominatim.openstreetmap.org/search/$address?format=json&bounded=1";

/* Getting the JSON DATA TO GET LAT & LONG */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$json_output = curl_exec($ch);
$geo         = json_decode($json_output);
?>

<?php /* Building the mapcontainer */ ?>
<div id="map">

</div>


<?php /* We add the script at the end - We get the first latitute and longitute entry from the list */ ?>

<script>

	(function ($) {

		var map;
		$(function () {
			// Initialize the map
			// This variable map is inside the scope of the jQuery function.

			// Now map reference the global map declared in the first line
			map = L.map('map').setView([<?php echo $geo[0]->lat . ',' . $geo[0]->lon; ?>], 12);

			L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
				maxZoom: 18
			}).addTo(map);

		});

	})(jQuery);

</script>
