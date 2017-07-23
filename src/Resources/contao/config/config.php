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

/**
 * Back End Modules
 */
 $GLOBALS['BE_MOD']['accounts']['fm_list'] = array(
 	'tables' => array('tl_family')
 );
 $GLOBALS['BE_MOD']['accounts']['fm_verification'] = array(
 	'callback' => 'Jmedia\ModuleFamilyVerification'
 );

/**
 * Front End Modules
 */
$GLOBALS['FE_MOD']['family']['fm_list'] = 'ModuleFamilyList';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['createNewUser'][] = array('FamilyHooks','moveDataAfterRegistration');
$GLOBALS['TL_HOOKS']['activateAccount'][] = array('FamilyHooks','sendVerificationEmail');
