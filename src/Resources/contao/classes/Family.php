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


	 public static function fullList() {
		 if(!static::$familyListLoaded) {
			 $list = \Database::getInstance()
						->prepare("SELECT * FROM tl_family ORDER BY lastname,firstname")->execute();
			while($row = $list->fetchAssoc()) {
				static::$arrFamilyList[$row['id']] = $row;
			}
		 }
		 return static::$arrFamilyList;
	 }

	 public static function nameList() {
		 $list = static::fullList();
		 foreach ($list as $elem) {
			 $nameList[$elem['id']] = static::formatName($elem);
		 }
		 return $nameList;
	 }

	 public static function formatName($elem, $withHtml = false) {
		 $str = $elem['lastname'] . ', ' . $elem['firstname'];
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
			if($yearOnly) $str = date('Y',$elem['dateOfBirth']);
			else $str = date('d.m.Y',$elem['dateOfBirth']);

			if($withAge) $str .= ' (Alter: ' . floor((date("Ymd") - date("Ymd",$elem['dateOfBirth'])) / 10000) . ')';
			return $str;
		 }
		return false;
	 }

	 public static function formatResidence($elem, $withAddress = false) {
		 static::loadLanguageFile('countries');
		 $str = '';

		 if($withAddress) $str .= $elem['street'] . ', ' . $elem['postal'] . ' ';
		 $str .= $elem['city'] . ', ' . $GLOBALS['TL_LANG']['CNT'][$elem['country']];

		 return $str;
	 }

	 public static function getAddressEntry($id) {
		return static::fullList()[$id];
	 }

	 public static function getMemberAccount($id) {
		 $arrEntry = static::fullList()[$id];

		if($arrEntry['account_id']) {
			 $objUser = \FrontendUser::getInstance();
			 $objUser->findBy('id',$arrEntry['account_id']);

			 return $objUser;
		 }
		 return false;
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
