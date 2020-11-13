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

namespace GiftPackage\Event;

use GiftPackage\{Entity\GiftPackageEntity, GiftPackage, Utils};
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\level\particle\HugeExplodeParticle;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\{IntTag, StringTag, CompoundTag, ByteArrayTag};
use pocketmine\utils\TextFormat as C;

class EventListener implements Listener{

    /** @var GiftPackage */
    private $plugin;

    public function __construct(GiftPackage $plugin){
        $this->plugin = $plugin;
    }

    public function blockPlaceEvent(BlockPlaceEvent $e){
        $p = $e->getPlayer();
        $item = $e->getItem();
        $block = $e->getBlock();

        if ($item->getId() == Item::CHEST and $item->getCustomName() == C::GOLD . "Gift Package" and $item->getLore() == ["GiftPackage"]){
            $path = Server::getInstance()->getDataPath() . "plugin_data/GiftPackage/Texture/giftpackage.png";
            $skin = Utils::getSkinFromFile($path);
            $nbt = Entity::createBaseNBT(new Vector3($block->getX(), $block->getY() + 1, $block->getZ()));
            $nbt->setTag(new CompoundTag("Skin", [
                new StringTag("Data", $skin->getSkinData()),
                new StringTag("Name", "giftpackage"),
                new StringTag("GeometryName", "geometry.giftpackage"),
                new ByteArrayTag("GeometryData", file_get_contents($this->plugin->getDataFolder() . "Model/giftpackage.json"))
            ]));
            $nbt->setTag(new IntTag("position"));
            $nbt->setTag(new StringTag("player", "0"));
            $npc = new GiftPackageEntity($p->getLevel(), $nbt);
            $npc->spawnToAll();
            $e->setCancelled();
            $p->getInventory()->removeItem(Item::get($item->getId(), $item->getDamage(), 1));
        }
    }

    public function entityDamageEvent(EntityDamageEvent $e)
    {
        if (!$e->isCancelled()){
            if ($e instanceof EntityDamageByEntityEvent) {
                $entity = $e->getEntity();
                $config = $this->plugin->configGet();
                $items = $config->get("Items");
                $p = $e->getDamager();
                if ($entity instanceof GiftPackageEntity) {
                    if ($p instanceof Player) {
                        $e->setCancelled();
                        $world = $entity->getLevel()->getName();
                        if (!Server::getInstance()->isLevelLoaded($world)) {
                            Server::getInstance()->loadLevel($world);
                        }
                        $p->getLevel()->addSound(new EndermanTeleportSound($entity->asVector3()));

                        $p->getLevel()->addParticle(new HugeExplodeParticle($entity->asVector3()));

                        $itemdetails = $items[array_rand($items)];
                        $details = explode(":", $itemdetails);
                        $id = (int)$details[0];
                        $meta = (int)$details[1];
                        $count = (int)$details[2];
                        if (isset($details[3])) {
                            $newitem = Item::get($id, $meta, $count)->setCustomName($details[3]);
                        } else {
                            $newitem = Item::get($id, $meta, $count);
                        }
                        $entity->getLevel()->dropItem($entity->asVector3(), $newitem);
                        $entity->close();
                    }
                }
            }
        }
    }
}
