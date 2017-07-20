<?php

namespace HittmanA\factionspp\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use HittmanA\factionspp\Provider;

class Invite
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
     
        if(strtolower($this->sender->getName()) != strtolower($this->args[0]))
        {
            $from = $this->sender->getServer()->getPlayer($this->sender->getName());
            $to = $this->sender->getServer()->getPlayer($this->args[0]);
            
            if($to->isOnline() == true)
            {
                if($this->provider->playerIsInFaction($to) == true)
                {
                    $from->sendMessage(TextFormat::RED . "Sorry, " . $to->getName() . " is already in a faction. They must leave their faction before they can join yours");
                } else {
                    $this->provider->newInvite($to, $from);
                    $to->sendMessage(TextFormat::GREEN . "You have been invited to a faction (" . $this->provider->getPlayer($from)["faction"] . "). Type /f accept to accept the invite.");
            		$this->sender->sendMessage(TextFormat::GREEN . $to->getName() . " has been invited.");
                }
            } else {
                $this->sender->sendMessage(TextFormat::GREEN . $to->getName() . TextFormat::RED . " is currently not online. Please send them an invite when they are online.");
            }
        } else {
            $this->sender->sendMessage(TextFormat::RED . "You can't invite yourself to a faction, sorry.");
        }
		
    }
    
}