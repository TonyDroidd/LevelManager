<?php

#    _                           _
#   ( )                         (_ )          /'\_/`\
#   | |       __   _   _    __   | |          |     |   _ _   ___     _ _    __     __   _ __
#   | |  _  /'__`\( ) ( ) /'__`\ | |          | (_) | /'_` )/' _ `\ /'_` ) /'_ `\ /'__`\( '__)
#   | |_( )(  ___/| \_/ |(  ___/ | |          | | | |( (_| || ( ) |( (_| |( (_) |(  ___/| |
#   (____/'`\____)`\___/'`\____)(___)         (_) (_)`\__,_)(_) (_)`\__,_)`\__  |`\____)(_)
#                                                                         ( )_) |

namespace TDroidd\LVLM;
use pocketmine\command\CommandExecutor;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use TDroidd\LVLM\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;

class Commands extends PluginBase implements CommandExecutor{
    public function __construct(Main $plugin){
        //parent::__construct("levelmanager", "Main command of LevelManager", "/lm gen:del levelName", ["lm", "lvm"]);
        //$this->setPermission("levelmanager.command");
        $this->plugin = $plugin;
    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
        if ($cmd->getName() === "levelmanager"){
            if (isset($args[0])) {
                switch (strtolower($args[0])) {
                    case "generate":
                    case "gen":
                    case "add":
                        if (isset($args[1])) {
                            $this->plugin->addLevel($args[1]);
                            $this->plugin->getServer()->broadcastMessage("Debug");
                        } else {
                            $this->getUsage();
                            return true;
                        }
                        break;

                    case "remove":
                    case "delete":
                    case "del":
                        if (isset($args[1])) {
                            $this->plugin->removeLevel($args[1], $sender);
                            $this->plugin->getServer()->broadcastMessage("Debug");
                        } else {
                            $this->getUsage();
                            return true;
                        }
                        break;
                }
            } else {
                $this->getUsage();
                return true;
            }
        }
    }
}

