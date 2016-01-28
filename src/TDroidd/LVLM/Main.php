<?php

#    _                           _
#   ( )                         (_ )          /'\_/`\
#   | |       __   _   _    __   | |          |     |   _ _   ___     _ _    __     __   _ __
#   | |  _  /'__`\( ) ( ) /'__`\ | |          | (_) | /'_` )/' _ `\ /'_` ) /'_ `\ /'__`\( '__)
#   | |_( )(  ___/| \_/ |(  ___/ | |          | | | |( (_| || ( ) |( (_| |( (_) |(  ___/| |
#   (____/'`\____)`\___/'`\____)(___)         (_) (_)`\__,_)(_) (_)`\__,_)`\__  |`\____)(_)
#                                                                         ( )_) |

namespace TDroidd\LVLM;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat;
use TDroidd\LVLM\Commands;
use pocketmine\command\CommandMap;
use pocketmine\command\CommandExecutor;

class Main extends PluginBase implements Listener{

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info(TextFormat::GREEN . "LevelManager Enabled!");
        $this->getCommand("levelmanager")->setExecutor(new Commands($this));
        //$this->getServer()->getCommandMap()->register("levelmanager", new Commands($this));
    }

    public function addLevel($levelname)
    {
        $this->getLogger()->notice("Generating level " . $levelname);
        $this->getServer()->broadcastMessage(TextFormat::GREEN . "Level " . $levelname . " Generated!");
        return $this->getServer()->generateLevel($levelname);
    }

    public function removeLevel($name, $sender)
    {
        $path = $this->getServer()->getDataPath() . "worlds/" . $name;
        if (!file_exists($path)) {
            return true;
        }
        if (!is_dir($path)){
            return unlink($path);
        }
        return rmdir($path);
    }
}