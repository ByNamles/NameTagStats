<?php

namespace NameTagStats\task;

use NameTagStats\interface\Data;
use NameTagStats\NameTagStats;
use NameTagStats\utils\Utils;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class NameTagStatsTask extends Task implements Data
{

    public function onRun(): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player){
            $player->setNameTag(NameTagStats::getInstance()->pure_chat->getNametag($player) . "\n".
                "CPS: ".NameTagStats::getInstance()->cps_counter->getCps($player)."     Ping: ".
                $player->getNetworkSession()->getPing().
                "      Platform: ".(self::INPUT[$this->plugin->platform[$player->getName()]] ?? "Unknown").
                "\n      HP: ".Utils::getProgress((int)$player->getHealth(),$player->getMaxHealth()).
                "\n Food: ".$player->getHungerManager()->getFood()."/".$player->getHungerManager()->getMaxFood().
                "    Item: ".($player->getInventory()->getItemInHand()->getName() ?? "Empty"));

        }
    }

}