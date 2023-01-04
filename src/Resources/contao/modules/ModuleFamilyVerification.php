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

use Contao\CoreBundle\Monolog\ContaoContext;
use Psr\Log\LogLevel;

class ModuleFamilyVerification extends \BackendModule {

	protected $strTemplate = 'be_verification';

	protected $intVerifiedGroup = 0;
	protected $intUnverifiedGroup = 0;

	/**
	* Renders the backend module
	*/
    protected function compile() {

		//get group IDs and check if setup is complete
		$this->getGroupIds();
		$this->Template->arrStatusMessages = $this->checkSetup();

		//apply changes on POST request
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(\Input::post('METHOD') == 'verify_member') {
				$this->verifyMember(\Input::post('id'));
				$this->sendInfoMail(\Input::post('id'));
			}
			if(\Input::post('METHOD') == 'publish_new_entry') {
				$this->publishNewEntry(\Input::post('id'));
			}
		}

		//prepare template data
		$this->Template->memberEntries = $this->fetchMemberEntries();
		$this->Template->bookNew = $this->fetchBookNew();
		$this->Template->bookUpdates = $this->fetchBookUpdates();
	}

	/**
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

	/**
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

	/**
	* Adjust groups of member to verify the member
	*
	* @param int $intId
	* @return void
	*/
	protected function verifyMember($intId) {
		//get current groups of member
		$data = $this->Database->prepare("SELECT id,email,groups FROM tl_member WHERE id = ?")->execute($intId);
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
			$this->Database->prepare("UPDATE tl_member SET groups = ?,tstamp = ? WHERE id = ?")->execute(serialize($arrGroups),time(),$intId);
		}

		//create new version of tl_member
		$objVersion = new \Versions('tl_member',$intId);
		$objVersion->create();

		//publish addressbook entry
		$this->Database->prepare("UPDATE tl_family SET visible = '1',tstamp = ? WHERE account_id = ?")->execute(time(),$intId);

		//create new version of tl_family
		$arrRecord = $this->Database->prepare("SELECT id FROM tl_family WHERE account_id = ?")->execute($intId)->fetchAssoc();
		$objVersion = new \Versions('tl_family',$arrRecord['id']);
		$objVersion->create();

		//log verification
		$logger = \System::getContainer()->get('monolog.logger.contao');
		$strText = 'Family Member with Member ID '.$arrData['id'].' ('.$arrData['email'].') has been verified';
		$logger->log(LogLevel::INFO,$strText,array('contao' => new ContaoContext(__METHOD__, 'ACCESS')));
	}

	protected function sendInfoMail($intId) {
		$objMail = new \Email();
		$objMail->from = \Config::get('adminEmail');
		$objMail->fromName = \Config::get('websiteTitle');
		$objMail->subject = 'Ihr Account wurde verifiziert';

		$user = $this->Database->prepare("SELECT m.email,f.firstname,f.lastname FROM tl_member m JOIN tl_family f ON f.account_id = m.id WHERE m.id = ?")->execute($intId);
		$arrUser = $user->fetchAssoc();

		$objMail->html = '<p>Hallo '.$arrUser['firstname'].' '.$arrUser['lastname'].',</p>
<p>Ihr Account bei '.\Config::get('websiteTitle').' wurde erfolgreich von einem Administrator verifiziert.<br />
Ihr Adressbucheintrag ist jetzt für alle Mitglieder auf der Website sichtbar. Gerne können Sie sich einloggen und beim Aufbau des Adressbuchs mithelfen:</p>
<p><a href="http://'.\Environment::get('httpHost').'">Zum Login</a></p><p>Vielen Dank!</p>';

		$objMail->sendTo($arrUser['email']);
	}

	/**
	* Publish an addressbok entry
	*
	* @param int $intId
	* @return void
	*/
	protected function publishNewEntry($intId) {
		//update tl_family
		$this->Database->prepare("UPDATE tl_family SET visible = 1,tstamp = ? WHERE id = ?")->execute(time(),$intId);

		//create new version of tl_family
		$objVersion = new \Versions('tl_family',$intId);
		$objVersion->create();
	}

	/**
	* Fetch Unverified member entries from Database
	*
	* @var $intUnverifiedGroup
	* @var $intVerifiedGroup
	* @return array
	*/
	protected function fetchMemberEntries() {
		$arrMemberEntries = array();
		$memberEntries = $this->Database->query("SELECT m.email,m.id,m.about_me,m.tstamp,m.groups,m.disable,f.firstname,f.lastname FROM tl_member m JOIN tl_family f ON f.account_id = m.id");
		while($arrMemberEntry = $memberEntries->fetchAssoc()) {
			if($arrMemberEntry['groups']) {
				$arrMemberGroups = unserialize($arrMemberEntry['groups']);

				if(!in_array($this->intVerifiedGroup, $arrMemberGroups)
					&& in_array($this->intUnverifiedGroup, $arrMemberGroups)
					&& $arrMemberEntry['disable'] != 1
				)
				{
					$arrMemberEntries[$arrMemberEntry['id']] = $arrMemberEntry;
				}
			}
		}
		return $arrMemberEntries;
	}

	/**
	* Fetch new unverified addressbook entries
	*
	* @return array
	*/
	protected function fetchBookNew() {
		$arrBookEntries = [];
		$bookEntries = $this->Database->query("SELECT * FROM tl_family WHERE visible != 1 AND account_id = 0");
		while($arrBookEntry = $bookEntries->fetchAssoc()) {
			$arrBookEntry['strDateOfBirth'] = $arrBookEntry['dateOfBirth'] ? date('m.d.Y',$arrBookEntry['dateOfBirth']) : '';
			$arrBookEntries[] = $arrBookEntry;
		}
		return $arrBookEntries;
	}

	/**
	* Fetch addressbook updates
	*
	* @return array
	*/
	protected function fetchBookUpdates() {
		$bookUpdates = $this->Database->query("SELECT * FROM tl_family_update WHERE reviewed = ''");
		$arrBookUpdates = [];
		while($arrBookUpdate = $bookUpdates->fetchAssoc()) {
			$arrBookUpdates[$arrBookUpdate['id']] = $arrBookUpdate;
		}
		return $arrBookUpdates;
	}
}
