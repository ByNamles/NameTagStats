<?php
/** @noinspection PhpUnused */

declare(strict_types=1);
namespace NameTagStats;


use _64FF00\PureChat\PureChat;
use luca28pet\PreciseCpsCounter\Main;
use NameTagStats\interface\Data;
use NameTagStats\task\NameTagStatsTask;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;


class NameTagStats extends PluginBase implements Listener,Data
{
    /** @var array  */
    public array $platform = [];

    /**
     * @var PureChat
     */
    public PureChat $pure_chat;
    /**
     * @var Main
     */
    public Main $cps_counter;

    /** @var NameTagStats  */
    private static NameTagStats $instance;

    public function onLoad(): void
    {
        self::$instance = $this;
    }

    /** @noinspection PhpFieldAssignmentTypeMismatchInspection
     * @noinspection PhpConditionAlreadyCheckedInspection
     */
    public function onEnable(): void
    {

        $this->pure_chat = $this->getServer()->getPluginManager()->getPlugin("PureChat");
        $this->cps_counter = $this->getServer()->getPluginManager()->getPlugin("PreciseCpsCounter");
        if ($this->pure_chat === null){
            $this->getLogger()->critical("You need to PureChat plugin...");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
        if (!($this->cps_counter === null)){
            $this->getLogger()->critical("You need to PreciseCpsCounter plugin...");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        $this->getScheduler()->scheduleRepeatingTask(new NameTagStatsTask(),20);
        $this->getLogger()->info("Plugin activated");
    }

    /**
     * @return NameTagStats
     */
    public static function getInstance(): NameTagStats
    {
        return self::$instance;
    }


}