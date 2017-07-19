<?php

namespace HittmanA\factionspp\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use HittmanA\factionspp\Provider;

class Info
{
    
    public function __construct($args, $provider, Command $command, CommandSender $sender)
    {
        $this->args = $args;
        $this->command = $command;
        $this->sender = $sender;
        $this->provider = $provider;
    }
    
    public function execute()
    {
        
        $player = $this->provider->getPlayer($this->sender->getServer()->getPlayer($this->sender->getName()));
        $faction = $this->provider->getFaction($player["faction"]);
        $this->sender->sendMessage(TextFormat::GOLD . "=====Faction Info=====");
        $this->sender->sendMessage(TextFormat::GREEN . "Faction: " . $faction["display"]);
        $this->sender->sendMessage(TextFormat::GREEN . "Faction Leader: " . $faction["leader"]);
        $this->sender->sendMessage(TextFormat::GREEN . "Your Role: " . $player["role"]);
        $this->sender->sendMessage(TextFormat::GREEN . "Faction Power: " . $faction["power"]);
        $this->sender->sendMessage(TextFormat::GREEN . "Faction Money: " . $faction["money"]);
        $this->sender->sendMessage(TextFormat::GOLD . "======================");
		
    }
    
}