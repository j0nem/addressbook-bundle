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

class ModuleFamilySetup extends \BackendModule {

	protected $strTemplate = 'be_fmsetup';

	/**
	* Renders the backend module
	*/
	protected function compile(){
		$this->import('Database');

		//check member group setup
		$this->Template->groupSetup = $this->getGroupIds();

		//check if modules are correctly installed
		$this->Template->moduleSetup = $this->getModuleConfig();

		//check if admin notification is set up
		$this->Template->adminSetup = $this->getNotificationSetup();

		//check if redirection page after first login is set
		$this->Template->redirectionSetup = $this->getRedirectionSetup();
	}

	/**
	* Check if there is a group "verified" and "unverified"
	*
	* @return array
	*/
	protected function getGroupIds() {
		$data = $this->Database->query("SELECT id, family_group_type FROM tl_member_group");
		while($row = $data->fetchAssoc()) {
			if($row['family_group_type'] == 'verified') {
				$arrGroups['verified'] = $row;
			}
			elseif($row['family_group_type'] == 'unverified') {
				$arrGroups['unverified'] = $row;
			}
		}
		return $arrGroups;
	}

	/**
	* Check if nesessary frontend modules are included in layout and content elements
	*
	* @return array
	*/
	protected function getModuleConfig() {
		$arrRes = [];
		$moduleData = $this->Database->query("SELECT * FROM tl_module");
		$contentData = $this->Database->query("SELECT * FROM tl_content");
		while($arrMod = $moduleData->fetchAssoc()) {
			if($arrMod['type'] == 'fm_list') {
				$arrRes['fm_list']['module'] = $arrMod;
				$arrIds['fm_list'] = $arrMod['id'];
			}
			elseif($arrMod['type'] == 'fm_edit') {
				$arrRes['fm_edit']['module'] = $arrMod;
				$arrIds['fm_edit'] = $arrMod['id'];
			}
		}
		while($arrCnt = $contentData->fetchAssoc()) {
			if($arrCnt['type'] = 'module' && in_array($arrCnt['module'],$arrIds)) {
				$strType = array_search($arrCnt['module'],$arrIds);
				$arrRes[$strType]['content'] = $arrCnt;
			}
		}
		return $arrRes;
	}

	/**
	* Check if notification is set up for backend users
	*
	* @return array
	*/
	protected function getNotificationSetup() {
		$arrRes = [];
		$users = $this->Database->query("SELECT id,email,family_notifications FROM tl_user");
		while($arrUser = $users->fetchAssoc()) {
			if($arrUser['family_notifications'] == 1) {
				$arrRes[$arrUser['id']] = $arrUser;
			}
		}
		return $arrRes;
	}

	/**
	* Check if the "redirection after first login" is set up correctly
	*
	* @return array
	*/
	protected function getRedirectionSetup() {
		$arrRes = [];
		$data = $this->Database->query("SELECT * FROM tl_page");
		//find id of jumpTo page
		while($arrPage = $data->fetchAssoc()) {
			$arrPages[$arrPage['id']] = $arrPage;
			if($arrPage['type'] == 'root' && $arrPage['family_jumpTo'] != 0) {
				$arrId = $arrPage;
			}
		}
		//find db record of jumpTo page
		foreach($arrPages as $arrPage) {
			if($arrPage['id'] == $arrId) {
				$arrRes = $arrPage;
			}
		}
		return $arrRes;
	}
}
