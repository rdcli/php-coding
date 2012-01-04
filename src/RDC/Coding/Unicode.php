<?php
/**
 * This file is part of RDC\Coding.
 *
 * RDC\Coding is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * RDC\Coding is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with RDC\Coding.  If not, see <http://www.gnu.org/licenses/>.
 *
 * © 2002-2011 Romain Dorgueil
 */

namespace RDC\Coding;

/**
 * Tool for writing ISO-8859 / Unicode agnostic PHP code.
 *
 * @package    \RDC\Coding
 * @subpackage Unicode
 * @author     Romain Dorgueil <romain@dorgueil.net>
 */
class Unicode
{
    /**
     * Diacritics <-> ASCII.
     */
    static private $_diacritics = array(
            'A'  => '\x{00C0}-\x{00C5}',
            'AE' => '\x{00C6}',
            'C'  => '\x{00C7}',
            'D'  => '\x{00D0}',
            'E'  => '\x{00C8}-\x{00CB}',
            'I'  => '\x{00CC}-\x{00CF}',
            'N'  => '\x{00D1}',
            'O'  => '\x{00D2}-\x{00D6}\x{00D8}',
            'OE' => '\x{0152}',
            'S'  => '\x{0160}',
            'U'  => '\x{00D9}-\x{00DC}',
            'Y'  => '\x{00DD}',
            'Z'  => '\x{017D}',
            'a'  => '\x{00E0}-\x{00E5}',
            'ae' => '\x{00E6}',
            'c'  => '\x{00E7}',
            'd'  => '\x{00F0}',
            'e'  => '\x{00E8}-\x{00EB}',
            'i'  => '\x{00EC}-\x{00EF}',
            'n'  => '\x{00F1}',
            'o'  => '\x{00F2}-\x{00F6}\x{00F8}',
            'oe' => '\x{0153}',
            's'  => '\x{0161}',
            'u'  => '\x{00F9}-\x{00FC}',
            'y'  => '\x{00FD}\x{00FF}',
            'z'  => '\x{017E}',
            'ss' => '\x{00DF}',
        );


    /**
     * Returns whether or not a string contains unicode characters.
     *
     * @param  string $str
     * @static
     * @access public
     * @return string
     */
    static public function isUnicode($t)
    {
        return !strlen(
                preg_replace(
                    ',[\x09\x0A\x0D\x20-\x7E]'.           # ASCII
                    '|[\xC2-\xDF][\x80-\xBF]'.            # non-overlong 2-byte
                    '|\xE0[\xA0-\xBF][\x80-\xBF]'.        # excluding overlongs
                    '|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}'. # straight 3-byte
                    '|\xED[\x80-\x9F][\x80-\xBF]'.        # excluding surrogates
                    '|\xF0[\x90-\xBF][\x80-\xBF]{2}'.     # planes 1-3
                    '|[\xF1-\xF3][\x80-\xBF]{3}'.         # planes 4-15
                    '|\xF4[\x80-\x8F][\x80-\xBF]{2}'.     # plane 16
                    ',sS',
                    '', $t));
    }


    /**
     * Takes any string and make it UTF-8. Do not double encode if the string is
     * already UTF-8 encoded.
     *
     * @param  string $t
     * @static
     * @access public
     * @return string
     */
    static public function getUnicode($t)
    {
        return self::isUnicode($t) ? $t : utf8_encode($t);
    }


    /**
     * Takes any string and make it ISO.
     *
     * @param  string $str
     * @static
     * @access public
     * @return string
     */
    static public function getIso($t)
    {
        return self::isUnicode($t) ? utf8_decode($t) : $t;
    }


    /**
     * Convenience substitute for htmlentities, that do not care about encoding.
     *
     * @param  string $t
     * @static
     * @access public
     * @return string
     */
    static public function toHtmlEntities($t)
    {
        return htmlentities(self::getIso($t));
    }

    /**
     * Converts diacritic characters to matching regular ascii characters.
     *
     * TODO Optimize this slow shit.
     *
     * @param  string $str
     * @static
     * @access public
     * @return string
     */
    public static function removeDiacritics($t)
    {
        foreach (self::$_diacritics as $r => $p)
        {
            $t = preg_replace('/['.$p.']/u', $r, $t);
        }

        return $t;
    }
}

// vim: et sw=4 ts=4
