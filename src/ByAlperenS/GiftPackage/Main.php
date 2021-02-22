<?php

namespace ByAlperenS\GiftPackage;

use ByAlperenS\GiftPackage\Entity\GiftPackageEntity;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use ByAlperenS\GiftPackage\Event\EventListener;
use ByAlperenS\GiftPackage\Command\GiftPackageCommand;
use pocketmine\utils\Config;
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase{

    /** @var static */
    private static $instance;

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getCommandMap()->register("giftpackage", new GiftPackageCommand($this));
        @mkdir($this->getDataFolder());
        @mkdir($this->getDataFolder() . "Model/");
        @mkdir($this->getDataFolder() . "Texture/");
        $this->saveResource("config.yml");
        $this->saveResource("Model/giftpackage.json");
        $this->saveResource("Texture/giftpackage.png");
        Entity::registerEntity(GiftPackageEntity::class, true);
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        if (!$config->get("Items")){
            $this->getLogger()->alert("config.yml > Items Are Empty !");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
        $economy = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if (!$economy instanceof EconomyAPI) {
            $this->getLogger()->alert("You need EconomyAPI");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    public function onLoad(){
        self::$instance = $this;
    }

    public static function getInstance(): Main{
        return self::$instance;
    }

    public function configGet(){
        return new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }
    public function economyGet(){
        return EconomyAPI::getInstance();
    }
}
