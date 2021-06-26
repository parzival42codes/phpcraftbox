<?php

class ContainerHelperCalculate
{

    public static function calculateMemoryBytes(int $val, int $round = 2, bool $long = false): string
    {

        $negative = false;
        if ($val < 0) {
            $val      = $val * -1;
            $negative = true;
        }

        if ($long === false) {
            return (($negative === false) ? '' : '-') . (($val < 1024) ? $val . " B" : (($val < 1048576) ? round($val / 1024,
                                                                                                                 $round) . "&nbsp;Kb" : (($val < 1073741824) ? round($val / 1048576,
                                                                                                                                                                     $round) . "&nbsp;Mb" : (($val < 1099511627776) ? round($val / 1073741824,
                                                                                                                                                                                                                            $round) . "&nbsp;Gb" : round($val / 1099511627776,
                                                                                                                                                                                                                                                         2) . "&nbsp;Tb"))));
        }
        else {
            return (($negative === false) ? '' : '-') . (($val < 1024) ? $val . " Bytes" : (($val < 1048576) ? round($val / 1024,
                                                                                                                     $round) . "&nbsp;KiloBytes" : (($val < 1073741824) ? round($val / 1048576,
                                                                                                                                                                                $round) . "&nbsp;MegaBytes" : (($val < 1099511627776) ? round($val / 1073741824,
                                                                                                                                                                                                                                              $round) . "&nbsp;GigaBytes" : round($val / 1099511627776,
                                                                                                                                                                                                                                                                                  2) . "&nbsp;TeraBytes"))));
        }
    }

    public static function calculateMicroTime(int $val, int $round = 4): int
    {
        return round(($val * 1000),
            $round);
    }

    public static function calculateMicroTimeDisplay(float $val, int $decimals = 2, string $dec_point = ',', string $thousands_sep = '.'): string
    {
        return number_format((float)($val * 1000),
                             $decimals,
                             $dec_point,
                             $thousands_sep) . '&nbsp;ms';
    }

    public static function calculateMicroTimeDisplaySeconds(int $val, int $decimals = 2, string $dec_point = ',', string $thousands_sep = '.'): int
    {
        return number_format(($val / 1000),
                $decimals,
                $dec_point,
                $thousands_sep) . ' sec.';
    }

}
