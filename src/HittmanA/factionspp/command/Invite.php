<?php

namespace HittmanA\factionspp\command;

use HittmanA\factionspp\provider\BaseProvider;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Invite
{
    
    public function __construct(array $args, BaseProvider $provider, Command $command, CommandSender $sender)
    {
        $this->args = $args;
        $this->command = $command;
        $this->sender = $sender;
        $this->provider = $provider;
    }
    
    public function execute()
    {

    	if(!$this->sender instanceof Player)
	    {
	    	$this->sender->sendMessage(TextFormat::RED . "You can only execute this command as a player.");
	    	return;
	    }
        if(strtolower($this->sender->getName()) !== strtolower($this->args[0]))
        {
            $from = $this->sender;
            $to = $this->sender->getServer()->getPlayer($this->args[0]);
            
            if($to !== null)
            {
                if($this->provider->playerIsInFaction($to) === true)
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