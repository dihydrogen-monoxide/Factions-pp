<?php

namespace HittmanA\factionspp;

//Commands
use HittmanA\factionspp\command\Accept;
use HittmanA\factionspp\command\CreateFaction;
use HittmanA\factionspp\command\Info;
use HittmanA\factionspp\command\Invite;
use HittmanA\factionspp\command\DeleteFaction;
use HittmanA\factionspp\command\MOTD;
use HittmanA\factionspp\command\Kick;
//Providers
use HittmanA\factionspp\provider\BaseProvider;
use HittmanA\factionspp\provider\MySQLProvider;
use HittmanA\factionspp\provider\YAMLProvider;
use HittmanA\factionspp\provider\JSONProvider;
//PocketMine
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class MainClass extends PluginBase implements Listener
{

    /** @var Config */
    protected $fac;
    /** @var string */
    protected $economyPlugin = "";
    /** @var string */
    protected $economyPluginInstance = "";
    /** @var array */
    protected $invites = [];
    /** @var BaseProvider */
    private $provider = null;

    public function onEnable()
    {
        //Make the faction config
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        /*
        $this->facs = new Config($this->getDataFolder() . "factions.json", Config::JSON, []);

        //Make the player info config
        $this->playerInfo = new Config($this->getDataFolder() . "players.json", Config::JSON, []);

        //Now the claims
        $this->claims = new Config($this->getDataFolder() . "claims.json", Config::JSON, []);*/

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new Events($this), $this);

        if($this->getServer()->getPluginManager()->getPlugin('EconomyAPI'))
        {
            $this->economyPluginInstance = $this->getServer()->getPluginManager()->getPlugin('EconomyAPI');
            $this->economyPlugin = "EconomyS";
            $this->getLogger()->notice("EconomyAPI enabled. Using " . TextFormat::YELLOW . "EconomyS" . TextFormat::AQUA . " as economy plugin");
        }
        else
        {
            $this->economyPluginInstance = null;
            $this->economyPlugin = null;
            $this->getLogger()->notice(TextFormat::RED . "No economy plugin :( FactionsPP is much better with an economy plugin.");
        }

        switch(strtolower($this->getConfig()->get("provider")))
        {
            case "yaml":
                $this->provider = new YAMLProvider($this);
                break;
            case "json":
                $this->provider = new JSONProvider($this);
                break;
            default:
                $this->getLogger()->error("Invalid database was given. Selecting YAML data provider as default.");
                $this->provider = new YAMLProvider($this);
                break;
        }

        $this->getLogger()->notice("Database provider set to " . TextFormat::YELLOW . $this->provider->getProvider());
        if($this->provider->getNumberOfFactions() == 1)
        {
            $this->getLogger()->notice($this->provider->getNumberOfFactions() . " faction has been loaded.");
        }
        else
        {
            $this->getLogger()->notice($this->provider->getNumberOfFactions() . " factions have been loaded.");
        }

        $this->getLogger()->notice("Loaded!");
    }

    public function onDisable()
    {
        $this->getLogger()->info(TextFormat::GREEN . "Unloading!");
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
    /*public function isInClaim($p) {

    }*/

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if($command->getName() == "factionspp")
        {
            //The subcommand of the command
            $subCmd = strtolower(array_shift($args));

            if ($sender instanceof Player)
            {
                switch ($subCmd)
                {
                    case "accept":

                        if($this->provider->playerIsInFaction($sender))
                        {
                            $sender->sendMessage(TextFormat::RED . "You must leave your current faction to join a new one.");
                            return true;
                        }
                        $accept = new Accept($args, $this->provider, $command, $sender);
                        $accept->execute();
                        return true;

                        break;

                    case "kick":

                        if(isset($args[0]))
                        {
                            if(!$this->provider->playerIsInFaction($sender))
                            {
                                $sender->sendMessage(TextFormat::RED . "You must be in a faction to run this command.");
                                return true;
                            }
                            $kick = new Kick($args, $this->provider, $command, $sender);
                            $kick->execute();
                            return true;
                        }
                        else
                        {
                            $sender->sendMessage(TextFormat::RED . "You must specify a player name. Example: /f kick Steve");
                            return true;
                        }

                        break;

                    case "invite":

                        if(isset($args[0]))
                        {
                            if(!$this->provider->playerIsInFaction($sender))
                            {
                                $sender->sendMessage(TextFormat::RED . "You are not a member of any faction.");
                                return true;
                            }
                            $role = $this->provider->getPlayer($sender)["role"];
                            if($role !== Member::MEMBER_LEADER && $role !== Member::MEMBER_OFFICER)
                            {
                                $sender->sendMessage(TextFormat::RED . "Only officers and leaders are allowed to invite new members.");
                                return true;
                            }
                            $invite = new Invite($args, $this->provider, $command, $sender);
                            $invite->execute();
                            return true;
                        }
                        else
                        {
                            $sender->sendMessage(TextFormat::RED . "You must specify a player name. Example: /f invite Steve");
                            return true;
                        }
                        
                        break;

                    case "create":

                        if(isset($args[0]))
                        {
                            if($this->provider->playerIsInFaction($sender))
                            {
                                $sender->sendMessage(TextFormat::RED . "You are already in a faction! You must leave your faction to create a new one.");
                                return true;
                            }
                            else
                            {
                                $create = new CreateFaction($args, $this->provider, $command, $sender);
                                $create->execute();
                                return true;
                            }
                        }
                        else
                        {
                            $sender->sendMessage(TextFormat::RED . "You must specify a faction name. Example: /f create Example");
                            return true;
                        }

                        break;

                    case "delete":
                        if(!$this->provider->playerIsInFaction($sender))
                        {
                            $sender->sendMessage(TextFormat::RED . "You aren't in a faction!");
                            return true;
                        }
                        else
                        {
                            $delete = new DeleteFaction($args, $this->provider, $command, $sender);
                            $delete->execute();
                            return true;
                        }

                        break;

                    case "info":
                        if($this->provider->playerIsInFaction($sender) || isset($args[0]))
                        {
                            $info = new Info($args, $this->provider, $command, $sender);
                            $info->execute();
                            return true;
                        }
                        else
                        {
                            $sender->sendMessage(TextFormat::RED . "You must be in a faction to run this command.");
                            return true;
                        }

                        break;

                    case "motd":
                        if($this->provider->playerIsInFaction($sender))
                        {
                            $motd = new MOTD($args, $this->provider, $command, $sender);
                            $motd->execute();
                            return true;
                        }
                        else
                        {
                            $sender->sendMessage(TextFormat::RED . "You must be in a faction to run this command.");
                            return true;
                        }

                        break;

                    default:
                        $sender->sendMessage(TextFormat::RED . "Unknown FactionsPP command. Do '/f help' for more info.");

                }
                return true;
            }
            else
            {
                $sender->sendMessage(TextFormat::RED . "You must be a player to run FactionsPP commands!");
                return true;
            }
        }
        return false;
    }

    /*public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
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
                        return true;
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
                              "money" => 0,
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

                          $prefix = "[".$facName."]";
                          $sender->setDisplayName($prefix . " " . $displayName);
                          $sender->setNameTag($prefix . " " . $displayName);

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
                } else {
                  $sender->sendMessage(TextFormat::RED . "You must be part of a faction to do that!");
                }
          } elseif ($subcmd === "invite") {
            if($playerRegistered === true && $playerHasFac)
            {
              if(isset($args[0]))
              {
                if($playerRole === "Leader" || $playerRole === "Officer")
                {
                  $playerToInvite = $args[0];
                  array_push($invites, ["player"=>$player,"faction"=>$facName]);
                  $sender->sendMessage(TextFormat::GREEN . "Sent invite to ".$playerToInvite);
                  $playerToSendMessageTo = $this->getServer()->getPlayer($playerToInvite);
                  $playerToSendMessageTo->sendMessage(TextFormat::GREEAN . "You got a faction invite. To accept simply do /f accept and to decline do /f decline.");
                } else {
                  $sender->sendMessage(TextFormat::YELLOW . "You must be an officer or leader to invite new players to your faction.");
                }
              } else {
                $sender->sendMessage(TextFormat::YELLOW . "You must specify a player. Example: /f invite JohnDoe");
              }
            }
          } else {
            $sender->sendMessage(TextFormat::RED . "Unknown command.");
            return true;
          }
        }
      $sender->sendMessage(TextFormat::RED . "You must be a player to run factionspp commands.");
      $this->facs->save(true);
      $this->playerInfo->save(true);
      return true;
      }
    }*/
}
