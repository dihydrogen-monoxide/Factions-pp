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

namespace dihydrogen\monoxide\factions\command\subcommands;

use dihydrogen\monoxide\factions\command\generic\GenericSubCommand;
use pocketmine\command\CommandSender;

class HelpSubCommand extends GenericSubCommand {

	public function getName() : string {
		return "help";
	}

	public function getUsage() : string {
		return "";
	}

	public function getAliases() : array {
		return ["h", "?"];
	}

	public function run(CommandSender $sender, array $args) {
		$sender->sendMessage("FactionsPP Help");
	}

	public function getPermission() : string {
		return "factionspp.command.factions.help";
	}

}