<?php
namespace HittmanA\factionspp;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class MainClass extends PluginBase implements Listener {
    public function onEnable() {
	@mkdir($this->getDataFolder());
	$this->saveResource("factions.json");
  	$facs = new Config($this->getDataFolder() . "factions.json", Config::JSON);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
	$this->getServer()->getPluginManager()->registerEvents(new Events($this), $this);
        $this->getLogger()->info(TextFormat::YELLOW . "[FactionsPP] Loaded!");
    }
    public function onDisable() {
        $this->getLogger()->info(TextFormat::YELLOW . "[FactionsPP] Unloading!");
    }
    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        switch ($command->getName()) {
		case "factionspp":
			$subcmd = $args[1];
			if($subcmd == "create") {
				$cfgfac = $args[2];
				$write = array("name" => $cfgfac, "Leader" => $sender, "Officers" => array(), "Members" => array());
				$facs->set($cfgfac,json_encode($write));
				$facs->save();
			}
			break;
	    default:
                return false;
        }
    }
}
