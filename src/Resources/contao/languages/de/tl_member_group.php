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

//TODO: Add check to avoid multiple "verified" and "unverified" groups

$GLOBALS['TL_LANG']['tl_member_group']['family_legend'] = 'Einstellungen für Familienadressbuch';
$GLOBALS['TL_LANG']['tl_member_group']['family_group_type'] = array('Gruppenfunktion','Bitte geben Sie an, wenn nötig, ob diese Gruppe verifizierte oder nicht-verifizierte Mitglieder repräsentiert.');
$GLOBALS['TL_LANG']['tl_member_group']['family_group_type_options'] = array(
	'verified' 		=> 'Verifizierte Mitglieder',
	'unverified' 	=> 'Nicht-verifiziete Mitglieder'
);
