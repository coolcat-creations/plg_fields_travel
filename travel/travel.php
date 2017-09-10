<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Travel
 *
 * @copyright   Copyright (C) 2017 Elisa Foltyn.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::import('components.com_fields.libraries.fieldsplugin', JPATH_ADMINISTRATOR);


class PlgFieldsTravel extends FieldsPlugin
{

	public function onCustomFieldsPrepareDom($field, DOMElement $parent, JForm $form) {


		$FieldNode = parent::onCustomFieldsPrepareDom($field, $parent, $form);

		if (!$FieldNode) {
			return $FieldNode;
		}

		return $FieldNode;
	}


}
