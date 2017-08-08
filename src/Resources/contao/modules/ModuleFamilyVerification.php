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

    protected function compile() {

		//apply changes on POST request
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if($this->Input->post('METHOD') == 'verify_member') {

				//get current groups of member
				$data = $this->Database->prepare("SELECT groups FROM tl_member WHERE id = ?")->execute($this->Input->post('id'));
				while($row = $data->fetchAssoc()) {
					$arrData = $row;
				}
				if($arrData) {
					$arrGroups = unserialize($arrData['groups']);
				}
				else  {
					$arrGroups = array();
				}

				//get group id of group "verified" and add member to group
				$data = $this->Database->prepare("SELECT id FROM tl_member_group WHERE family_group_type = ?")->execute('verified');
				while($row = $data->fetchAssoc()) {
					$intGroupId = $row['id'];
				}
				if($arrData) {
					$arrGroups[] = $intGroupId;
					$this->Database->prepare("UPDATE tl_member SET groups = ? WHERE id = ?")->execute(serialize($arrGroups), $this->Input->post('id'));
				}
			}
		}

		//fetch unverified member entries
		$memberEntries = $this->Database->query("SELECT m.email,m.id,m.about_me,m.tstamp,m.groups,f.firstname,f.lastname FROM tl_member m JOIN tl_family f ON f.account_id = m.id");
		while($arrMemberEntry = $memberEntries->fetchAssoc()) {
			if($arrMemberEntry['groups']) {

				//TODO: Add selector for group "verified" in backend
				if(!in_array(3, unserialize($arrMemberEntry['groups']))) //if not in member group "verified"
				{
					$arrMemberEntries[$arrMemberEntry['id']] = $arrMemberEntry;
				}
			}
			else $arrMemberEntries[$arrMemberEntry['id']] = $arrMemberEntry;
		}

		//fetch unverified addressbook updates
		$bookUpdates = $this->Database->query("SELECT * FROM tl_family_update WHERE reviewed = ''");
		while($arrBookUpdate = $bookUpdates->fetchAssoc()) {
			$arrBookUpdates[$arrBookUpdate['id']] = $arrBookUpdate;
		}

		$this->Template->memberEntries = $arrMemberEntries;
		$this->Template->bookUpdates = $arrBookUpdates;
    }
}
