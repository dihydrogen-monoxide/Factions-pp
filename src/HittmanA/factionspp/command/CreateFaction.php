<?php

namespace HittmanA\factionspp\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use onebone\economyapi\EconomyAPI;

class CreateFaction
{
    
    public function __construct($factionInfo, $playerFactionInfo, $command, $sender)
    {
        $this->faction = $factionInfo;
        $this->player = $playerFactionInfo;
        $this->command = $command;
        $this->sender = $sender;
        $this->setPermission("fpp.command.create");
    }
    
    public function Run()
    {
        if(!$this->testPermission($sender)){
			return false;
		}
		
		$sender->sendMessage(TextFormat::GREEN . "Worked!");
		
    }
    
}