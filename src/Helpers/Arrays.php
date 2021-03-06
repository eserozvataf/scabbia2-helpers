<?php
/**
 * Scabbia2 Helpers Component
 * https://github.com/eserozvataf/scabbia2
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link        https://github.com/eserozvataf/scabbia2-helpers for the canonical source repository
 * @copyright   2010-2016 Eser Ozvataf. (http://eser.ozvataf.com/)
 * @license     http://www.apache.org/licenses/LICENSE-2.0 - Apache License, Version 2.0
 */

namespace Scabbia\Helpers;

/**
 * A bunch of utility methods for array operations
 *
 * @package     Scabbia\Helpers
 * @author      Eser Ozvataf <eser@ozvataf.com>
 * @since       1.0.0
 *
 * @scabbia-compile
 */
class Arrays
{
    /**
     * Flattens given parameters into an array
     *
     * @param array $uValues values
     *
     * @return array flatten array
     */
    public static function flat(...$uValues)
    {
        $tArray = [];

        foreach ($uValues as $tValue) {
            if (is_array($tValue)) {
                foreach (self::flat(...$tValue) as $tValue2) {
                    $tArray[] = $tValue2;
                }

                continue;
            }

            $tArray[] = $tValue;
        }

        return $tArray;
    }

    /**
     * Gets the first element in array, otherwise returns default value
     *
     * @param array         $uArray     array
     * @param mixed|null    $uDefault   default value
     *
     * @return mixed|null first element of array
     */
    public static function getFirst(array $uArray, $uDefault = null)
    {
        $tValue = current($uArray);
        if ($tValue === false) {
            return $uDefault;
        }

        return $tValue;
    }

    /**
     * Gets the specified element in array, otherwise returns default value
     *
     * @param array $uArray     array
     * @param mixed $uElement   key
     * @param mixed $uDefault   default value
     *
     * @return mixed|null extracted element
     */
    public static function get(array $uArray, $uElement, $uDefault = null)
    {
        if (!isset($uArray[$uElement])) {
            return $uDefault;
        }

        return $uArray[$uElement];
    }

    /**
     * Gets the specified elements in array
     *
     * @param array $uArray    array
     * @param array $uElements elements
     *
     * @return array array of extracted elements
     */
    public static function getArray(array $uArray, ...$uElements)
    {
        $tReturn = [];

        foreach ($uElements as $tElement) {
            $tReturn[$tElement] = isset($uArray[$tElement]) ? $uArray[$tElement] : null;
        }

        return $tReturn;
    }

    /**
     * Accesses child element by path notation, otherwise returns default value
     *
     * @param array     $uArray     array
     * @param mixed     $uElement   key
     * @param mixed     $uDefault   default value
     * @param string    $uSeparator path separator
     *
     * @return mixed|null extracted element
     */
    public static function getPath(array $uArray, $uElement, $uDefault = null, $uSeparator = "/")
    {
        $tVariable = $uArray;

        foreach (explode($uSeparator, $uElement) as $tKey) {
            if (!isset($tVariable[$tKey])) {
                return $uDefault;
            }

            $tVariable = $tVariable[$tKey];
        }

        return $tVariable;
    }

    /**
     * Accesses child elements by path notation
     *
     * @param array $uArray    array
     * @param array $uElements elements
     *
     * @return array array of extracted elements
     */
    public static function getArrayPath(array $uArray, ...$uElements)
    {
        $tReturn = [];

        foreach ($uElements as $tElement) {
            $tVariable = $uArray;

            foreach (explode("/", $tElement) as $tKey) {
                if (!isset($tVariable[$tKey])) {
                    $tVariable = null;
                    break;
                }

                $tVariable = $tVariable[$tKey];
            }

            $tReturn[$tElement] = $tVariable;
        }

        return $tReturn;
    }

    /**
     * Gets a random element in array
     *
     * @param array $uArray array
     *
     * @return mixed|null a random element in the set
     */
    public static function getRandom(array $uArray)
    {
        // mt_srand((int)(microtime(true) * 0xFFFF));

        $tCount = count($uArray);
        if ($tCount === 0) {
            return null;
        }

        $uValues = array_values($uArray);

        return $uValues[mt_rand(0, $tCount - 1)];
    }

