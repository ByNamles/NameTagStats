<?php

use NameTagStats\NameTagStats;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\JwtUtils;

class NameTagStatsListener implements Listener
{

    public function dataPacket(DataPacketReceiveEvent $event)
    {
        $pk = $event->getPacket();
        if ($pk instanceof LoginPacket) {
            $cd = JwtUtils::parse($pk->clientDataJwt)[1];
            NameTagStats::getInstance()[$cd["ThirdPartyName"]] = $cd["CurrentInputMode"];
        }
    }

}