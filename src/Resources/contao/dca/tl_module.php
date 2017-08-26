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

 //Add field "family_jumpTo" to palettes
 $GLOBALS['TL_DCA']['tl_module']['palettes']['login'] = str_replace('redirectBack;','redirectBack,familyJumpTo;',$GLOBALS['TL_DCA']['tl_module']['palettes']['login']);

 //Add field "family_jumpTo"
 $GLOBALS['TL_DCA']['tl_module']['fields']['family_jumpTo'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['family_jumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'foreignKey'              => 'tl_page.title',
	'eval'                    => array('fieldType'=>'radio'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'",
	'relation'                => array('type'=>'hasOne', 'load'=>'eager')
 );
