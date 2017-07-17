<?php
namespace HittmanA\factionspp\provider;

use HittmanA\factionspp\MainClass;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;

class YAMLProvider implements Provider{
	
	public function __construct(MainClass $plugin){
		$this->plugin = $plugin;
		$this->factions = new Config($this->plugin->getDataFolder() . "factions.yml", Config::YAML, []);
        $this->users = new Config($this->plugin->getDataFolder() . "players.yml", Config::YAML, []);
        $this->claims = new Config($this->plugin->getDataFolder() . "claims.yml", Config::YAML, []);
	}
	
	public function getProvider()
    {
        return "yaml";
    }
   
    public function getFaction($name)
    {
        return $this->factions->$name;
    }
    
    public function getPlayer($player)
    {
        $playerName = $player->getDisplayName();
        return $this->users->$playerName;
    }
    
    public function getNumberOfFactions()
    {
        return count($this->factions) - 1;
    }
    
    public function createFaction($name, CommandSender $sender)
    {
        $this->factions->set($name, [
            "name" => strtolower($name),
            "display" => $name,
            "leader" => $sender->getName(),
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
        $this->users->set($sender->getName(),[
            "name" => $sender->getName(),
            "faction" => $name,
            "role" => "Leader"
        ]);
        save();
    
        return true;
    }
    
    public function removeFaction($faction)
    {
        $this->factions->remove($faction);
        //Save the faction config.
        save();
        
        return true;
    }
    
    public function removePlayerFromFaction($player)
    {
        getPlayer($player)->faction = "";
        getPlayer($player)->role = "";
        
        save();
        
        return true;
    }
    
    public function playerIsInFaction($player)
    {
        if(isset(getPlayer($player)->faction))
        {
            return true;
        } else {
            return false;
        }
    }
    
    public function save()
    {
        $this->factions->save();
        $this->users->save();
        $this->claims->save();
    }
}