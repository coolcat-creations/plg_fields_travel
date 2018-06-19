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

if (!$field->value || $field->value == '-1')
{
	return;
}

/* Map Height */
$mapheight = $field->fieldparams['mapheight'];
$plugin       = JPluginHelper::getPlugin('fields', 'travel');
$pluginparams = json_decode($plugin->params);
$key = $pluginparams->opencageapi;

/* get the document */
$doc = JFactory::getDocument();

/* get the style for the leaflet map */
$doc->addStyleSheet('https://cdn.jsdelivr.net/leaflet/1/leaflet.css');
$doc->addStyleDeclaration('#map {height: ' . $mapheight . 'px; }');


/* get the script for the leaflet map */
$doc->addScript('https://cdn.jsdelivr.net/leaflet/1/leaflet.js');


/* Fill in the gaps in adress to make a proper call in the url */
// $address = str_replace(" ", "%20", $field->value);
$address = JFilterOutput::stringURLSafe($field->rawvalue);

/* setting up the call url */
$url = "https://api.opencagedata.com/geocode/v1/json?q=$address&key=$key&pretty=1";

/* Getting the JSON DATA TO GET LAT & LONG */

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$curl_response = curl_exec($ch);
$jsonobj = json_decode($curl_response);

$lat = $jsonobj->results[0]->geometry->lat;
$lon = $jsonobj->results[0]->geometry->lng;

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
			map = L.map('map').setView([<?php echo $lat . ',' . $lon; ?>], 12);

			L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
				maxZoom: 18
			}).addTo(map);

		});

	})(jQuery);

</script>
