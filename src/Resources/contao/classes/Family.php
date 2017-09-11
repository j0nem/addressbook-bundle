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

 class Family extends \System {

	 protected static $arrFamilyList;
	 protected static $familyListLoaded;


	 public static function fullList($onlyVisible = false) {
		 if(!static::$familyListLoaded) {
			 $list = \Database::getInstance()
						->prepare("SELECT * FROM tl_family ORDER BY lastname,firstname")->execute();
			while($row = $list->fetchAssoc()) {
				if($row['visible'] == 1 || !$onlyVisible) {
					$arrReturn[$row['id']] = $row;
				}
				static::$arrFamilyList[$row['id']] = $row;
			}
		 }
		 return $arrReturn;
	 }

	 public static function nameList($onlyVisible = false) {
		 $list = static::fullList($onlyVisible);
		 foreach ($list as $elem) {
			 $nameList[$elem['id']] = static::formatName($elem);
		 }
		 return $nameList;
	 }

	 public static function formatName($elem, $withHtml = false) {
		$str = '';
		if($elem['isDeceased']) {
			$str .= ' ✝ ';
		}
		if($elem['title']) {
			\Controller::loadLanguageFile('tl_family');
			$str .= $GLOBALS['TL_LANG']['tl_family']['title_options'][$elem['title']] . ' ';
		}
		$str .= $elem['lastname'] . ', ' . $elem['firstname'];
		if($elem['nameOfBirth'] && $elem['name'] != $elem['nameOfBirth']) {
			 $str .=  ' ';
			 if($withHtml) $str .= '<span class="nameOfBirth">';
			 $str .= '(geb. ' . $elem['nameOfBirth'] . ')';
			 if($withHtml) $str .= '</span>';
		}

		 return $str;
	 }

	 public static function formatDate($elem, $yearOnly = true, $withAge = true) {
		if($elem['dateOfBirth']) {
			$str = '∗ ';
			if($yearOnly) {
				$str .= date('Y',$elem['dateOfBirth']);
			}
			else {
				$str .= date('d.m.Y',$elem['dateOfBirth']);
			}
			//add age
			if($withAge && !$elem['isDeceased']) {
				$str .= ' (Alter: ' . floor((date("Ymd") - date("Ymd",$elem['dateOfBirth'])) / 10000) . ')';
			}
			//add date of death
			elseif($elem['isDeceased'] && $elem['dateOfDeath']) {
				if($yearOnly) {
					$str .= ' (✝ ' . date('Y',$elem['dateOfDeath']) . ')';
				}
				else {
					$str .= ' (✝ ' . date('d.m.Y',$elem['dateOfDeath']) . ')';
				}
			}
			return $str;
		}
		return false;
	 }

	 public static function formatResidence($elem, $withAddress = false) {
		static::loadLanguageFile('countries');
		$str = '';

		if($withAddress) {
			$str .= $elem['street'] . ', ' . $elem['postal'] . ' ';
		}
		$str .= $elem['city'] . ', ' . $GLOBALS['TL_LANG']['CNT'][$elem['country']];

		return $str;
	 }

	 public static function getAddressEntry($id) {
		return static::fullList()[$id];
	 }

	 public static function getAddressEntryOfMember($id) {
		foreach(static::fullList() as $arrEntry) {
			if($arrEntry['account_id'] == $id) return $arrEntry;
		}
	 }

	 public static function getAccountOfAddressEntry($id) {
		$arrEntry = static::fullList()[$id];
		if($arrEntry['account_id']) {
			 $objUser = \MemberModel::findById($arrEntry['account_id']);
			 return $objUser;
		 }
		 return false;
	 }

	 public static function getEmptyEntry() {
		 $arrColumns = \Database::getInstance()->listFields('tl_family');
		 foreach($arrColumns as $arrColumn) {
			if($arrColumn['type'] == 'int'){
				$arrEmptyEntry[$arrColumn['name']] = 0;
			}
			elseif($arrColumn['type'] == 'varchar' || $arrColumn['type'] == 'char') {
				$arrEmptyEntry[$arrColumn['name']] = '';
			}
		 }
		 return $arrEmptyEntry;
	 }

	 public static function getChildren($id) {
		 $arrList = static::fullList();
		 $arrChildren = array();

		 foreach($arrList as $arrEntry) {
			if($arrEntry['mother'] == $id) $arrChildren[] = $arrEntry['id'];
			if($arrEntry['father'] == $id) $arrChildren[] = $arrEntry['id'];
		 }

		 return $arrChildren;
	 }
 }
