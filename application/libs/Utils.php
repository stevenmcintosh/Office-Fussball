<?php

/**
 * This is the "base controller class". All other "real" controllers extend this class.
 */
class Utils {

    /**
     * ConvertDateForwardSlashesToMysql()
     * Example 01/12/2014 to 2014-12-01
     * @param string $dateInput
     */
    public static function ConvertDateForwardSlashesToMysql($dateInput) {
        $dateBits = explode('/', $dateInput);
        return $dateBits[2] . "-" . $dateBits[1] . "-" . $dateBits[0];
    }

    /**
     * ConvertMysqlDateToReadableSlashes()
     * Example 2014-12-01 to 01/12/2014
     * @param string $dateInput
     */
    public static function ConvertMysqlDateToReadable($dateInput) {
        $dateBits = explode('-', $dateInput);
        return $dateBits[2] . "/" . $dateBits[1] . "/" . $dateBits[0];
    }

    /**
     * Return array of each date from ()
     * Example 2014-12-01 to date[day] = 01, date[month] = 12, date[year] = 2014, 
     * @param string $dateInput
     * @param string $splitChar
     */
    public static function ConvertMysqlDateToArray($dateInput, $splitChar) {
        $dateBits = explode($splitChar, $dateInput);
        $date = array();
        $date['day'] = $dateBits[2];
        $date['month'] = $dateBits[1];
        $date['year'] = $dateBits[0];
        return $date;
    }

    public static function generateRandomPassword($length = 10) {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array();
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    /**
     * Returns the percentage of an amount
     * eg getPercentageFromAmount(50, 150) returns 75
     * Enter description here ...
     * @param unknown_type $percentage
     * @param unknown_type $amount
     */
    public static function getPercentageFromAmount($percentage, $amount) {
        return number_format(($percentage / 100) * $amount, 2);
    }

    public static function getPercentageFromTwoAmounts($val1, $val2, $decimalPlaces) {
        return number_format(( $val1 / $val2) * 100, $decimalPlaces);
    }

    /**
     * getObjectDifferencs($obj1, $obj2);
     * @param object $obj1
     * @param object $obj2
     * Returns a list of the array key and values that differ between two objects
     * Ideal for keeping a log of edits by passing in the original obj and the 
     * new obj. 
     * Only the values which can be cast to a string are compared. If the array value
     * is an object itself ,then its not compared.
     * Example:
     * getObjectDifferencs($courseModel, $newCourseModel);
     * If only the course name is changed from test1 to test2, then the returned value is:
     * // name=test1->test2
     * If course name and active is changed from test1 to test2 and active n to y:
     * // name=testxx->test1 | active=n->y
     */
    public static function getObjectDifferencs($obj1, $obj2) {

        $objTmp1 = array();
        $objTmp2 = array();

        foreach ($obj1 as $key => $value) {
            if (self::canBeConvertedToString($value)) {
                $objTmp1[$key] = (string) $value;
            }
        }

        foreach ($obj2 as $key => $value) {
            if (self::canBeConvertedToString($value)) {
                $objTmp2[$key] = (string) $value;
            }
        }


        $a1 = (array) $objTmp1;
        $a2 = (array) $objTmp2;
        $onlyInFirst = array_diff_assoc($a1, $a2);
        $onlyInSecond = array_diff_assoc($a2, $a1);
//print_r($onlyInFirst);
        $edits = '';

        foreach ($onlyInSecond as $key => $val) {
            $edits .= $key . "=" . $val . "->" . $onlyInFirst[$key] . " | ";
        }

        return $edits;
    }

    /**
     * getNewEntry($array)
     * @param array $array
     * The purpose of this is to return a readable format of the varibles used
     * in the $sql_array when adding a new record.
     * Example:
     * $sql_array = array(':name' => $this->name, ':club_id' => $this->club_id);
     * getNewEntry($sql_array) // returns :name->sasas | :club_id->51
     */
    public static function getNewEntry($array) {
        $newEntry = '';
        foreach ($array as $key => $val) {
            $newEntry .= $key . "->" . $val . " | ";
        }
        return $newEntry;
    }

    public static function canBeConvertedToString($item) {
        $returnVal = false;
        if (
                (!is_array($item) ) &&
                ( (!is_object($item) && settype($item, 'string') !== false ) ||
                ( is_object($item) && method_exists($item, '__toString') ) )
        ) {
            $returnVal = true;
        }
        return $returnVal;
    }

}
