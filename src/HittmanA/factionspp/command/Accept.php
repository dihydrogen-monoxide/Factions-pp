<?php

namespace HittmanA\factionspp\command;

use HittmanA\factionspp\provider\BaseProvider;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Accept
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
        if (!$this->sender instanceof Player) {
            $this->sender->sendMessage(TextFormat::RED . "You can only execute this command as a player.");
            return;
        }
        if (!$this->provider->hasInvite($this->sender)) {
            $this->sender->sendMessage(TextFormat::RED . "You don't have any open faction invites.");
            return;
        }
        $this->provider->acceptInvite($this->sender);
        $faction = $this->provider->getPlayer($this->sender)["faction"];
        $this->sender->sendMessage(TextFormat::GREEN . "You have successfully joined the " . TextFormat::AQUA . $faction . TextFormat::RESET . TextFormat::GREEN . " faction!");
    }
}