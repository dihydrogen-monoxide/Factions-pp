<?php

namespace HittmanA\factionspp\command;

use HittmanA\factionspp\provider\BaseProvider;
use HittmanA\factionspp\Member;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class DeleteFaction
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

        if(!$this->sender->hasPermission("fpp.command.delete"))
        {
            $this->sender->sendMessage(TextFormat::RED . "You don't have permission to you use this subcommand.");
            return;
        }
        else
        {
            $factionName = $this->provider->getPlayer($this->sender)["faction"];
            if($this->provider->getPlayer($this->sender)["role"] !== Member::MEMBER_LEADER)
            {
                $this->sender->sendMessage(TextFormat::RED . "You do not own this faction. Only the owner of this faction may delete it.");
            }
            else
            {
                $faction = $this->provider->getFaction($factionName);
                for($i = 0; $i < count($faction["members"]); $i++)
                {
                    $player = $this->sender->getServer()->getPlayer($faction["members"][$i]);
                    $this->provider->removePlayerFromFaction($player);
                }
                for($i = 0; $i < count($faction["officers"]); $i++)
                {
                    $player = $this->sender->getServer()->getPlayer($faction["officers"][$i]);
                    $this->provider->removePlayerFromFaction($player);
                }
                $this->provider->removePlayerFromFaction($this->sender);
                $this->provider->removeFaction($factionName);
                $this->sender->sendMessage(TextFormat::GREEN . "Your faction has been deleted!");
            }

        }

    }

}
