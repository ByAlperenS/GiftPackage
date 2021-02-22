<?php

namespace ByAlperenS\GiftPackage\Entity;

use ByAlperenS\GiftPackage\Main;
use pocketmine\entity\Human;
use pocketmine\entity\Skin;

class GiftPackageEntity extends Human{

    public $height = 1.8;
    public $width = 0.6;

    public function setSkin(Skin $skin) : void{
        parent::setSkin(new Skin($skin->getSkinId(), $skin->getSkinData(), '', 'geometry.giftpackage', file_get_contents(Main::getInstance()->getDataFolder() . "giftpackage.json")));
    }
}
