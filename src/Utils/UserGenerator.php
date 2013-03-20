<?php
/**
 * User: mykola
 * Date: 3/19/13
 * Time: 2:11 PM
 */

namespace Utils;

class UserGenerator {

    private static function getNames() {
        $url = 'https://raw.github.com/hadley/data-baby-names/master/baby-names.csv';
        $options = array(
            'https' => array(
                'method' => 'GET'
            )
        );
        $context = stream_context_create($options);
        $csvNames = file($url, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES, $context);
        $resultNames = array();
        $skipLine = True;
        foreach($csvNames as $line) {
            if ($skipLine === True) {
                $skipLine = False;
                continue;
            }
            $lineValues = explode(',', $line);
            array_push($resultNames, trim(strtolower($lineValues[1]), '"'));
        }
        return $resultNames;
    }

    public static function makeUsers() {
        $names = self::getNames();
        foreach ($names as $name) {
            $user = new \Models\User(
                null,
                $name,
                $name,
                "Lorem ipsum dolor sit amet $name consectetur adipisicing elit.",
                rand(0, 5)
            );
            $user->save();
            if (DEBUG === True) {
                echo "Saved user $user <br />";
            }
        }
    }
}
