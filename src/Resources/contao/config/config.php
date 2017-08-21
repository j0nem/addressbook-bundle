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

$GLOBALS['TL_CSS'][] = 'bundles/jmediaaddressbook/backend.css';

/**
 * Back End Modules
 */
 array_insert($GLOBALS['BE_MOD'],3, array('family' => array(
	 'fm_addressbook' => array(
 		'tables' => array('tl_family')
	 ),
	 'fm_verification' => array(
		 'callback' => 'Jmedia\ModuleFamilyVerification'
	 )
 )));

/**
 * Front End Modules
 */
$GLOBALS['FE_MOD']['family']['fm_list'] = 'Jmedia\ModuleFamilyList';
$GLOBALS['FE_MOD']['family']['fm_edit'] = 'Jmedia\ModuleFamilyEdit';


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['createNewUser'][] = array('Jmedia\FamilyHooks','moveDataAfterRegistration');
$GLOBALS['TL_HOOKS']['activateAccount'][] = array('Jmedia\FamilyHooks','sendVerificationEmail');
