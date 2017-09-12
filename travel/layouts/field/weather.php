<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Travel
 *
 * @copyright   Copyright (C) 2017 Elisa Foltyn.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

ini_set("allow_url_fopen", 1);
if (!key_exists('field', $displayData))
{
	return;
}

$field = $displayData['field'];
$value = $field->rawvalue;
$plugin = JPluginHelper::getPlugin('fields', 'travel');
$pluginparams = json_decode($plugin->params);

/* get the document */
$doc = JFactory::getDocument();

/* load the style for the weather layout */
$doc->addStyleSheet('media/plg_fields_travel/css/weather.css');

/* get the API Key that is set in the global params */
$key = $pluginparams->apixuapi;

/* How many images should be loaded */
$forecast_days = $field->fieldparams['forecastdays'];

/* Fahrenheit or Celcius */
$degrees = $field->fieldparams['degrees'];

/* Dateformat */
$dateformat = $field->fieldparams['dateformat'];

/* Miles or Kilometer */
$wind = $field->fieldparams['wind'];

/* Fill in the gaps in adress to make a proper call in the url */
$address = str_replace(" ", "%20", $field->rawvalue);

/* setting up the call url */
$url = "http://api.apixu.com/v1/forecast.json?key=$key&q=$address&days=$forecast_days&=";

/* Getting the JSON DATA */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$json_output = curl_exec($ch);
$weather     = json_decode($json_output);
$days        = $weather->forecast->forecastday;

/* output the weather layout */

echo '<h3 class="weather-left-menu__header">' . JTEXT::_('PLG_TRAVEL_WEATHER_FORECAST') . $value . '</h3>'; ?>

<ul class="nav nav-tabs" role="tablist">
	<?php $tabCounter = 0;
	foreach ($days as $day)
	{
		?>
        <li class="nav-item">
            <a class="nav-link <?php echo $tabCounter <= 0 ? 'active' : ''; ?>"
               data-toggle="tab"
               href="#tab-<?php echo $day->date; ?>"
               role="tab">
                <div class="weather-date">
					<?php echo date($dateformat, strtotime($day->date)); ?>
                </div>
            </a>
        </li>
		<?php $tabCounter++;
	} ?>
</ul>

<div class="tab-content">

	<?php $contentCounter = 0;
	foreach ($days as $day)
	{ ?>

        <div
                class="tab-pane fade <?php echo $contentCounter <= 0 ? 'show active' : ''; ?>"
                id="tab-<?php echo $day->date; ?>"
                role="tabpanel">


            <div id="weather" class="weather-container">
                <div class="row">
                    <div class="col-6">
                        <div class="weather-left">


                            <div class="weather-condition">

                                <img src="<?php echo $day->day->condition->icon; ?>"
                                     width="64" height="64" alt="Weather in <?php echo $value; ?>"
                                     class="weather-condition">
                                <p><?php echo $day->day->condition->text; ?></p>

                                <span class="weather-temp">
                        <?php echo JTEXT::_('PLG_TRAVEL_WEATHER_AVERAGE'); ?> <?php echo($degrees == 'C' ? $day->day->avgtemp_c . '&deg;C' : $day->day->avgtemp_f . '&deg;F'); ?>
                                    <br>
                    </span>

                                <span class="weather-temp-average">
					<?php echo JTEXT::_('PLG_TRAVEL_WEATHER_BETWEEN'); ?>
					<?php echo($degrees == 'C' ?
						$day->day->mintemp_c . '&deg;C ' . JTEXT::_('PLG_TRAVEL_WEATHER_AND') . ' ' . $day->day->maxtemp_c . '&deg;C'
						: $day->day->mintemp_f . '&deg;F ' . JTEXT::_('PLG_TRAVEL_WEATHER_AND') . ' ' . $day->day->maxtemp_f . '&deg;F'); ?>
                    </span>
                            </div>

                            <div class="weather-astro">
                                <p class="weather-sun">
									<?php echo JTEXT::_('PLG_TRAVEL_WEATHER_SUNRISE'); ?> <?php echo $day->astro->sunrise; ?>
                                    /
									<?php echo JTEXT::_('PLG_TRAVEL_WEATHER_SUNSET'); ?> <?php echo $day->astro->sunset; ?>
                                    /
									<?php echo JTEXT::_('PLG_TRAVEL_WEATHER_WIND'); ?> <?php echo($wind == 'K' ? $day->day->maxwind_kph . 'kpH' : $day->day->maxwind_mph . 'mpH'); ?>
                                </p>
                                <p class="weather-wind">
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="weather-right">
                            <h4><?php echo JTEXT::_('PLG_TRAVEL_WEATHER_HOURS_OVERVIEW'); ?></h4>

                            <div class="hours">
								<?php foreach ($day->hour as $hr)
								{
									?>
                                    <div class="row">
                                        <div class="col-2">
                                            <img src="<?php echo $hr->condition->icon; ?>" width="42" height="42"
                                                 alt="<?php echo $hr->time; ?>">
                                        </div>
                                        <div class="col-10 hourdetails">
                                    <span class="weather-time">
                                    <?php echo JTEXT::_('PLG_TRAVEL_WEATHER_TIME'); ?><?php echo date('H:i', strtotime($hr->time)); ?></span><br>
											<?php echo JTEXT::_('PLG_TRAVEL_WEATHER_TEMPERATURE'); ?> <?php echo($degrees == 'C' ? $hr->temp_c . '&deg;C' : $hr->temp_f . '&deg;F'); ?>
                                            /
											<?php echo JTEXT::_('PLG_TRAVEL_WEATHER_WIND'); ?> <?php echo($wind == 'K' ? $hr->wind_kph . ' kpH' : $hr->wind_mph . ' mpH'); ?>
                                            /
											<?php echo JTEXT::_('PLG_TRAVEL_WEATHER_HUMIDITY'); ?> <?php echo $hr->humidity; ?>
                                            %
                                        </div>

                                    </div>

									<?php
								}

								?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<?php $contentCounter++;
	} ?>

</div>
