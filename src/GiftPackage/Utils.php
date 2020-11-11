<?php

/*
 * Author: ByAlperenS
 * Contect:
 *   - Messenger: Alperen Sancak
 *   - Facebook: Alperen Sancak
 *   - Discord: ByAlperenS#5361
 *
 * Turkey - 2020
 *
 */

namespace GiftPackage;

use pocketmine\entity\Skin;

class Utils{

    public static function getSkinFromFile(string $path) : ?Skin{
        $img = @imagecreatefrompng($path);
        $bytes = '';
        $l = (int) @getimagesize($path)[1];
        for($y = 0; $y < $l; $y++){
            for($x = 0; $x < 64; $x++){
                $rgba = @imagecolorat($img, $x, $y);
                $a = ((~((int)($rgba >> 24))) << 1) & 0xff;
                $r = ($rgba >> 16) & 0xff;
                $g = ($rgba >> 8) & 0xff;
                $b = $rgba & 0xff;
                $bytes .= chr($r) . chr($g) . chr($b) . chr($a);
            }
        }
        @imagedestroy($img);
        return new Skin("giftpackage", $bytes);
    }
}