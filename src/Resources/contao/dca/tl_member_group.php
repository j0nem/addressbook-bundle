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

 //Add "group type" field to palettes
 $GLOBALS['TL_DCA']['tl_member_group']['palettes']['default'] = str_replace('{account_legend}','{family_legend},family_group_type;{account_legend}',$GLOBALS['TL_DCA']['tl_member_group']['palettes']['default']);

 //Add "group type" select field
 $GLOBALS['TL_DCA']['tl_member_group']['fields']['family_group_type'] = array(
	'label'         => &$GLOBALS['TL_LANG']['tl_member_group']['family_group_type'],
	'exclude'		=> true,
	'inputType'		=> 'select',
	'options'		=> array('verified','unverified'),
	'reference'		=> &$GLOBALS['TL_LANG']['tl_member_group']['family_group_type_options'],
	'eval'			=> array('includeBlankOption' => true, 'unique'=>true, 'tl_class'=>'w50','feEditable'=>true, 'feViewable'=>true),
	'sql'			=> "varchar(255) NOT NULL default ''"
);
