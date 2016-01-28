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

class Main extends PluginBase implements Listener
{

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info(TextFormat::GREEN . "LevelManager Enabled!");
        $this->getCommand("levelmanager")->setExecutor(new Commands($this));
    }

    public function addLevel($levelname)
    {
        $this->getLogger()->notice("Generating level " . $levelname);
        $this->getServer()->broadcastMessage(TextFormat::GREEN . "Level " . $levelname . " Generated!");
        return $this->getServer()->generateLevel($levelname);
    }

    public function removeLevel($name){
        $path = $this->getServer()->getDataPath() . "worlds/" . $name;
        $level = $this->getServer()->getLevelByName($name);
        if ($level instanceof Level) {
            $this->getServer()->unloadLevel($level);
        }
        self::file_delDir($path);
        $this->getServer()->broadcastMessage(TextFormat::GREEN . "World " . $name . " deleted!");
    }

    public function loadWorld($name){
        if (!$this->getServer()->isLevelGenerated($name)) {
            $this->getServer()->broadcastMessage(TextFormat::RED . "The level does not exist");
            return false;
        } elseif (!$this->getServer()->isLevelLoaded($name)){
            $this->getServer()->broadcastMessage(TextFormat::YELLOW . "Level is not loaded. Loading...");
            if ($this->getServer()->loadLevel($name)){
                $this->getServer()->broadcastMessage(TextFormat::AQUA . "Level " . $name . " Loaded!");
            }else{
                $this->getServer()->broadcastMessage(TextFormat::RED . "The level couldn't be loaded");
                return false;
            }
        }else{
            $this->getServer()->broadcastMessage(TextFormat::AQUA . "Level is already loaded!");
            return false;
        }
    }

    public function unloadWorld($name){
        $level = $this->getServer()->getLevelByName($name);
        if(!$this->getServer()->isLevelGenerated($name)){
            $this->getServer()->broadcastMessage(TextFormat::RED . "The level does not exist");
            return false;
        }
        if (!$this->getServer()->isLevelLoaded($name)) {
            $this->getServer()->broadcastMessage(TextFormat::YELLOW . "Level is not loaded.");
            return false;
        }
        if ($level instanceof Level) {
            $this->getServer()->unloadLevel($level);
        }
    }

    public static function file_delDir($dir){
        $dir = rtrim($dir, "/\\") . "/";
        foreach (scandir($dir) as $file) {
            if ($file === "." or $file === "..") {
                continue;
            }
            $path = $dir . $file;
            if (is_dir($path)) {
                self::file_delDir($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }
}