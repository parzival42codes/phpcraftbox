<?php

class ContainerHelperData
{

    public static function gzip(string $content, int $level = 1): string
    {
//        $contentCRC   = crc32($content);
//        $contentLengh = mb_strlen($content);
//        $content      = "\x1f\x8b\x08\x00\x00\x00\x00\x00" . gzcompress($content, $level);
//        $content      = substr($content, 0, -4);
//        $content      .= pack("VV", $contentCRC, $contentLengh);
//        return $content;

        $size   = strlen($content);
        $gzdata = "\x1f\x8b\x08\x00\x00\x00\x00\x00";
        $gzdata .= substr(gzcompress($content,
                                     $level),
                          0,
                          -4);
        $gzdata .= pack('V',
                        crc32($content));
        $gzdata .= pack('V',
                        $size);

        return $gzdata;

    }

}
