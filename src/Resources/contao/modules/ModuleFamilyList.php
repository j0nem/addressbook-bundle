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

 class ModuleFamilyList extends \Module
{
 	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'fm_list';


	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . $GLOBALS['TL_LANG']['FMD']['fm_list'][0] . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->import('FrontendUser','User');
		global $objPage;

		//Redirect if not logged in
		if(!FE_USER_LOGGED_IN){
			return false;
		}

		$arrList = Family::fullList(true);

		//DETAIL
		if(\Input::get('id') != '' && $arrList[\Input::get('id')]) {
			$this->Template->mode = 'detail';

			$arrActiveRecord = $arrList[\Input::get('id')];
			$arrActiveRecord['birthday'] = Family::formatDate($arrActiveRecord,false);

			if ($arrActiveRecord['city'] || $arrActiveRecord['country']) {
				$arrActiveRecord['gmaps_link'] = '<a href="https://google.com/maps/search/'.str_replace(' ','%20',Family::formatResidence($arrActiveRecord,true)).'" target="_blank">'.Family::formatResidence($arrActiveRecord,true).'</a>';
			}
			
			if($acc = Family::getAccountOfAddressEntry($arrActiveRecord['id'])) {
				$arrActiveRecord['email'] = '<a href="mailto:'.$acc->email.'">'.$acc->email.'</a>';
				$arrActiveRecord['about_me'] = $acc->about_me;
			}
			if($arrActiveRecord['father']) {
				$arrActiveRecord['father_link'] = '<a href="'.$objPage->getFrontendUrl('/id/'.$arrActiveRecord['father']).'">'.Family::formatName(Family::getAddressEntry($arrActiveRecord['father'])).'</a>';
			}
			if($arrActiveRecord['mother']) {
				$arrActiveRecord['mother_link'] = '<a href="'.$objPage->getFrontendUrl('/id/'.$arrActiveRecord['mother']).'">'.Family::formatName(Family::getAddressEntry($arrActiveRecord['mother'])).'</a>';
			}
			if($arrActiveRecord['partner']) {
				$arrActiveRecord['partner_link'] = '<a href="'.$objPage->getFrontendUrl('/id/'.$arrActiveRecord['partner']).'">'.Family::formatName(Family::getAddressEntry($arrActiveRecord['partner'])).'</a>';
			}
			if($arrActiveRecord['partner_relation']) {
				\Controller::loadLanguageFile('tl_family');
				$arrActiveRecord['str_partner_relation'] = $GLOBALS['TL_LANG']['tl_family']['partner_relation_options'][$arrActiveRecord['partner_relation']];
			}
			if($ch = Family::getChildren($arrActiveRecord['id'])) {
				foreach($ch as $chId) {
					$arrActiveRecord['children'][$chId]['link'] = '<a href="'.$objPage->getFrontendUrl('/id/'.$chId).'">'.Family::formatName(Family::getAddressEntry($chId)).'</a>';
				}
			}

			if($arrActiveRecord['account_id'] == $this->User->id) {
				$this->Template->isMyProfile = true;
				if($this->family_editJumpTo && ($objTarget = $this->objModel->getRelated('family_editJumpTo')) instanceof \PageModel) {
					$this->Template->editHref = $objTarget->getFrontendUrl();
				}
			}
			if($arrActiveRecord['account_id'] == 0) {
				$this->Template->isNotMaintained = true;
				if($this->family_suggestJumpTo && ($objTarget = $this->objModel->getRelated('family_suggestJumpTo')) instanceof \PageModel) {
					$this->Template->suggestHref = $objTarget->getFrontendUrl();
				}
			}
			$this->Template->activeRecord = $arrActiveRecord;
			$this->Template->backHref = $objPage->getFrontendUrl();

			$objPage->pageTitle = Family::formatName($arrList[\Input::get('id')]);
		}

		//LIST
		else {

			$this->Template->mode = 'list';
			foreach($arrList as $elem) {
				$elem['name_string'] = Family::formatName($elem,true);
				$elem['date_string'] = Family::formatDate($elem);
				if ($elem['city'] || $elem['country']) {
					$elem['residence'] = Family::formatResidence($elem);
				}
				$elem['detail_href'] = $objPage->getFrontendUrl('/id/'.$elem['id']);

				$arrFamily[$elem['id']] = $elem;
			}

			$this->Template->arrFamily = $arrFamily;
		}
		return true;
	}
}
