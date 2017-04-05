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

namespace dihydrogen\monoxide\factions\command;

use dihydrogen\monoxide\factions\command\generic\GenericCommand;
use dihydrogen\monoxide\factions\command\subcommands\HelpSubCommand;
use dihydrogen\monoxide\factions\FactionsPP;

class FactionsCommand extends GenericCommand {

	public function __construct(FactionsPP $plugin) {
		parent::__construct($plugin, "factions", "Main factions command", "/f <help>", ["f", "fpp", "fac"]);

		$this->setPermission("factionspp.command.factions");

		$this->registerSubCommand(new HelpSubCommand($this));
	}

}