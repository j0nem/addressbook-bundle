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

    	echo 'HEEEEELLLLLOOO';

    	$this->Template->test = 'Hallo';

    	$this->render('fm_verification.html5.twig');
    }
}