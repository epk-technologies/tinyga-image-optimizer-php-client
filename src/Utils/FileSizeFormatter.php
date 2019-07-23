<?php
namespace Tinyga\ImageOptimizer\Utils;


class FileSizeFormatter
{
    /**
     * @param int $size_in_bytes
     * @param int $round_precision
     * @return string
     */
    public static function formatFileSize($size_in_bytes, $round_precision = 2)
    {
        $size_in_bytes = (int)$size_in_bytes;
        if($size_in_bytes < 1024){
            return "{$size_in_bytes} B";
        }

        $size_in_bytes /= 1024;
        if($size_in_bytes < 1024){
            return round($size_in_bytes, (int)$round_precision) . ' KB';
        }

        $size_in_bytes /= 1024;
        if($size_in_bytes < 1024){
            return round($size_in_bytes, (int)$round_precision) . ' MB';
        }

        $size_in_bytes /= 1024;
        return round($size_in_bytes, (int)$round_precision) . ' GB';
    }
}
