<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at                              |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Sune Jensen <sj@sunet.dk>                        |
// +----------------------------------------------------------------------+
//
// $Id: Rates_DNB.php,v 0.1 2008/08/30 07:44:12 cross Exp $

/**
 * Exchange rate driver - Danmarks Nationalbank
 *
 * @link http://www.nationalbanken.dk/DNUK/AboutUs.nsf/side/Webinformation!OpenDocument Disclaimer
 * @link http://www.nationalbanken.dk/DNUK/AboutUs.nsf/side/Privacy_Statement!OpenDocument Privacy Statement
 *
 * @author Sune Jensen <sj@sunet.dk>
 * @copyright Copyright 2008 Sune Jensen
 * @license http://www.php.net/license/2_02.txt PHP License 2.0
 * @package Services_ExchangeRates
 */
 
/**
 * Include common functions to handle cache and fetch the file from the server
 */
require_once 'Services/ExchangeRates/Common.php';

/**
 * European Central Bank Exchange Rate Driver
 *
 * @package Services_ExchangeRates
 */
class Services_ExchangeRates_Rates_DNB extends Services_ExchangeRates_Common {

   /**
    * URL of XML feed
    * @access private
    * @var string
    */
    var $_feedXMLUrl = 'http://www.nationalbanken.dk/DNUK/rates.nsf/rates.xml';
       
   /**
    * Downloads exchange rates in terms of the Danisk Krone from Danmarks Nationalbank. This
    * information is updated daily, and is cached by default for 1 hour.
    *
    * Returns a multi-dimensional array containing:
    * 'rates' => associative array of currency codes to exchange rates
    * 'source' => URL of feed
    * 'date' => date feed last updated, pulled from the feed (more reliable than file mod time)
    *
    * @link http://www.nationalbanken.dk/dnuk/Rates.nsf/side/Exchange_rates!OpenDocument HTML version
    * @link http://www.nationalbanken.dk/DNUK/rates.nsf/rates.xml XML version
    *
    * @param int Length of time to cache (in seconds)
    * @param string cacheDir The dir to cache rates
    * @return array Multi-dimensional array
    */
    function retrieve($cacheLength, $cacheDir) {
    
        // IMPORTANT: defines Euro mapping.  Without this, you can't convert 
        // to or from the Euro!
        $return['rates'] = array('DKK' => 1.0);
    
        $return['source'] = $this->_feedXMLUrl;
        
        // retrieve the feed from the server or cache
        $root = $this->retrieveXML($this->_feedXMLUrl, $cacheLength, $cacheDir);
        
        
    
        // set date published
        $return['date'] = $root->children[1]->attributes['id'];
        
        // get down to array of exchange rates
        $xrates = $root->children[1]->children;
        
        // loop through and put them into an array
        foreach ($xrates as $rateinfo) {
            if ($rateinfo->name == 'currency') {
                $rate = $rateinfo->attributes['rate'];
                $rate = str_replace('.', '', $rate);    
                $rate = str_replace(',', '.', $rate);
                $rate = (double)$rate;    
            
                $return['rates'][$rateinfo->attributes['code']] = $rate;
            }
        }
        
        return $return; 

    }

}

?>

