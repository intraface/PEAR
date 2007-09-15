<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * HTML QuickForm Alternate Select
 *
 * This file must be included *after* HTML/QuickForm.php
 *
 * HTML_QuickForm plugin that changes a select into a group of radio buttons
 * or checkboxes with an optional textbox for other options not listed. If
 * the select element is listed as multiple, then it will be rendered with
 * checkboxes, otherwise it is rendered with radio buttons.
 *
 * PHP Versions 4 and 5
 *
 * @category    HTML
 * @package     HTML_QuickForm_altselect
 * @author      David Sanders (shang.xiao.sanders@gmail.com)
 * @license     http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version     Release: @package_version@
 * @link        http://pyrus.sourceforge.net/HTML_QuickForm_altselect.html
 * @see         HTML_QuickForm_select
 */

require_once 'HTML/QuickForm/select.php';

/**
* Replace PHP_EOL constant
*
*  category    PHP
*  package     PHP_Compat
* @link        http://php.net/reserved.constants.core
* @author      Aidan Lister <aidan@php.net>
* @since       PHP 5.0.2
*/
if (!defined('PHP_EOL')) {
    switch (strtoupper(substr(PHP_OS, 0, 3))) {
        // Windows
        case 'WIN':
            define('PHP_EOL', "\r\n");
            break;

        // Mac
        case 'DAR':
            define('PHP_EOL', "\r");
            break;

        // Unix
        default:
            define('PHP_EOL', "\n");
    }
}

/**
 * HTML QuickForm Alternate Select
 *
 * HTML_QuickForm plugin that changes a select into a group of radio buttons
 * or checkboxes with an optional textbox for other options not listed. If
 * the select element is listed as multiple, then it will be rendered with
 * checkboxes, otherwise it is rendered with radio buttons.
 *
 * @category    HTML
 * @package     HTML_QuickForm_altselect
 * @author      David Sanders (shang.xiao.sanders@gmail.com)
 * @license     http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version     Release: @package_version@
 * @link        http://pyrus.sourceforge.net/HTML_QuickForm_altselect.html
 * @see         HTML_QuickForm_select
 */
class HTML_QuickForm_advgroup extends HTML_QuickForm_element
{
    /**
     * Include the other text field for non-listed entry.
     *
     * @var     bool
     * @access  public
     */
    var $includeOther = false;

    /**
     * Label for the Other option.
     *
     * @var     string
     * @access  public
     */
    var $otherLabel = 'Other';

    /**
     * Text label to go in front of other text field (singular mode).
     *
     * @var     string
     * @access  public
     */
    var $otherText = 'If other please specify:';

    /**
     * Text label to go in front of other text field (multiple mode).
     *
     * @var     string
     * @access  public
     */
    var $otherTextMultiple = 'Other:';

    /**
     * Delimiter between subelements.  Use br to go vertical, or nbsp to go
     * horizontal.
     *
     * @var     string
     * @access  public
     */
    var $delimiter = '<br />';

    /**
     * Other value storage.
     *
     * @var     string
     * @access  private
     */
    var $_otherValue;

    /**
     * Associative array of attributes for each of the individual form elements.
     * NOTE: use "_qf_other" for the other radio button, and "_qf_other_text"
     * for the text field.
     *
     * @var      array     Associative array of attributes (see HTML_Common)
     * @access   private
     */
    var $_individualAttributes;

    var $_group;

    /**
     * Constructor.  Used to distinguish the attributes array which should be
     * an associative array of options to either a typical HTML attribute string
     * or another associative array
     *
     * @param  string    $elementName  select name attribute
     * @param  mixed     $elementLabel label(s) for the select
     * @param  mixed     $options      data to be used to populate options
     * @param  mixed     $attributes   an associative array of option value
     *                                 -> attributes. Each attribute is either
     *                                 a typical HTML attribute string or an
     *                                 associative array.
     *                                 NOTE: use "_qf_other" for the other radio
     *                                 button, and "_qf_other_text" for the
     *                                 text field.
     * @return void
     */
    function HTML_QuickForm_advgroup($elementName = null,
                                      $elementLabel = null,
                                      $group = null,
                                      $options = null,
                                      $attributes = null)
    {
        if (func_get_args()) {
            HTML_QuickForm_select::HTML_QuickForm_element($elementName,
                                                         $elementLabel,
                                                         $options);
            $this->_individualAttributes = $attributes;

            $this->_group = $group;
        }
    }

    /**
     * Render the HTML_QuickForm element.
     *
     * @access  public
     * @return  string The rendered HTML
     */
    function toHtml()
    {
        return $this->group->toHTML();
    }
}

if (class_exists('HTML_QuickForm')) {
    HTML_QuickForm::registerElementType('advgroup',
                                        'HTML/QuickForm/advgroup.php',
                                        'HTML_QuickForm_advgroup');
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
?>
