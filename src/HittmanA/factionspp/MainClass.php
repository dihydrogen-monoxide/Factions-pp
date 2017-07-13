<?php
namespace HittmanA\factionspp;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
class MainClass extends PluginBase implements Listener {
    /** @var Config */
    protected $fac;
    public function onEnable() {
        //Make the faction config
      @mkdir($this->getDataFolder());
        $this->facs = new Config($this->getDataFolder() . "factions.json", Config::JSON, []);
        //Make the player info config
        $this->playerInfo = new Config($this->getDataFolder() . "players.json", Config::JSON, []);
        //Now the claims
        $this->claims = new Config($this->getDataFolder() . "claims.json", Config::JSON, []);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new Events($this), $this);
        $this->getLogger()->info(TextFormat::YELLOW . "Loaded!");
    }
    public function onDisable() {
        $this->getLogger()->info(TextFormat::YELLOW . "Unloading!");
    }
    //Was going to use this but first I need to make PureChatReloaded
    /*
    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $dispName = $player->getName();
        $fac = $this->playerInfo->$dispName->faction;
        if(isset($fac)) {
            $prefix = "[$fac]";
            $player->setDisplayName($prefix . " " . $player->getName());
            $player->setNameTag($prefix . " " . $player->getName());
        }
    }
    */
    ##$this->facs->$facWhosPowerYouWantToChange["power"] == 10;
    ##$this->facs->save(true);
    ##$power = $this->facs->$facName[“power”];
    ##$power += 10 //amount to add. Can also be a var
    ##$power -= 10
    //Checks if the player is in a faction claim
    public function isInClaim($p) {
      
    }
    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
      //Player's display name
        $displayName = $sender->getName();
        $playerHasFac = false;
        $playerRegistered = false;
        $power = 0;
        //Check if player is already registered in the config. If so set some helpful vars.
        if(isset($this->playerInfo->$displayName)){
          //Players info from the config.
          $playerFPPProfile = $this->playerInfo->$displayName;
          //Players faction from the config.
          $playerFac = $playerFPPProfile["faction"];
          //Players role in the faction.
          $playerRole = $playerFPPProfile["role"];
          //Get the information about the players faction from the faction config.
          $playerFacInfo = $this->facs->$playerFac;
          //Faction power
          $power = $playerFacInfo["power"];
          $x = $playerFacInfo["claimx"];
          $z = $playerFacInfo["claimz"];
          $x2 = $playerFacInfo["claimx"];
          $z2 = $playerFacInfo["claimz"];
          //Claim var's, not sure yet...
          //Player exists in player config.
          $playerRegistered = true;
          //If player's faction is set...
          if($playerFac !== ""){
            //Player has a faction.
            $playerHasFac = true;
          }
        }
    
        //The subcommand of the command
        $subcmd = strtolower(array_shift($args));
        //Is the actual command factionspp, fpp, or f?
        switch ($command->getName()){
            case "factionspp":
            case "fpp":
            case "f":
            //Is the command sender a player?
                if($sender instanceof Player) {
                  //Is the subcommand create?
                        if($subcmd === "create") {
                          //Is the faction name included? E.G. /f create <faction name>...
                            if(isset($args[0])) {
                              //Name of the <faction name>
                                $facName = array_shift($args);
                                //Does the player have a fac...
                                if($playerHasFac) {
                                  //THEN WHY DO YOU NEED TO MAKE ANOTHER ONE?!
                                    $sender->sendMessage(TextFormat::RED . "You are already in a faction!");
                                }else{
                                  //Does this faction exist...
                                    if(!isset($this->facs->$facName)){
                                      //If not then create a new faction in the config.
                                      $this->facs->set($facName, [
                                          "name" => strtolower($facName),
                                          "display" => $facName,
                                          "leader" => $displayName,
                                          "officers" => [],
                                          "members" => [],
                                          "power" => 5,
                                          "claimx" => $sender->getX() + 9,
                                          "claimz" => $sender->getZ() + 9,
                                          "claimx2" => $sender->getX() - 9,
                                          "claimz2" => $sender->getZ() - 9
                                      ]);
                                      //And make a new player profile in the player config.
                                      $this->playerInfo->set($displayName,[
                                          "name" => $displayName,
                                          "faction" => $facName,
                                          "role" => "Leader"
                                      ]);
                                      //Save the faction config.
                                      $this->facs->save(true);
                                      //And the player info.
                                      $this->playerInfo->save(true);
                                      /*
                                      $prefix = "[".$facName."]";
                                      $sender->setDisplayName($prefix . " " . $displayName);
                                      $sender->setNameTag($prefix . " " . $displayName);
                                      */
                                      //Success!
                                      $sender->sendMessage(TextFormat::GREEN . "Faction created!");
                                      return true;
                                    }else{
                                      //Otherwise, WHY ARE YOU TRYING TO STEAL THAT FACTION NAME?!
                                      $sender->sendMessage(TextFormat::RED . "That faction already exists!");
                                      return true;
                                }
                              }
                            } else {
                              //Or did you forget to specify a name?
                                $sender->sendMessage(TextFormat::GOLD . "Usage: /factionspp create <name>");
                                return true;
                            }
                    } elseif ($subcmd === "info") {
                            if($playerRegistered === true && $playerHasFac) {
                                $sender->sendMessage(TextFormat::GOLD . "Faction: " . $playerFac);
                                $sender->sendMessage(TextFormat::GREEN . "Your Role: " . $playerFPPProfile["role"]);
                                $sender->sendMessage(TextFormat::GREEN . "Faction Power: " . $power);
                                return true;
                            }else{
                                $sender->sendMessage(TextFormat::RED . "You must be part of a faction to run this command!");
                                return true;
                            }
                    } elseif ($subcmd === "claim") {
                            if($playerRegistered === true && $playerHasFac) {
                                $sender->sendMessage(TextFormat::GOLD . "Faction: " . $playerFac);
                                $sender->sendMessage(TextFormat::GREEN . "Your Role: " . $playerFPPProfile["role"]);
                                $sender->sendMessage(TextFormat::GREEN . "Faction Power: " . $power);
                                return true;
                            }else{
                                $sender->sendMessage(TextFormat::RED . "You must be part of a faction to run this command!");
                                return true;
                            }
                    } elseif ($subcmd === "leave" || $subcmd === "quit") {
                            if($playerRegistered === true && $playerHasFac) {
                                if(empty($playerFacInfo["officers"]) && empty($playerFacInfo["members"])) {
                                    $this->facs->remove($playerFac);
                                    $this->playerInfo->setNested($displayName, ["name" => $displayName,"faction" => "","role" => ""]);
                                    $sender->sendMessage(TextFormat::GREEN . "You have left the faction!");
                                    return true;
                                  }
                                } else {
                                    if($playerRole !== "Leader") {
                                        $this->facs->$playerFac[$playerRole . "s"]->remove($displayName);
                                        $this->playerInfo->setNested($displayName, ["name" => $displayName,"faction" => "","role" => ""]);
                                        $sender->sendMessage(TextFormat::GREEN . "You have left the faction!");
                                        return true;
                                    } else {
                                        $sender->sendMessage(TextFormat::RED . "You must make another player leader first!");
                                        return true;
                                    }
                                }
                            }else{
                                $sender->sendMessage(TextFormat::RED . "You must be part of a faction to run this command!");
                                return true;
                            }
                        }
                        $this->facs->save(true);
                        $this->playerInfo->save(true);
                      }             
                    }
}