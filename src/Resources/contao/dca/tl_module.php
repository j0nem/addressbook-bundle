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

 //Add custom palettes for fm_list
 $GLOBALS['TL_DCA']['tl_module']['palettes']['fm_list'] = '{title_legend},name,type;{jumpTo_legend},family_editJumpTo,family_suggestJumpTo';

 //Add jumpTo fields
 $GLOBALS['TL_DCA']['tl_module']['fields']['family_editJumpTo'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['family_editJumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'foreignKey'              => 'tl_page.title',
	'eval'                    => array('fieldType'=>'radio'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'",
	'relation'                => array('type'=>'hasOne', 'load'=>'eager')
 );

 $GLOBALS['TL_DCA']['tl_module']['fields']['family_suggestJumpTo'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['family_suggestJumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'foreignKey'              => 'tl_page.title',
	'eval'                    => array('fieldType'=>'radio'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'",
	'relation'                => array('type'=>'hasOne', 'load'=>'eager')
);
