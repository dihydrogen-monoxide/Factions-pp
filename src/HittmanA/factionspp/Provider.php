<?php

namespace HittmanA\factionspp;

use pocketmine\utils\Config;
use pocketmine\command\CommandSender;

class Provider
{
   
    public function __construct($parent)
    {
       $this->providerType = $parent->getConfig()->get("provider");
       $this->getDataFolder = $parent->getDataFolder();
       
       if($this->providerType == "json")
       {
            $this->factions = new Config($this->getDataFolder . "factions.json", Config::JSON, []);
            $this->playerInfo = new Config($this->getDataFolder . "players.json", Config::JSON, []);
            $this->claims = new Config($this->getDataFolder . "claims.json", Config::JSON, []);
       } elseif($this->providerType == "yaml") {
            $this->factions = new Config($this->getDataFolder . "factions.yml", Config::YAML, []);
            $this->playerInfo = new Config($this->getDataFolder . "players.yml", Config::YAML, []);
            $this->claims = new Config($this->getDataFolder . "claims.yml", Config::YAML, []);
       } elseif($this->providerType == "mysql") {
            
       }
    }
   
    public function getProvider()
    {
        return $this->providerType;
    }
   
    public function getFaction($faction)
    {
        if($this->providerType == "mysql")
        {
           
        } else {
            return $this->factions->$faction;
        }
    }
    
    public function getPlayer($playerName)
    {
        if($this->providerType == "mysql")
        {
           
        } else {
            return $this->playerInfo->$playerName;
        }
    }
    
    public function getNumberOfFactions()
    {
        if($this->providerType == "mysql")
        {
           
        } else { 
            return count($this->factions) - 1;
        }
    }
    
    public function createFaction($name, CommandSender $sender)
    {
        if($this->providerType == "mysql")
        {
           
        } else {
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
            $this->playerInfo->set($sender->getName(),[
                "name" => $sender->getName(),
                "faction" => $name,
                "role" => "Leader"
            ]);
            //Save the faction config.
            $this->factions->save(true);
            //And the player info.
            $this->playerInfo->save(true);
            
            return true;
        }
    }
    
    public function removeFaction($faction)
    {
        if($this->providerType == "mysql")
        {
           
        } else {
            $this->factions->remove($faction);
            //Save the faction config.
            $this->factions->save(true);
            //And the player info.
            $this->playerInfo->save(true);
            
            return true;
        }
    }
    
    public function removePlayerFromFaction($player)
    {
        if($this->providerType == "mysql")
        {
           
        } else {
            
        }
    }
    
    public function playerIsInFaction($player)
    {
        if($this->providerType == "mysql")
        {
           
        } else {
            if(isset(getPlayer($player)->faction))
            {
               return true;
            } else {
                return false;
            }
        }
    }

}