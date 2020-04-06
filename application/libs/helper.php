<?php

class Helper {

    /**
     * debugPDO
     *
     * Shows the emulated SQL query in a PDO statement. What it does is just extremely simple, but powerful:
     * It combines the raw query and the placeholders. For sure not really perfect (as PDO is more complex than just
     * combining raw query and arguments), but it does the job.
     * 
     * @author Panique
     * @param string $raw_sql
     * @param array $parameters
     * @return string
     */
    static public function debugPDO($raw_sql, $parameters) {

        $keys = array();
        $values = $parameters;

        foreach ($parameters as $key => $value) {

            // check if named parameters (':param') or anonymous parameters ('?') are used
            if (is_string($key)) {
                $keys[] = '/' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

            // bring parameter into human-readable format
            if (is_string($value)) {
                $values[$key] = "'" . $value . "'";
            } elseif (is_array($value)) {
                $values[$key] = implode(',', $value);
            } elseif (is_null($value)) {
                $values[$key] = 'NULL';
            }
        }

        /*
          echo "<br> [DEBUG] Keys:<pre>";
          print_r($keys);

          echo "\n[DEBUG] Values: ";
          print_r($values);
          echo "</pre>";
         */

        $raw_sql = preg_replace($keys, $values, $raw_sql, 1, $count);

        return $raw_sql;
    }

    // DISPLAYS COMMENT POST TIME AS "1 year, 1 week ago" or "5 minutes, 7 seconds ago", etc...
    public static function time_passed($date, $granularity = 2) {
        $retval = '';
        $date = strtotime($date);
        $difference = time() - $date;
        $periods = array('decade' => 315360000,
            'year' => 31536000,
            'month' => 2628000,
            'week' => 604800,
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1);
        if ($difference < 30) { // less than 5 seconds ago, let's say "just now"
            $retval = "just now";
            return $retval;
        } else {
            foreach ($periods as $key => $value) {
                if ($difference >= $value) {
                    $time = floor($difference / $value);
                    $difference %= $value;
                    $retval .= ($retval ? ' ' : '') . $time . ' ';
                    $retval .= (($time > 1) ? $key . 's' : $key);
                    $granularity--;
                }
                if ($granularity == '0') {
                    break;
                }
            }
            return ' posted ' . $retval . ' ago';
        }
    }
    
    
    public static function ActiveArray() {
        return array('0' => 'off', '1' => 'on');
    }

}
