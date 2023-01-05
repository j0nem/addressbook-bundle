<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

/**
 * Jmedia/AddressbookBundle
 *
 * @author Johannes Cram <johannes@jonesmedia.de>
 * @package AddressbookBundle
 * @license GPL-3.0+
 */

 //Add field "family notification" to palettes
 $GLOBALS['TL_DCA']['tl_user']['palettes']['admin'] = str_replace('admin;','admin,family_notifications;',$GLOBALS['TL_DCA']['tl_user']['palettes']['admin']);

 //Add field "family notifications" to fields
 $GLOBALS['TL_DCA']['tl_user']['fields']['family_notifications'] = array(
	'label'         => &$GLOBALS['TL_LANG']['tl_user']['family_notifications'],
	'exclude'		=> true,
	'inputType'		=> 'checkbox',
	'sql'			=> "char(1) NOT NULL default ''"
 );
