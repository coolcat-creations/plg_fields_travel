<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Travel
 *
 * @copyright   Copyright (C) 2017 Elisa Foltyn.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('text');


class JFormFieldTravel extends JFormFieldText {

	protected $type = 'Travel';


	public function setup (SimpleXMLElement $element, $value, $group = null)
	{

		$doc = JFactory::getDocument();
		$doc->addScript('https://cdn.jsdelivr.net/npm/places.js@1.4.15', array(), array('defer'=> 'defer' ));
		$doc->addScriptDeclaration('document.addEventListener("DOMContentLoaded", function(event) {
							var placesAutocomplete = places({
								container: document.querySelector(".address-input")
							});
							
							placesAutocomplete.on("change", e => console.log(e.suggestion));

						});');

		$element['class'] = 'address-input';
		$element['hint'] = 'PLG_FIELDS_TRAVEL_DESTINATION';


		$return = parent::setup($element, $value, $group);
		return $return;
	}

}

