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

	public function sendVerificationEmail(\MemberModel $memberModel, \ModuleRegistration $registrationModule) {
		print_r(\Environment::get('httpHost'));

		$objMail = new \Email();
		$objMail->from = $GLOBALS['TL_CONFIG']['adminEmail'];
		$objMail->fromName = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$objMail->subject = 'Neue Mitglieder-Registrierung bei ' . $GLOBALS['TL_CONFIG']['websiteTitle'];

		$addressEntry = Family::getAddressEntry($memberModel->id);

		$objMail->html = '<h1>'.$addressEntry['firstname'].' '.$addressEntry['lastname'].' hat sich neu registriert</h1>
Der Benutzer hat folgende Daten angegeben:<br /><br />
E-Mail: '.$memberModel->email.'<br />
Geburtstdatum: '.date('d.m.Y',$addressEntry['dateOfBirth']).'<br />
Familienzugehörigkeit: <br />'.$memberModel->about_me.'<br /><br />
<a href="http://'.\Environment::get('httpHost').'/contao?do=fm_verification">Mitglied bestätigen</a>';

		//Send emails to users with activated "notifications" checkbox
		$db = \Database::getInstance();
		$users = $db->query("SELECT email FROM tl_user WHERE family_notifications = '1'");
		while($user = $users->fetchAssoc()) {
			$objMail->sendTo($user['email']);
		}
	}

 }
