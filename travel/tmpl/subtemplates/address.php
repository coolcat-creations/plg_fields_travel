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

$address = $field->rawvalue;

echo $address;
