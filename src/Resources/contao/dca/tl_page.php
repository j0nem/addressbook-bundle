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

 //Add family_jumpTo to palettes

 $GLOBALS['TL_DCA']['tl_page']['palettes']['root']  = str_replace(';{url_legend}',';{family_legend},family_jumpTo;{url_legend}',$GLOBALS['TL_DCA']['tl_page']['palettes']['root']);
 $GLOBALS['TL_DCA']['tl_page']['palettes']['rootfallback'] = str_replace(';{url_legend}',';{family_legend},family_jumpTo;{url_legend}',$GLOBALS['TL_DCA']['tl_page']['palettes']['rootfallback']);

 //Add field "family_jumpTo"
 $GLOBALS['TL_DCA']['tl_page']['fields']['family_jumpTo'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['family_jumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'foreignKey'              => 'tl_page.title',
	'eval'                    => array('fieldType'=>'radio'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'",
	'relation'                => array('type'=>'hasOne', 'load'=>'eager')
 );
