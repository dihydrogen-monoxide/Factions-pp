<?php

namespace HittmanA\factionspp\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use HittmanA\factionspp\MainCLass;

class CreateCommand extends PluginBase implements Listener {

    /** @var Config */
    protected $fac;

    public function __construct(MainClass $plugin){
          $this->plugin = $plugin;
          $this->plugin->facs = new Config($this->plugin->getDataFolder() . "factions.json", Config::JSON, []);
          $this->plugin->playerInfo = new Config($this->plugin->getDataFolder() . "players.json", Config::JSON, []);
    }

    public function dataPath()
    {
      return $this->getDataFolder();
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
    public function hasFaction($displayName){//Players info from the config.
        $playerFPPProfile = $this->plugin->playerInfo->$displayName;
        //Players faction from the config.
        $playerFac = $playerFPPProfile["faction"];
        if($playerFac !== ""){
            $playerHasFac = true;
    }
}
    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
      //Player's display name
        $displayName = $sender->getName();
        //Check if player is already registered in the config. If so set some helpful vars.
        if(isset($this->playerInfo->$displayName)){
          //Players info from the config.
          $playerFPPProfile = $this->plugin->playerInfo->$displayName;
          //Players faction from the config.
          $playerFac = $playerFPPProfile["faction"];
          //Players role in the faction.
          $playerRole = $playerFPPProfile["role"];
          //Get the information about the players faction from the faction config.
          $playerFacInfo = $this->plugin->facs->$playerFac;
          //Player exists in player config.
          $playerRegistered = true;
          //If player's faction is set...
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
                          //Is the faction name included? E.G. /f create <faction name>
                            if(isset($args[0])) {
                              //Name of the <faction name>
                                $facName = array_shift($args);
                                //Does the player have a fac...
                                if(!$this->hasFaction($displayName)){
                                  //THEN WHY DO YOU NEED TO MAKE ANOTHER ONE?!
                                    $sender->sendMessage(TextFormat::RED . "You are already in a faction!");
                                }else{
                                  //Does 
                                    if(!isset($this->facs->$facName)){
                                    $this->plugin->facs->set($facName, [
                                        "name" => strtolower($facName),
                                        "display" => $facName,
                                        "leader" => $displayName,
                                        "officers" => [],
                                        "members" => [],
                                        "power" => 5
                                    ]);
                                    $this->plugin->playerInfo->set($displayName,[
                                        "name" => $displayName,
                                        "faction" => $facName,
                                        "role" => "Leader"
                                    ]);
                                    $this->plugin->facs->save(true);
                                    $this->plugin->playerInfo->save(true);
                                    $prefix = "[".$facName."]";
                                    $sender->setDisplayName($prefix . " " . $displayName);
                                    $sender->setNameTag($prefix . " " . $displayName);
                                    $sender->sendMessage(TextFormat::GREEN . "Faction created!");
                                }else{
                                  $sender->sendMessage(TextFormat::RED . "That faction already exists!");
                                }
                        $this->plugin->facs->save(true);
                        $this->plugin->playerInfo->save(true);
                    }
                }
            }
        }
    }
}
}
