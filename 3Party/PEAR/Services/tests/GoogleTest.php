<?php
/**
 * Test class
 *
 * Requires Sebastian Bergmann's PHPUnit
 *
 * PHP version 5
 *
 * @category  Services
 * @package   Services_Google
 * @author    Lars Olesen <lars@legestue.net>
 * @copyright 2007 Authors
 * @license   GPL http://www.opensource.org/licenses/gpl-license.php
 * @version   @package-version@
 * @link      http://public.intraface.dk
 */
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '/../Google.php';

error_reporting(E_STRICT);

/**
 * Test class
 *
 * @category  Services
 * @package   Services_Geocoding
 * @author    Lars Olesen <lars@legestue.net>
 * @copyright 2007 Authors
 * @license   GPL http://www.opensource.org/licenses/gpl-license.php
 * @version   @package-version@
 * @link      http://public.intraface.dk
 */
class GoogleTest extends PHPUnit_Framework_TestCase
{

    private $search_returns = 3;

    public function testSearchReturnsANumResult()
    {
        $key = 'UrSohRFQFHLc/kavpPYGxOzGq9Bp9WXO';
        $google = new Services_Google($key);
        $google->queryOptions['limit'] = 10;
        $google->search('site:www.vih.dk test');
        $this->assertEquals($this->search_returns, $google->numResults());
    }

    public function testUsageOfIterator()
    {
        $key = 'UrSohRFQFHLc/kavpPYGxOzGq9Bp9WXO';
        $google = new Services_Google($key);
        $google->queryOptions['limit'] = 10;
        $google->search('site:www.vih.dk test');
        $i = 0;
        foreach($google as $key => $result) {
            $this->assertEquals($i, $key);
            $this->assertTrue(!empty($result->title), 'title fails on iteration ' . $i);
            $this->assertTrue(!empty($result->URL), 'title fails on iteration ' . $i);
            $this->assertTrue(!empty($result->snippet), 'title fails on iteration ' . $i);
            $i++;
        }
    }

    public function testIteratorShouldOnlyIterateThroughTheNumberOfResultsReturnedFromSearch()
    {
        $key = 'UrSohRFQFHLc/kavpPYGxOzGq9Bp9WXO';
        $google = new Services_Google($key);
        $google->queryOptions['limit'] = 10;
        $google->search('site:www.vih.dk test');
        $i = 0;

        foreach($google as $key => $result) {
            $i++;
        }

        $this->assertEquals($this->search_returns, $i);
    }

    public function testWrongAuthReturnsASoapException()
    {
        $key = 'wrongauth';

        $google = new Services_Google($key);
        $google->queryOptions['limit'] = 10;
        $google->search('site:www.vih.dk test');
        try {
            $google->numResults();
            $this->fail('A Soap Exception was expected here');
        } catch (SoapFault $e) {
            return;
        }
    }

}
?>