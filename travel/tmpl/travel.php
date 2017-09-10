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

/* Prepare for plugin display */
JHTML::_('content.prepare', $value);

/* Call the parameter that defines which template should be loaded */
$templates = $field->fieldparams['loadtemplates'];

/* if a template is defined and the automatic display is ON */
if (is_array($templates) || is_object($templates) && $field->fieldparams['display'] == '1')
{
		/* load the templates */
		foreach ($templates as $template)
		{
			$tmplfile = $template . '.php';
			include $tmplfile;
		}
}


