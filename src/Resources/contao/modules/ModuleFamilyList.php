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

 use Patchwork\Utf8;

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

			$objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['fm_list'][0]) . ' ###';
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

		//Redirect if not logged in
		if(!FE_USER_LOGGED_IN){
			/* FUNKTIONIERT in Contao 4 schinbar NICHT MEHR
			*
			* $objHandler = new $GLOBALS['TL_PTY']['error_403']();
			* $objHandler->generate($objPage->id);
			*/
			return false;
		}

		$arrList = Family::fullList();

		//DETAIL
		if($this->Input->get('id') != '' && $arrList[$this->Input->get('id')]) {
			$this->Template->mode = 'detail';

			$arrActiveRecord = $arrList[$this->Input->get('id')];
			$arrActiveRecord['birthday'] = Family::formatDate($arrActiveRecord,false);

			if($arrActiveRecord['city']) {
				$arrActiveRecord['gmaps_link'] = '<a href="https://google.com/maps/search/'.str_replace(' ','%20',Family::formatResidence($arrActiveRecord,true)).'" target="_blank">'.Family::formatResidence($arrActiveRecord).'</a>';
			}
			if($acc = Family::getAccountOfAddressEntry($arrActiveRecord['id'])) {
				$arrActiveRecord['email'] = '<a href="mailto:'.$acc->email.'">'.$acc->email.'</a>';
			}
			if($arrActiveRecord['father']) {
				$arrActiveRecord['father_link'] = '<a href="'.$this->addToUrl('id=' . $arrActiveRecord['father'], ['_locale']).'">'.Family::formatName(Family::getAddressEntry($arrActiveRecord['father'])).'</a>';
			}
			if($arrActiveRecord['mother']) {
				$arrActiveRecord['mother_link'] = '<a href="'.$this->addToUrl('id=' . $arrActiveRecord['mother'], ['_locale']).'">'.Family::formatName(Family::getAddressEntry($arrActiveRecord['mother'])).'</a>';
			}
			if($arrActiveRecord['partner']) {
				$arrActiveRecord['partner_link'] = '<a href="'.$this->addToUrl('id=' . $arrActiveRecord['partner'], ['_locale']).'">'.Family::formatName(Family::getAddressEntry($arrActiveRecord['partner'])).'</a>';
			}
			if($ch = Family::getChildren($arrActiveRecord['id'])) {
				foreach($ch as $chId) {
					$arrActiveRecord['children'][$chId]['link'] = '<a href="'.$this->addToUrl('id=' . $chId, ['_locale']).'">'.Family::formatName(Family::getAddressEntry($chId)).'</a>';
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
			$this->Template->backHref = $this->addToUrl('id=',['_locale','id']);

			global $objPage;
			$objPage->pageTitle = Family::formatName($arrList[$this->Input->get('id')]);
		}

		//LIST
		else {

			$this->Template->mode = 'list';
			foreach($arrList as $elem) {
				$elem['name_string'] = Family::formatName($elem,true);
				$elem['date_string'] = Family::formatDate($elem);
				$elem['detail_link'] = '<a href="'.$this->addToUrl('id=' . $elem['id'],['_locale']).'">'.$elem['name_string'].'</a>';

				if($elem['city']) {
					$elem['gmaps_link'] = '<a href="https://google.com/maps/search/'.str_replace(' ','%20',Family::formatResidence($elem,true)).'" target="_blank">'.Family::formatResidence($elem).'</a>';
				}

				$arrFamily[$elem['id']] = $elem;
			}

			$this->Template->arrFamily = $arrFamily;
		}
		return true;
	}
}
