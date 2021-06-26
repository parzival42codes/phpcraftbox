<?php

class ContainerHelperView
{

    public static function convertBacktraceView(array $backtraceArray, bool $showArgs = false): string
    {
        $backtraceNewView = '<table class="ViewhelperBacktraceView">
            <tr>
            <th>#</th>
            <th>File</th>
            <th>Line</th >
            <th>Class</th>
            <th>Type</th>
            <th>Function</th>
            ' . (($showArgs === false) ? '' : '<th>Args</th>') . '
            </tr>
            ';

        $i = 0;

        foreach ($backtraceArray as $backtrace) {
            $backtraceNewView .= '<tr>
                    <td>' . $i++ . '</td>
                    <td>' . ((isset($backtrace['file']) === true) ? ContainerFactoryFile::getFilenameWrap($backtrace['file']) : '') . '</td>
                    <td>' . ((isset($backtrace['line']) === true) ? $backtrace['line'] : '') . '</td>
                    <td>' . ((isset($backtrace['class']) === true) ? $backtrace['class'] : '') . '</td>
                    <td>' . ((isset($backtrace['type']) === true) ? $backtrace['type'] : '') . '</td>
                    <td>' . ((isset($backtrace['function']) === true) ? strtr($backtrace['function'],
                                                                              [
                                                                                  '{' => '&#123;',
                                                                                  '}' => '&#125;',
                                                                              ]) : '') . '</td>
                    ' . (($showArgs === false) ? '' : '<td><span style="overflow: auto;"><details><summary>Args</summary>' . strtr(htmlentities(var_export($backtrace['args'] ?? null,
                                                                                                                                                           true)),
                                                                                                                                   [
                                                                                                                                                                                                                                '{' => '&#123;',
                                                                                                                                                                                                                                '}' => '&#125;',
                                                                                                                                                                                                                            ]) . '</details><span></td>') . '
                    </tr>';
        }


        $backtraceNewView .= '</table>';

        return $backtraceNewView;

    }
}
