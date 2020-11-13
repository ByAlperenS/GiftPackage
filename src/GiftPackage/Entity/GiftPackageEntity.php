<?php

/*
 * Author: ByAlperenS
 * Contact:
 *   - Messenger: Alperen Sancak
 *   - Facebook: Alperen Sancak
 *   - Discord: ByAlperenS#5361
 *
 * Turkey - 2020
 *
 */

namespace GiftPackage\Entity;

use GiftPackage\GiftPackage;
use pocketmine\entity\Human;
use pocketmine\entity\Skin;

class GiftPackageEntity extends Human{

    public $height = 1.8;
    public $width = 0.6;

    public function setSkin(Skin $skin) : void{
        parent::setSkin(new Skin($skin->getSkinId(), $skin->getSkinData(), '', 'geometry.giftpackage', file_get_contents(GiftPackage::getInstance()->getDataFolder() . "giftpackage.json")));
    }
}