    /**
     * Returns an array filled with the elements in specified range
     *
     * @param int|float   $uMinimum     minimum number
     * @param int|float   $uMaximum     maximum number
     * @param int|float   $uStep        step
     * @param bool        $uWithKeys    whether set keys or not
     *
     * @return array a set contains sequence of numbers in given range
     */
    public static function range($uMinimum, $uMaximum, $uStep = 1, $uWithKeys = false)
    {
        $tReturn = [];

        for ($i = $uMinimum; $i <= $uMaximum; $i += $uStep) {
            if ($uWithKeys) {
                $tReturn[$i] = $i;
                continue;
            }

            $tReturn[] = $i;
        }

        return $tReturn;
    }

    /**
     * Sorts an array by key
     *
     * @param array     $uArray array
     * @param mixed     $uField field
     * @param string    $uOrder order
     *
     * @return array sorted array
     */
    public static function sortByKey(array $uArray, $uField, $uOrder = "asc")
    {
        $tReturn = [];
        if (count($uArray) === 0) {
            return $tReturn;
        }

        $tValues = [];
        foreach ($uArray as $tKey => $tValue) {
            $tValues[$tKey] = $tValue[$uField];
        }

        if ($uOrder === "desc") {
            arsort($tValues);
        } else {
            asort($tValues);
        }

        foreach (array_keys($tValues) as $tKey) {
            $tReturn[] = $uArray[$tKey];
        }

        return $tReturn;
    }

    /**
     * Categorizes an array by key
     *
     * @param array $uArray        array
     * @param mixed $uKey          key
     * @param bool  $uPreserveKeys preserves keys
     *
     * @return array categorized array
     */
    public static function categorize(array $uArray, $uKey, $uPreserveKeys = false)
    {
        $tReturn = [];
        if (!is_array($uKey)) {
            $uKey = [$uKey];
        }

        foreach ($uArray as $tRowKey => &$tRow) {
            $tRef = & $tReturn;
            foreach ($uKey as $tKey) {
                $tValue = $tRow[$tKey];
                if (!isset($tRef[$tValue])) {
                    $tRef[$tValue] = [];
                }
                $tNewRef = & $tRef[$tValue];
                unset($tRef);
                $tRef = & $tNewRef;
            }

            if ($uPreserveKeys) {
                $tRef[$tRowKey] = $tRow;
            } else {
                $tRef[] = $tRow;
            }
        }

        return $tReturn;
    }

    /**
     * Assigns keys by key
     *
     * @param array $uArray array
     * @param mixed $uKey   key
     *
     * @return array array with new keys
     */
    public static function assignKeys(array $uArray, $uKey)
    {
        $tReturn = [];

        foreach ($uArray as $tRow) {
            $tReturn[$tRow[$uKey]] = $tRow;
        }

        return $tReturn;
    }

    /**
     * Extracts specified column from the array
     *
     * @param array $uArray         array
     * @param mixed $uKey           key
     * @param bool  $uSkipEmpties   whether skip empty entries or not
     * @param bool  $uDistinct      whether returns multiple instances of same entries or not
     *
     * @return array values of the specified column from a multi-dimensional array
     */
    public static function column(array $uArray, $uKey, $uSkipEmpties = false, $uDistinct = false)
    {
        $tReturn = [];

        foreach ($uArray as $tRow) {
            if (isset($tRow[$uKey])) {
                if (!$uDistinct || !in_array($tRow[$uKey], $tReturn)) {
                    $tReturn[] = $tRow[$uKey];
                }
            } else {
                if (!$uSkipEmpties) {
                    $tReturn[] = null;
                }
            }
        }

        return $tReturn;
    }

