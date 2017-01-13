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
        @mkdir($this->getDataFolder());
        $this->facs = new Config($this->getDataFolder() . "factions.json", Config::JSON, []);
        $this->playerInfo = new Config($this->getDataFolder() . "players.json", Config::JSON, []);
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

    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        $displayName = $sender->getName();
        $subcmd = strtolower(array_shift($args));
        switch ($command->getName()){
            case "factionspp":
            case "fpp":
            case "f":
                if($sender instanceof Player) {
                        if($subcmd === "create") {
                            if(isset($args[0])) {
                                $facName = array_shift($args);
                                if(isset($this->playerInfo->$displayName["faction"])) {
                                    $sender->sendMessage(TextFormat::RED . "You are already in a faction!");
                                }else
                                if(isset($this->facs->$facName)){
                                    $sender->sendMessage(TextFormat::RED . "A faction with this name already exists!");
                                }elseif(!isset($this->playerInfo->$displayName["faction"])) {
                                    if(!isset($this->facs->$facName)){
                                    $this->facs->set($facName, [
                                        "name" => strtolower($facName),
                                        "display" => $facName,
                                        "leader" => $displayName,
                                        "officers" => [],
                                        "members" => [],
                                        "power" => 5
                                    ]);
                                    $this->playerInfo->set($displayName,[
                                        "name" => $displayName,
                                        "faction" => $facName,
                                        "role" => "Leader"
                                    ]);
                                    $this->facs->save(true);
                                    $this->playerInfo->save(true);
                                    $prefix = "[".$facName."]";
                                    $sender->setDisplayName($prefix . " " . $displayName);
                                    $sender->setNameTag($prefix . " " . $displayName);
                                    $sender->sendMessage(TextFormat::GREEN . "Faction created!");
                                }
                            } else {
                                $sender->sendMessage(TextFormat::GOLD . "Usage: /factionspp create <name>");
                            }
                        }
                    }

                        elseif ($subcmd === "info") {
                            if(isset($this->playerInfo->$displayName)) {
                                $playerFPPProfile = $this->playerInfo->$displayName;
                                $playerFac = $playerFPPProfile["faction"];
                                $playerFacInfo = $this->facs->$playerFac;
                                $sender->sendMessage(TextFormat::GOLD . "Faction: " . $playerFac);
                                $sender->sendMessage(TextFormat::GREEN . "Your Role: " . $playerFPPProfile["role"]);
                            }else{
                                $sender->sendMessage(TextFormat::RED . "You must be part of a faction to run this command!");
                            }
                        }elseif ($subcmd === "leave" || $subcmd === "quit") {
                            if(isset($this->playerInfo->$displayName)) {
                                $playerFPPProfile = $this->playerInfo->$displayName;
                                $playerFac = $playerFPPProfile["faction"];
                                $playerFacInfo = $this->facs->$playerFac;
                                if(empty($playerFacInfo["officers"]) || empty($playerFacInfo["members"])) {
                                    $this->facs->remove($playerFac);
                                    $this->playerInfo->setNested($displayName, ["faction" => ""]);
                                    $this->playerInfo->setNested($displayName, ["role" => ""]);
                                    $sender->sendMessage(TextFormat::GREEN . "You have left the faction!");
                                }else{
                                    if($playerFPPProfile["role"] === "Leader") {
                                        $this->facs->$playerFac[$playerFPPProfile["role"] . "s"]->remove($displayName);
                                        $this->playerInfo->setNested($displayName, ["faction" => ""]);
                                        $this->playerInfo->setNested($displayName, ["role" => ""]); 
                                        $sender->sendMessage(TextFormat::GREEN . "You have left the faction!");
                                    }else{
                                        $sender->sendMessage(TextFormat::RED . "You must make another player leader first!");
                                    }
                                }
                            }else{
                                $sender->sendMessage(TextFormat::RED . "You must be part of a faction to run this command!");
                            }
                        }
                        $this->facs->save(true);
                        $this->playerInfo->save(true);
                } else {
                    $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
                }
            }
          }
}
