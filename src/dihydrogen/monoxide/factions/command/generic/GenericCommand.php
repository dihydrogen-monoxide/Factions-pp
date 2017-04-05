<?php

/**
 * Factions-PP
 *
 * Copyright (C) 2017 dihydrogen-monoxide
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Jack Noordhuis
 *
 */

namespace dihydrogen\monoxide\factions\command\generic;

use dihydrogen\monoxide\factions\FactionsPP;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

abstract class GenericCommand extends Command implements PluginIdentifiableCommand {

	/** @var FactionsPP */
	private $plugin = null;

	/** @var GenericSubCommand[] */
	protected $subCommands = [];

	public function __construct(FactionsPP $plugin, $name, $description = "", $usageMessage = null, $aliases = []) {
		parent::__construct($name, $description, $usageMessage, $aliases);
		$this->plugin = $plugin;
	}

	public function getPlugin() : FactionsPP {
		return $this->plugin;
	}

	public function execute(CommandSender $sender, $commandLabel, array $args) : bool {
		if($this->testPermission($sender)) {
			if(isset($args[0])) {
				if(($subCmd = $this->getSubCommand(array_shift($args))) !== null) {
					if($sender->hasPermission($subCmd->getPermission())) {
						$subCmd->run($sender, $args);
						return true;
					} else {
						$sender->sendMessage($this->getPermissionMessage());
						return true;
					}
				}
			}
			$sender->sendMessage("Usage: " . $this->getUsage());
			return true;
		} else {
			$sender->sendMessage($this->getPermissionMessage());
			return true;
		}
	}

	/**
	 * @param string $name
	 *
	 * @return GenericSubCommand|null
	 */
	public function getSubCommand(string $name) {
		return $this->subCommands[strtolower($name)] ?? null;
	}

	public function registerSubCommand(GenericSubCommand $subCmd) {
		foreach(array_merge($subCmd->getAliases(), [$subCmd->getName()]) as $alias) {
			$label = strtolower($alias);
			if(!isset($this->subCommands[$label])) {
				$this->subCommands[$label] = $subCmd;
			} else {
				// TODO: Appropriate error handling
			}
		}
	}

}