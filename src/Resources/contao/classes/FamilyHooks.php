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

 namespace Jmedia;
 
 class FamilyHooks extends \System {

	/** 
	 * Move Data from registration form (name, date of birth) from tl_member to tl_family
	 */
	public function moveDataAfterRegistration($intId, $arrData) {
		$db = \Database::getInstance();
		
		//inset new entry into tl_family
		$insSuccess = $db->prepare("INSERT INTO tl_family (tstamp,firstname,lastname,dateOfBirth,account_id) VALUES (?,?,?,?,?)")
									 ->execute(time(),$arrData['firstname'],$arrData['lastname'],$arrData['dateOfBirth'],$intId);
		//delete copied fields from tl_member
		$delSuccess = $db->prepare("UPDATE tl_member SET firstname='',lastname='',dateOfBirth='' WHERE id = ?")->execute($intId);
		
		return $delSuccess && $insSuccess;
	}
	 
	public function sendVerificationEmail(MemberModel $memberModel, ModuleRegistration $registrationModule) {
		return true;
	}
	 
 }
