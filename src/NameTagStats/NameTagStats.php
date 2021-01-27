<?php
/** @noinspection PhpUnused */

declare(strict_types=1);
namespace NameTagStats;


use _64FF00\PureChat\PureChat;
use luca28pet\PreciseCpsCounter\Main;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;


class NameTagStats extends PluginBase implements Listener,Data
{
    /** @var array  */
    public $platform = [];

    /**
     * @var PureChat
     */
    public $pure_chat;
    /**
     * @var Main
     */
    public $cps_counter;

    public function onEnable()
    {

        $this->pure_chat = $this->getServer()->getPluginManager()->getPlugin("PureChat");
        $this->cps_counter = $this->getServer()->getPluginManager()->getPlugin("PreciseCpsCounter");
        if ($this->pure_chat === null){
            $this->getLogger()->critical("You need to PureChat plugin...");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
        if ($this->cps_counter === null){
            $this->getLogger()->critical("You need to PreciseCpsCounter plugin...");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        $this->getScheduler()->scheduleRepeatingTask(new class($this) extends Task {
            public $plugin;

            public function __construct(NameTagStats $plugin)
            {
                $this->plugin = $plugin;
            }

            public function onRun(int $currentTick)
            {
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player){
                    $player->setNameTag($this->plugin->pure_chat->getNametag($player)."\n"."CPS: ".$this->plugin->cps_counter->getCps($player)."     Ping: ".$player->getPing()."      Platform: ".($this->plugin::INPUT[$this->plugin->platform[$player->getName()]] ?? "Unknown")."\n      HP: ".self::getProgress((int)$player->getHealth(),(int)$player->getMaxHealth())."\n Food: ".$player->getFood()."/".$player->getMaxFood()."    Item: ".($player->getInventory()->getItemInHand()->getName() ?? "Empty"));

                }
            }
            public static function getProgress(int $progress, int $size): string {
                $divide = $size > 750 ? 50 : ($size > 500 ? 20 : ($size > 300 ? 15 : ($size > 200 ? 10 : ($size > 100 ? 5 : 3)))); // for short bar
                $percentage = number_format(($progress / $size) * 100, 2);
                $progress = (int) ceil($progress / $divide);
                $size = (int) ceil($size / $divide);
                return TextFormat::GRAY . "[" . TextFormat::GREEN . str_repeat("|", $progress) .
                    TextFormat::RED . str_repeat("|", $size - $progress) . TextFormat::GRAY . "] " .
                    TextFormat::AQUA . "{$percentage} %%";
            }
        },20);
        $this->getLogger()->info("Plugin activated");
    }

    public function dataPacket(DataPacketReceiveEvent $event)
    {
        $pk = $event->getPacket();
        if ($pk instanceof LoginPacket) {
            $cd = $pk->clientData;
            $this->platform[$pk->username] = $cd["CurrentInputMode"];
        }
    }
}