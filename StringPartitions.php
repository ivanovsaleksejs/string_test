<?php

/**
 * Returns possible partitions of (sub)string
 * where all substrings are not shorter than given minimal length
 *
 * Result is list of possible partitions
 *
 * e. g.
 *
 * partitions(10, 4) =
 * [
 *    [10]
 *    [4, 6]
 *    [5, 5]
 *    [6, 4]
 * ]
 *
 * @param integer $length
 * @param integer $min
 *
 * @return array
 * @example: partitions(10, 4)
 **/
function partitions($length, $min)
{
    return array_merge(
        /**
         * always return whole length as one of results
         **/
        [[$length]],

        /**
         * Check if string can be split
         **/
        $length >= 2 * $min
            /**
             * Lift up one nesting level
             * [[[], []],[[]]] -> [[], [], []]
             * because we want to have all results as one list
             **/
            ? call_user_func_array("array_merge",
                array_map(function($elem) use ($length, $min)
                    {

                        /**
                         * Poor man's unshift
                         * because php's unshift doesn't return a new array
                         * and I don't want to use intermediate variable.
                         * This code combines a substring of length $elem with all possible
                         * partitions of the rest of string ($length - $elem)
                         **/
                        return array_map(function($arr) use ($elem)
                            {
                                return array_merge([$elem], $arr);
                            },
                            partitions($length - $elem, $min)
                        );

                    },
                    range($min, $length - $min)
                )
            )

            /**
             * No possible splits
             **/
            : []
    );
}

/**
 * Returns a string partitioned by given partitioning pattern
 *
 * e. g.
 * splitString("asdfqwerzx", [4, 6]) =
 * ["asdf", "qwerzx"]
 *
 * @param string $str
 * @param array  $partition
 *
 * @return array
 * @example: splitString("asdfqwerzx", [4, 6])
 **/
function splitString($str, $partition)
{
    /**
     * This would look better with simple foreach
     **/
    return array_reduce($partition, function($c, $p)
        {
            return [
                'l' => array_merge($c['l'], [substr($c['s'], 0, $p)]),
                's' => substr($c['s'], $p)
            ];
        },
        [
            'l' => [],
            's' => $str
        ]
    )['l'];
};

/**
 * Returns all possible partitions of string
 *
 * e. g.
 * processString("asdfqwerzx", 4) =
 * [
 *   ["asdfqwerzx"]
 *   ["asdf","qwerzx"]
 *   ["asdfq","werzx"]
 *   ["asdfqw","erzx"]
 * ]
 *
 * @param string  $str
 * @param integer $min
 *
 * @return array
 * @example: splitString("asdfqwerzx", [4, 6])
 **/
function processString($str, $min)
{
    /**
     * Get all partitions and split string using them
     **/
    return array_map(function($p) use ($str)
        {
            return splitString($str, $p);
        },
        partitions(strlen($str), $min)
    );
}

/**
 * Helper function
 * Echoes all possible partitions of a string with given minimal length of substrings
 *
 * @param string  $str
 * @param integer $min
 *
 * @return void
 * @example: echoStrings(("asdfqwerzx", 4)
 **/
function echoStrings($str, $min)
{
    array_map(function($elem)
        {
            echo json_encode($elem) . "\n";
        },
        processString($str, $min)
    );
}