    /**
     * Extracts specified columns from the array
     *
     * @param array $uArray         array
     * @param array $uKeys          keys
     *
     * @return array values of the specified column from a multi-dimensional array
     */
    public static function columns(array $uArray, ...$uKeys)
    {
        $tReturn = [];

        foreach ($uArray as $tRow) {
            $tReturnRow = [];

            foreach ($uKeys as $tKey) {
                if (isset($tRow[$tKey])) {
                    $tReturnRow[$tKey] = $tRow[$tKey];
                }
            }

            $tReturn[] = $tReturnRow;
        }

        return $tReturn;
    }

    /**
     * Gets the first matching row from a multi-dimensional array
     *
     * @param array $uArray array
     * @param mixed $uKey   key
     * @param mixed $uValue value
     *
     * @return array|bool entire row matches the condition
     */
    public static function getRow(array $uArray, $uKey, $uValue)
    {
        foreach ($uArray as $tRow) {
            if (isset($tRow[$uKey]) && $tRow[$uKey] === $uValue) {
                return $tRow;
            }
        }

        return false;
    }

    /**
     * Gets the first matching row's key
     *
     * @param array $uArray array
     * @param mixed $uKey   key
     * @param mixed $uValue value
     *
     * @return mixed|bool key of row matches the condition
     */
    public static function getRowKey(array $uArray, $uKey, $uValue)
    {
        foreach ($uArray as $tKey => $tRow) {
            if (isset($tRow[$uKey]) && $tRow[$uKey] === $uValue) {
                return $tKey;
            }
        }

        return false;
    }

    /**
     * Gets the matching rows
     *
     * @param array $uArray array
     * @param mixed $uKey   key
     * @param mixed $uValue value
     *
     * @return array set of elements matches the condition
     */
    public static function getRows(array $uArray, $uKey, $uValue)
    {
        $tReturn = [];

        foreach ($uArray as $tKey => $tRow) {
            if (isset($tRow[$uKey]) && $tRow[$uKey] === $uValue) {
                $tReturn[$tKey] = $tRow;
            }
        }

        return $tReturn;
    }

    /**
     * Gets the not matching rows
     *
     * @param array $uArray array
     * @param mixed $uKey   key
     * @param mixed $uValue value
     *
     * @return array set of elements not matches the condition
     */
    public static function getRowsBut(array $uArray, $uKey, $uValue)
    {
        $tReturn = [];

        foreach ($uArray as $tKey => $tRow) {
            if (isset($tRow[$uKey]) && $tRow[$uKey] !== $uValue) {
                $tReturn[$tKey] = $tRow;
            }
        }

        return $tReturn;
    }

    /**
     * Combines two arrays properly
     *
     * @param array $uArray1    first array
     * @param array $uArray2    second array
     *
     * @return array combined array
     */
    public static function combine(array $uArray1, array $uArray2)
    {
        $tArray = [];

        for ($i = 0, $tLen = count($uArray1); $i < $tLen; $i++) {
            if (!isset($uArray2[$i])) {
                $tArray[$uArray1[$i]] = null;
                continue;
            }

            $tArray[$uArray1[$i]] = $uArray2[$i];
        }

        return $tArray;
    }

    /**
     * Combines two or more arrays
     *
     * @param array $uArgs arguments
     *
     * @return array combined array
     */
    public static function combine2(...$uArgs)
    {
        $tArray = [];

        for ($i = 0; true; $i++) {
            $tValues = [];
            $tAllNull = true;

            foreach ($uArgs as $tArg) {
                if (isset($tArg[$i])) {
                    $tAllNull = false;
                    $tValues[] = $tArg[$i];
                    continue;
                }

                $tValues[] = null;
            }

            if ($tAllNull === true) {
                break;
            }

            $tArray[] = $tValues;
        }

        return $tArray;
    }

    /**
     * Sorts an array by priority list
     *
     * @param array $uArray         array
     * @param array $uPriorities    list of priorities
     *
     * @return array sorted array
     */
    public static function sortByPriority(array $uArray, $uPriorities)
    {
        $tArray = [];

        foreach ($uPriorities as $tKey) {
            if (!isset($uArray[$tKey])) {
                continue;
            }

            $tArray[$tKey] = $uArray[$tKey];
        }

        // union of arrays
        return $tArray + $uArray;
    }
}
