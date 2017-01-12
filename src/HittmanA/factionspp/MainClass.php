<?php

namespace HittmanA\factionspp;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class MainClass extends PluginBase implements Listener {

	/** @var Config */
	protected $fac;

	public function onEnable() {
		@mkdir($this->getDataFolder());
		$this->facs = new Config($this->getDataFolder() . "factions.json", Config::JSON, []);
		$this->playerInfo = new Config($this->getDataFolder() . "players.json", Config::JSON, []);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getPluginManager()->registerEvents(new Events($this), $this);
		$this->getLogger()->info(TextFormat::YELLOW . "Loaded!");
	}

	public function onDisable() {
		$this->getLogger()->info(TextFormat::YELLOW . "Unloading!");
	}

	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		$displayName = $sender->getName();
		switch ($command->getName()) {
			case "factionspp":
			case "fpp":
			case "f":
				if($sender instanceof Player) {
					if(isset($args[1])) {
						if(strtolower(array_shift($args)) === "create") {
							$facName = array_shift($args);
							$this->facs->set($facName, [
								"name" => strtolower($facName),
								"display" => $facName,
								"leader" => $displayName,
								"officers" => [],
								"members" => []
							]);
							$this->playerInfo->set($displayName,[
								"name" => $displayName,
								"faction" => $facName,
								"role" => "Leader"
							]);
							$this->facs->save(true);
							$this->playerInfo->save(true);
							$sender->sendMessage(TextFormat::GREEN . "Faction created!");
						}
					} else {
						$sender->sendMessage(TextFormat::GOLD . "Usage: /factionspp create <name>");
					}
				} else {
					$sender->sendMessage(TextFormat::RED . "Please run this command in-game");
				}
				return true;
			default:
				return false;
		}
	}

}
