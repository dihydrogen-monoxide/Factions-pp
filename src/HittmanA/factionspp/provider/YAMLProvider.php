<?php
namespace HittmanA\factionspp\provider;

use HittmanA\factionspp\MainClass;

use pocketmine\IPlayer;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;

class YAMLProvider extends BaseProvider implements Provider{

	/** @var Config */
	protected $factions;
	/** @var Config */
	protected $users;
	/** @var Config */
	protected $claims;

	public function __construct(MainClass $plugin){
		parent::__construct($plugin);
		$this->plugin = $plugin;
		$this->factions = new Config($this->plugin->getDataFolder() . "factions.yml", Config::YAML, []);
        $this->users = new Config($this->plugin->getDataFolder() . "players.yml", Config::YAML, []);
        $this->claims = new Config($this->plugin->getDataFolder() . "claims.yml", Config::YAML, []);
	}
	
	public function getProvider(): string
    {
        return "yaml";
    }
   
    public function getFaction(string $name): array
    {
        return $this->factions->$name;
    }
    
    public function getPlayer(IPlayer $player): array
    {
        $playerName = strtolower($player->getName());
        return $this->users->$playerName;
    }
    
    public function getNumberOfFactions(): int
    {
        return count($this->factions->getAll()) - 1;
    }
    
    public function createFaction(string $name, IPlayer $sender): bool
    {
        $this->factions->set($name, [
            "name" => strtolower($name),
            "display" => $name,
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
        $this->users->set($sender->getName(),[
            "name" => strtolower($sender->getName()),
            "faction" => $name,
            "role" => "Leader"
        ]);
        $this->save();
    
        return true;
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
        $this->getPlayer($player)->faction = "";
        $this->getPlayer($player)->role = "";
        
        $this->save();
        
        return true;
    }
    
    public function playerIsInFaction(IPlayer $player): bool
    {
        if(isset($this->getPlayer($player)["faction"]))
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
