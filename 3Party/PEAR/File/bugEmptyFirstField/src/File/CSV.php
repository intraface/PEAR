<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * File::CSV
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category    File
 * @package     File
 * @author      Tomas V.V.Cox <cox@idecnet.com>
 * @author      Helgi Þormar <dufuz@php.net>
 * @copyright   2004-2005 The Authors
 * @license     http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version     CVS: $Id: CSV.php,v 1.41 2007/05/20 12:25:14 dufuz Exp $
 * @link        http://pear.php.net/package/File
 */

require_once 'File/CSV.php';

/**
* File class for handling CSV files (Comma Separated Values), a common format
* for exchanging data.
*
* TODO:
*  - Usage example and Doc
*  - Use getPointer() in discoverFormat
*  - Add a line counter for being able to output better error reports
*  - Store the last error in GLOBALS and add File_CSV::getLastError()
*
* Wish:
*  - Other methods like readAll(), writeAll(), numFields(), numRows()
*  - Try to detect if a CSV has header or not in discoverFormat() (not possible with CSV)
*
* Known Bugs:
* (they has been analyzed but for the moment the impact in the speed for
*  properly handle this uncommon cases is too high and won't be supported)
*  - A field which is composed only by a single quoted separator (ie -> ;";";)
*    is not handled properly
*  - When there is exactly one field minus than the expected number and there
*    is a field with a separator inside, the parser will throw the "wrong count" error
*
* Info about CSV and links to other sources
* http://www.shaftek.org/publications/drafts/mime-csv/draft-shafranovich-mime-csv-00.html#appendix
*
* @author Tomas V.V.Cox <cox@idecnet.com>
* @author Helgi Þormar <dufuz@php.net>
* @package File
*/
class File_CSV_EmptyFirstFieldBugFix extends File_CSV
{

    
    /**
    * Reads a row of data as an array from a CSV file. It's able to
    * read memo fields with multiline data.
    *
    * @param string $file   The filename where to write the data
    * @param array  &$conf   The configuration of the dest CSV
    *
    * @return mixed Array with the data read or false on error/no more data
    */
    function readQuoted($file, &$conf)
    {
        if (!$fp = File_CSV::getPointer($file, $conf, FILE_MODE_READ)) {
            return false;
        }

        $buff = $old = $prev = $c = '';
        $ret  = array();
        $i = 1;
        $in_quote = false;
        $quote = $conf['quote'];
        $f     = $conf['fields'];
        $sep   = $conf['sep'];
        while (false !== $ch = fgetc($fp)) {
            $old  = $prev;
            $prev = $c;
            $c    = $ch;

            // Common case
            if ($c != $quote && $c != $sep && $c != "\n" && $c != "\r") {
                $buff .= $c;
                continue;
            }

            // Start quote.
            if (
                $in_quote === false &&
                $quote && $c == $quote &&
                (
                 $prev == $sep || $prev == "\n" || $prev === null ||
                 $prev == "\r" || $prev == '' || $prev == ' '
                 || $prev == '=' //excel compat
                )
            ) {
                $in_quote = true;
                // excel compat, removing the = part but only if we are in a quote
                if ($prev == '=') {
                    $buff{strlen($buff) - 1} = '';
                }
            }

            if ($in_quote) {

                // When does the quote end, make sure it's not double quoted
                if ($c == $sep && $prev == $quote && $old != $quote) {
                    $in_quote = false;
                } elseif ($c == $sep && $buff == $quote.$quote) {
                    // In case we are dealing with double quote but empty value
                    $in_quote = false;
                } elseif ($c == "\n" || $c == "\r") {
                    $sub = ($prev == "\r") ? 2 : 1;
                    $buff_len = strlen($buff);
                    if (
                        $buff_len >= $sub &&
                        $buff[$buff_len - $sub] == $quote
                    ) {
                        $in_quote = false;
                    }
                }
            }

            if (!$in_quote && ((($c == "\n" || $c == "\r") && $prev != '') || ($c == $conf['sep']))) {
                // More fields than expected
                if ($c == $sep && (count($ret) + 1) == $f) {
                    // Seek the pointer into linebreak character.
                    while (true) {
                        $c = fgetc($fp);
                        if  ($c == "\n" || $c == "\r" || $c == '') {
                            break;
                        }
                    }

                    // Insert last field value.
                    $ret[] = File_CSV::unquote($buff, $quote);
                    return $ret;
                }

                // Less fields than expected
                if (($c == "\n" || $c == "\r") && $i != $f) {
                    // Insert last field value.
                    $ret[] = File_CSV::unquote($buff, $quote);
                    if (count($ret) == 1 && empty($ret[0])) {
                        return array();
                    }

                    // Pair the array elements to fields count. - inserting empty values
                    $ret_count = count($ret);
                    $sum = ($f - 1) - ($ret_count - 1);
                    $data = array_merge($ret, array_fill($ret_count, $sum, ''));
                    return $data;
                }

                if ($prev == "\r") {
                    $buff = substr($buff, 0, -1);
                }

                // Convert EOL character to Unix EOL (LF).
                if ($conf['eol2unix']) {
                    $buff = preg_replace('/(\r\n|\r)$/', "\n", $buff);
                }

                $ret[] = File_CSV::unquote($buff, $quote);
                if (count($ret) == $f) {
                    return $ret;
                }
                $buff = '';
                ++$i;
                continue;
            }
            $buff .= $c;
        }

        /* If it's the end of the file and we still have something in buffer
         * then we process it since files can have no CL/FR at the end
         */
        $feof = feof($fp);
        if ($feof && !in_array($buff, array("\r", "\n", "\r\n")) && strlen($buff) > 0) {
            $ret[] = File_CSV::unquote($buff, $quote);
            if (count($ret) == $f) {
                return $ret;
            }
        }

        return !$feof ? $ret : false;
    }
}
