<?php

namespace HittmanA\factionspp\provider;


use HittmanA\factionspp\MainClass;
use pocketmine\IPlayer;

class SQLiteProvider extends BaseProvider implements Provider {
	
	private $database;
	
	public function __construct(MainClass $plugin){
		parent::__construct($plugin);
	}

	public function initialize(): bool{
		if(!file_exists($file = $this->plugin->getDataFolder() . "faction_data.sqlite3"))
		{
			file_put_contents($file, "");
		}
		return true;
	}

	public function playerIsInFaction(IPlayer $player): bool
	{
		// TODO: Implement playerIsInFaction() method.
	}

	public function createFaction(string $name, IPlayer $player): bool
	{
		// TODO: Implement createFaction() method.
	}

	public function removeFaction(string $name): bool
	{
		// TODO: Implement removeFaction() method.
	}

	public function getFaction(string $name): array
	{
		// TODO: Implement getFaction() method.
	}

	public function getPlayer(IPlayer $player): array
	{
		// TODO: Implement getPlayer() method.
	}

	public function removePlayerFromFaction(IPlayer $player): bool
	{
		// TODO: Implement removePlayerFromFaction() method.
	}

	public function getProvider(): string
	{
		// TODO: Implement getProvider() method.
	}

	public function getNumberOfFactions(): int
	{
		// TODO: Implement getNumberOfFactions() method.
	}

	public function save()
	{
		// TODO: Implement save() method.
	}
}