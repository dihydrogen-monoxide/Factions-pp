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
        
        if(!$this->sender->hasPermission("fpp.command.create")){
            
			return true;
		} else {
		    if($this->provider->getFaction($this->args[0]))
		    {
                $this->sender->sendMessage(TextFormat::RED . "That faction already exists! Please choose a different name.");
		    } else {
		        $this->provider->createFaction($this->args[0], $this->sender);
		        $this->sender->sendMessage(TextFormat::GREEN . "Your new faction has been made!");
		    }
		}
		
    }
    
}