<?php

namespace HittmanA\factionspp\provider;

use HittmanA\factionspp\MainClass;

use pocketmine\command\CommandSender;

interface Provider{
	public function __construct(MainClass $plugin);
	/**
	 * @param \pocketmine\Player|string $player
	 * @return bool
	 */
	public function playerIsInFaction($player);
	/**
	 * @param string $name
	 * @param \pocketmine\command\CommandSender|CommandSender $sender
	 * @return bool
	 */
	public function createFaction($name, CommandSender $sender);
	/**
	 * @param string $name
	 * @return bool
	 */
	public function removeFaction($name);
	/**
	 * @param string $name
	 * @return array
	 */
	public function getFaction($name);
	/**
	 * @param \pocketmine\Player|string $player
	 * @return array
	 */
	public function getPlayer($player);
	/**
	 * @param \pocketmine\Player|string $player
	 * @return bool
	 */
	public function removePlayerFromFaction($player);
	/**
	 * @return string
	 */
	public function getProvider();
	/**
	 * @return float
	 */
	public function getNumberOfFactions();
	public function save();
}