<?php

namespace HittmanA\factionspp\provider;

use pocketmine\IPlayer;
use pocketmine\Player;

interface Provider
{

    /**
     * @param IPlayer $player
     *
     * @return bool
     */
    public function playerIsInFaction(IPlayer $player): bool;

    /**
     * @param string $name
     * @param Player $sender
     *
     * @return bool
     */
    public function createFaction(string $name, Player $player): bool;

    /**
     * @param string $name
     * @param string $motd
     *
     * @return bool
     */
    public function setMOTD(string $name, string $motd): bool;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function removeFaction(string $name): bool;

    /**
     * @param string $name
     *
     * @return array
     */
    public function getFaction(string $name): array;

    /**
     * @param IPlayer $player
     *
     * @return array
     */
    public function getPlayer(IPlayer $player): array;

    /**
     * @param IPlayer $player
     *
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

    /**
     * @param IPlayer $to
     * @param IPlayer $from
     *
     * @return bool
     */
    public function newInvite(IPlayer $to, IPlayer $from): bool;

    /**
     * @param IPlayer $player
     *
     * @return bool
     */
    public function hasInvite(IPlayer $player): bool;

    /**
     * @param IPlayer $player
     *
     * @return bool
     */
    public function acceptInvite(IPlayer $player): bool;

    public function save();
}
