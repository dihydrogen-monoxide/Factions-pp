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

use pocketmine\command\CommandSender;

abstract class GenericSubCommand {

	/** @var GenericCommand */
	private $owner = null;

	public function __construct(GenericCommand $owner) {
		$this->owner = $owner;
	}

	public function getOwner() : GenericCommand {
		return $this->owner;
	}

	public abstract function getName() : string;

	public abstract function getUsage() : string;

	public function getAliases() : array {
		return [];
	}

	public abstract function run(CommandSender $sender, array $args);

	public abstract function getPermission() : string;

}