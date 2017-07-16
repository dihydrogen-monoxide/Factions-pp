<?php

namespace HittmanA\factionspp\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use HittmanA\factionspp\Provider;

class CreateFaction
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
        
        $player = $this->provider->getPlayer($this->sender->getName());
        $faction = $this->provider->getFaction($player->faction);
        $sender->sendMessage(TextFormat::GOLD . "=====Faction Info=====");
        $sender->sendMessage(TextFormat::GREEN . "Faction: " . $faction->display);
        $sender->sendMessage(TextFormat::GREEN . "Faction Leader: " . $faction->leader);
        $sender->sendMessage(TextFormat::GREEN . "Your Role: " . $player->role);
        $sender->sendMessage(TextFormat::GREEN . "Faction Power: " . $faction->power);
        $sender->sendMessage(TextFormat::GREEN . "Faction Money: " . $faction->money);
        $sender->sendMessage(TextFormat::GOLD . "======================");
		
    }
    
}