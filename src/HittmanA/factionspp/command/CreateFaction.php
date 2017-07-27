<?php

namespace HittmanA\factionspp\command;

use HittmanA\factionspp\provider\BaseProvider;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class CreateFaction
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

        if(!$this->sender->hasPermission("fpp.command.create"))
        {
            $this->sender->sendMessage(TextFormat::RED . "You don't have permission to you use this subcommand.");
            return;
        }
        else
        {
            if($this->provider->getFaction($this->args[0]))
            {
                $this->sender->sendMessage(TextFormat::RED . "That faction already exists! Please choose a different name.");
            }
            else
            {
                $this->provider->createFaction($this->args[0], $this->sender->getServer()->getPlayer($this->sender->getName()));
                $this->sender->sendMessage(TextFormat::GREEN . "Your new faction has been made!");
            }
        }

    }

}