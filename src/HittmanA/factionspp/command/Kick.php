<?php

namespace HittmanA\factionspp\command;

use HittmanA\factionspp\provider\BaseProvider;
use HittmanA\factionspp\Member;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Kick
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

        if(!$this->sender->hasPermission("fpp.command.kick"))
        {
            $this->sender->sendMessage(TextFormat::RED . "You don't have permission to use this subcommand.");
            return;
        }
        else
        {
            $factionName = $this->provider->getPlayer($this->sender)["faction"];
            $targetPlayer = $this->sender->getServer()->getPlayer($this->args[0]);
            if($this->provider->getPlayer($this->sender)["role"] > $this->provider->getPlayer($targetPlayer)["role"])
            {
                $this->provider->removePlayerFromFaction($targetPlayer);
                $this->sender->sendMessage(TextFormat::GREEN . $targetPlayer->getName() . " has been removed.");
            }
            else
            {
                $this->sender->sendMessage(TextFormat::RED . "You don't have permission to kick that person.");
            }

        }

    }

}
