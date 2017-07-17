<?php

namespace HittmanA\factionspp\provider;

use HittmanA\factionspp\MainClass;

use pocketmine\command\CommandSender;
use pocketmine\IPlayer;

interface Provider{
	/**
	 * @param IPlayer $player
	 * @return bool
	 */
	public function playerIsInFaction(IPlayer $player): bool;
	/**
	 * @param string $name
	 * @param IPlayer $sender
	 * @return bool
	 */
	public function createFaction(string $name, IPlayer $player): bool;
	/**
	 * @param string $name
	 * @return bool
	 */
	public function removeFaction(string $name): bool;
	/**
	 * @param string $name
	 * @return array
	 */
	public function getFaction(string $name): array;
	/**
	 * @param IPlayer $player
	 * @return array
	 */
	public function getPlayer(IPlayer $player): array;
	/**
	 * @param IPlayer $player
	 * @return bool
	 */
	public function removePlayerFromFaction(IPlayer $player): bool;
	/**
	 * @return string
	 */
	public function getProvider(): string;
	/**
	 * @return int
	 */
	public function getNumberOfFactions(): int;
	
	public function save();
}