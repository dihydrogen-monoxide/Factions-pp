<?php

namespace HittmanA\factionspp\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class CreateFaction
{
    
    public function __construct($factionInfo, $playerFactionInfo, Command $command, CommandSender $sender)
    {
        $this->faction = $factionInfo;
        $this->player = $playerFactionInfo;
        $this->command = $command;
        $this->sender = $sender;
    }
    
    public function execute()
    {
        
        if(!$this->sender->hasPermission("fpp.command.create")){
            
			return true;
		} else {
		    
		    $this->sender->sendMessage(TextFormat::GREEN . "Worked!");
		    
		}
		
    }
    
}