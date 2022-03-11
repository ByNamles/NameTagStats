<?php

namespace NameTagStats\utils;

use pocketmine\utils\TextFormat;

final class Utils
{

    public static function getProgress(int $progress, int $size): string
    {
        $divide = $size > 750 ? 50 : ($size > 500 ? 20 : ($size > 300 ? 15 : ($size > 200 ? 10 : ($size > 100 ? 5 : 3)))); // for short bar
        $percentage = number_format(($progress / $size) * 100, 2);
        $progress = (int)ceil($progress / $divide);
        $size = (int)ceil($size / $divide);
        return TextFormat::GRAY . "[" . TextFormat::GREEN . str_repeat("|", $progress) .
            TextFormat::RED . str_repeat("|", $size - $progress) . TextFormat::GRAY . "] " .
            TextFormat::AQUA . "$percentage %%";
    }

}