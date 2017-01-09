<?php
namespace HittmanA\factionspp\MainClass;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;

class MainClass extends PluginBase implements Listener {
    public function onEnable() {
	@mkdir($this->getDataFolder());
  	$this->badPlayers = new Config($this->getDataFolder()."factions-list.txt", Config::ENUM);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
	$this->getServer()->getPluginManager()->registerEvents(new Events($this), $this);
        $this->getLogger()->info(TextFormat::YELLOW . "[FactionsPP] Loaded!");
    }
    public function onDisable() {
        
    }
    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        switch ($command->getName()) {
		case "factionspp":
			
			break;
	    default:
                return false;
        }
    }
}
