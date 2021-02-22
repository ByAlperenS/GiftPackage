<?php

namespace ByAlperenS\GiftPackage\Command;

use ByAlperenS\GiftPackage\Main;
use pocketmine\command\{PluginCommand, CommandSender};
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;

class GiftPackageCommand extends PluginCommand{

    /** @var Main */
    private $plugin;

    public function __construct(Main $plugin){
        parent::__construct("giftpackage", $plugin);
        $this->setAliases(['gp']);
        $this->setDescription("Gift Package Commands");
        $this->setUsage("/gp help");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $p, string $commandLabel, array $args){
        $config = $this->plugin->configGet();
        $plugintitle = $config->get("Plugin-Title");
        if (!$p instanceof Player){
            $p->sendMessage("Please Use This Command In-Game !");
            return false;
        }
        if (isset($args[0])){
            switch ($args[0]){
                case "help":
                    $p->sendMessage(C::GRAY . "[ " . C::RED . "GiftPackage" . C::GRAY . " ]");
                    $p->sendMessage(C::GRAY . "/giftpackage help");
                    $p->sendMessage(C::GRAY . "/giftpackage buy [Count]");
                    break;
                case "buy":
                     $cost = $config->get("GiftPackage-Cost");
                    if (isset($args[1])){
                        if (!is_numeric($args[1])){
                            $p->sendMessage(str_replace("{title}", $plugintitle, $config->get("No-Numerical-Message")));
                            return false;
                        }
                        if ($this->plugin->economyGet()->myMoney($p) < $args[1] * $cost){
                            $p->sendMessage(str_replace("{title}", $plugintitle, $config->get("No-Money-Message")));
                            return false;
                        }
                        $this->plugin->economyGet()->reduceMoney($p, $args[1] * $cost);
                        $item = ItemFactory::get(Item::CHEST);
                        $item->setCustomName(C::GOLD . "Gift Package");
                        $item->setLore(["GiftPackage"]);
                        $item->setCount($args[1]);
                        $p->getInventory()->addItem($item);
                        $p->sendMessage(str_replace("{title}", $plugintitle, $config->get("Buy-Item-Message")));
                    }else{
                        $p->sendMessage(C::GRAY . $this->getUsage());
                    }
                    break;
            }
        }else{
            $p->sendMessage(C::GRAY . $this->getUsage());
        }
        return true;
    }
}
