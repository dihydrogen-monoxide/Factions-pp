<?php

namespace HittmanA\factionspp\command;

use HittmanA\factionspp\provider\BaseProvider;
use HittmanA\factionspp\Member;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class MOTD
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

        $motd = "";
        for($i = 0; $i < count($this->args); $i++)
        {
            $motd = $motd . $this->args[$i];
        }
        if(!$this->sender->hasPermission("fpp.command.motd"))
        {
            $this->sender->sendMessage(TextFormat::RED . "You don't have permission to use this subcommand.");
            return;
        }
        else
        {
            $factionName = $this->provider->getPlayer($this->sender)["faction"];
            if($this->provider->getPlayer($this->sender)["role"] !== Member::MEMBER_LEADER || $this->provider->getPlayer($this->sender)["role"] !== Member::MEMBER_OFFICER)
            {
                $this->sender->sendMessage(TextFormat::RED . "You do not have permission to change the motd. Only the owner and officers can do this.");
            }
            else
            {
                $this->provider->setMOTD($factionName, $motd);
                $this->sender->sendMessage(TextFormat::GREEN . "Your faction motd has been set!");
            }

        }

    }

}
