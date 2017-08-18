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
 * Namespace
 */
namespace Jmedia;

class ModuleFamilyVerification extends \BackendModule {

	protected $strTemplate = 'be_verification';

	protected $intVerifiedGroup = 0;
	protected $intUnverifiedGroup = 0;

	/*
	* Renders the backend module
	*/
    protected function compile() {

		//get group IDs and check if setup is complete
		$this->getGroupIds();
		$this->Template->arrStatusMessages = $this->checkSetup();

		//apply changes on POST request
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if($this->Input->post('METHOD') == 'verify_member') {
				$this->verifyMember($this->Input->post('id'));
				$this->sendInfoMail($this->Input->post('id'));
			}
		}

		//fetch unverified member entries
		$arrMemberEntries = $this->fetchMemberEntries();

		//fetch unreviewed addressbook updates
		$arrBookUpdates = $this->fetchBookUpdates();

		//prepare template data
		$this->Template->memberEntries = $arrMemberEntries;
		$this->Template->bookUpdates = $arrBookUpdates;
	}

	/*
	* Get group IDs for unverified and verified members
	*
	* @var $intVerifiedGroup
	* @var $intUnverifiedGroup
	* @return void
	*/
	protected function getGroupIds() {
		$data = $this->Database->query("SELECT id, family_group_type FROM tl_member_group");
		while($row = $data->fetchAssoc()) {
			if($row['family_group_type'] == 'verified') {
				$this->intVerifiedGroup = $row['id'];
			}
			elseif($row['family_group_type'] == 'unverified') {
				$this->intUnverifiedGroup = $row['id'];
			}
		}
	}

	/*
	* Check if there is a "verified" and an "unverified" group
	*
	* @return array
	*/
	protected function checkSetup() {
		$arrMessages = array();
		if($this->intVerifiedGroup == 0) {
			$arrMessages['no_verified_group'] = true;
		}
		if($this->intUnverifiedGroup == 0) {
			$arrMessages['no_unverified_group'] = true;
		}
		return $arrMessages;
	}

	/*
	* Adjust groups of member to verify the member
	*
	* @param int $intId
	* @return void
	*/
	protected function verifyMember($intId) {
		//get current groups of member
		$data = $this->Database->prepare("SELECT groups FROM tl_member WHERE id = ?")->execute($intId);
		$arrData = $data->fetchAssoc();

		//put current group IDs of the user in array
		if($arrData) {
			$arrGroups = unserialize($arrData['groups']);
		}
		else  {
			$arrGroups = array();
		}
		//add group "verified", unset group "unverified" and save groups
		if($arrData) {
			$arrGroups[] = $this->intVerifiedGroup;
			unset($arrGroups[array_search($this->intUnverifiedGroup,$arrGroups)]);
			$this->Database->prepare("UPDATE tl_member SET groups = ? WHERE id = ?")->execute(serialize($arrGroups), $intId);
		}
	}

	protected function sendInfoMail($intId) {
		$objMail = new \Email();
		$objMail->from = $GLOBALS['TL_CONFIG']['adminEmail'];
		$objMail->fromName = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$objMail->subject = 'Ihr Account wurde verifiziert';

		$user = $this->Database->prepare("SELECT m.email,f.firstname,f.lastname FROM tl_member m JOIN tl_family f ON f.account_id = m.id WHERE m.id = ?")->execute($intId);
		$arrUser = $user->fetchAssoc();

		$objMail->html = '<p>Hallo '.$arrUser['firstname'].' '.$arrUser['lastname'].',</p>
<p>Ihr Account bei '.$GLOBALS['TL_CONFIG']['websiteTitle'].' wurde erfolgreich von einem Administrator verifiziert.<br />
Ihr Adressbucheintrag ist jetzt für alle Mitglieder auf der Website sichtbar. Gerne können Sie sich einloggen und beim Aufbau des Adressbuchs mithelfen:</p>
<p><a href="http://'.\Environment::get('httpHost').'">Zum Login</a></p><p>Vielen Dank!</p>';

		$objMail->sendTo($arrUser['email']);
	}

	/*
	* Fetch Unverified member entries from Database
	*
	* @var $intUnverifiedGroup
	* @var $intVerifiedGroup
	* @return array
	*/
	protected function fetchMemberEntries() {
		$arrMemberEntries = array();
		$memberEntries = $this->Database->query("SELECT m.email,m.id,m.about_me,m.tstamp,m.groups,f.firstname,f.lastname FROM tl_member m JOIN tl_family f ON f.account_id = m.id");
		while($arrMemberEntry = $memberEntries->fetchAssoc()) {
			if($arrMemberEntry['groups']) {
				$arrMemberGroups = unserialize($arrMemberEntry['groups']);

				if(!in_array($this->intVerifiedGroup, $arrMemberGroups) && in_array($this->intUnverifiedGroup, $arrMemberGroups))
				{
					$arrMemberEntries[$arrMemberEntry['id']] = $arrMemberEntry;
				}
			}
		}
		return $arrMemberEntries;
	}

	/*
	* Fetch addressbook updates
	*
	* @return array
	*/
	protected function fetchBookUpdates() {
		$bookUpdates = $this->Database->query("SELECT * FROM tl_family_update WHERE reviewed = ''");
		while($arrBookUpdate = $bookUpdates->fetchAssoc()) {
			$arrBookUpdates[$arrBookUpdate['id']] = $arrBookUpdate;
		}
		return $arrBookUpdates;
	}
}
