<?php

class ContainerHelperDatetime
{

    public static function getLocaleDate($date)
    {

        if (!$date instanceof DateTime) {
            $dateTime = new DateTime($date);
        } else {
            $dateTime = $date;
        }

        if(empty($date)) {
            return '';
        }

        return strftime((string)Config::get('/environment/datetime/locale'),
                        $dateTime->getTimestamp());
    }

    public static function calculateDifference($dateStart, $dateEnd): array
    {
        if (!is_object($dateStart)) {
            $dateStart = new \DateTime($dateStart);
        }

        if (!is_object($dateEnd)) {
            $dateEnd = new \DateTime($dateEnd);
        }

        $dateDiff = $dateStart->diff($dateEnd);

        $dateArray = [
            'y',
            'm',
            'd',
            'h',
            'i',
            's'
        ];

        $output = [];
        foreach ($dateArray as $dateArrayValue) {
            $output[$dateArrayValue] = $dateDiff->$dateArrayValue;
        }
        return $output;
    }

}
