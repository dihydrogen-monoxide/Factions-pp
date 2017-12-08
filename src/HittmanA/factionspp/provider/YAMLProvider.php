<?php

namespace HittmanA\factionspp\provider;

use HittmanA\factionspp\MainClass;
use HittmanA\factionspp\Member;
use pocketmine\IPlayer;
use pocketmine\Player;
use pocketmine\utils\Config;

class YAMLProvider extends BaseProvider implements Provider
{

    /** @var Config */
    protected $factions;
    /** @var Config */
    protected $users;
    /** @var Config */
    protected $claims;
    /** @var Config */
    protected $invites;

    public function __construct(MainClass $plugin)
    {
        parent::__construct($plugin);
        $this->plugin = $plugin;
        $this->factions = new Config($this->plugin->getDataFolder() . "factions.yml", Config::YAML, []);
        $this->users = new Config($this->plugin->getDataFolder() . "players.yml", Config::YAML, []);
        $this->claims = new Config($this->plugin->getDataFolder() . "claims.yml", Config::YAML, []);
        $this->invites = new Config($this->plugin->getDataFolder() . "invites.yml", Config::YAML, []);
    }

    public function getProvider(): string
    {
        return "yaml";
    }

    public function getFaction(string $factionName): array
    {
        $name = strtolower($factionName);
        if($this->factions->get($name) == false)
        {
            return array();
        }
        else
        {
            return $this->factions->get($name);
        }
    }

    public function getNumberOfFactions(): int
    {
        return count($this->factions->getAll());
    }

    public function createFaction(string $name, Player $sender): bool
    {
        $this->factions->set(strtolower($name), [
            "name" => strtolower($name),
            "display" => $name,
            "motd" => "This is a new faction! Use /f motd <new motd> to set a new motd.",
            "leader" => strtolower($sender->getName()),
            "officers" => [],
            "members" => [],
            "power" => 5,
            "money" => 0,
            "claimx" => $sender->getX() + 9,
            "claimz" => $sender->getZ() + 9,
            "claimx2" => $sender->getX() - 9,
            "claimz2" => $sender->getZ() - 9
        ]);
        //And make a new player profile in the player config.
        $this->users->set(strtolower($sender->getName()), [
            "name" => strtolower($sender->getName()),
            "faction" => strtolower($name),
            "role" => Member::MEMBER_LEADER
        ]);
        $this->save();

        return true;
    }

    public function setMOTD(string $name, string $motd): bool
    {
        $this->factions->setNested($name . ".motd", $motd);
        $this->save();
    }

    public function save()
    {
        $this->factions->save();
        $this->users->save();
        $this->claims->save();
        $this->invites->save();
    }

    public function removeFaction(string $faction): bool
    {
        $this->factions->remove($faction);
        //Save the faction config.
        $this->save();

        return true;
    }

    public function removePlayerFromFaction(IPlayer $player): bool
    {
        $faction = $this->getPlayer($player)["faction"];

        $members = $this->factions->get($faction)["members"];
        unset($members[strtolower($player->getName())]);
        $this->factions->setNested($faction . ".members", $members);
        $this->users->remove(strtolower($player->getName()));

        $this->save();

        return true;
    }

    public function getPlayer(IPlayer $player): array
    {
        $playerName = strtolower($player->getName());
        if($this->users->get($playerName) == false)
        {
            return array();
        }
        else
        {
            return $this->users->get($playerName);
        }
    }

    public function acceptInvite(IPlayer $player): bool
    {
        if(!$this->hasInvite($player)) {
            return false;
        }
        $faction = $this->getPlayer($player)["faction"];
        $this->invites->remove(strtolower($player->getName()));
        $this->users->set(strtolower($player->getName()), [
            "name" => strtolower($player->getName()),
            "faction" => $faction,
            "role" => Member::MEMBER_DEFAULT
        ]);
        $members = $this->factions->get($faction)["members"];
        $members[] = strtolower($player->getName());
        $this->factions->setNested($faction . ".members", $members);
        $this->save();
        return true;
    }

    public function hasInvite(IPlayer $player): bool
    {
        if(!$this->invites->exists(strtolower($player->getName()))) {
            return false;
        }
        return true;
    }

    public function playerIsInFaction(IPlayer $player): bool
    {
        if(isset($this->getPlayer($player)["faction"]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function newInvite(IPlayer $to, IPlayer $from): bool
    {
        $this->invites->set(strtolower($to->getName()), [
            "to" => strtolower($to->getName()),
            "from" => strtolower($from->getName())
        ]);
        $this->save();

        return true;
    }
}
