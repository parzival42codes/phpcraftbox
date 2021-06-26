<?php

class ContainerHelperCode
{
    protected static array $viewArrayAsTable = [];

    public static function viewArrayAsTable(array $array): string
    {
        self::$viewArrayAsTable = [];
        if (is_array($array)) {
            self::viewArrayAsTableConvertHelper($array);
        }

        $cellStyle = 'border: 1px #000 solid; background: #FFF; color: #000;padding: 0.2em 0.2em 0.2em 0.5em;';
        $table     = '<table ' . ((count(self::$viewArrayAsTable)) > 0 ? '' : 'data-empty="1"') . ' style="width: 100%;' . $cellStyle . '"><tr><td style="font-weight: bold;' . $cellStyle . '">Key</td><td style="font-weight: bold;width: 75%;' . $cellStyle . '">Value</td></tr>';
        foreach (self::$viewArrayAsTable as $key => $value) {

            if (gettype($value) !== 'string') {
                if (gettype($value) === 'object') {
                    $value = '*** ' . gettype($value) . ': ' . get_class($value) . ' ***';
                }
                elseif (gettype($value) === 'integer') {
                    $value = '*** ' . gettype($value) . ': ' . $value . ' ***';
                }
                else {
                    $value = '*** ' . gettype($value) . ' ***';
                }
            }

            $table .= '<tr><td data-row="key" style="' . $cellStyle . '">' . $key . '</td><td data-row="value" style="display: block;width: 99%;white-space:pre;overflow: auto;max-height: 15em;' . $cellStyle . '">' . htmlentities($value) . '</td></tr>';
        }
        $table .= '</table>';
        return $table;
    }

    protected static function viewArrayAsTableConvertHelper(array $array, string $path = '', string $separator = '&nbsp;/&nbsp;'): void
    {
        foreach ($array as $key => $item) {
            if (is_array($item) === true) {
                self::viewArrayAsTableConvertHelper($item,
                                                    $path . $separator . $key,
                                                    $separator);
            }
            else {
                self::$viewArrayAsTable[$path . $separator . $key] = $item;
            }
        }
    }

    public static function viewArrayAsString(array $array, string $separator = ''): array
    {
        self::$viewArrayAsTable = [];
        self::viewArrayAsTableConvertHelper($array,
                                            '',
                                            $separator);
        return self::$viewArrayAsTable;
    }

}
