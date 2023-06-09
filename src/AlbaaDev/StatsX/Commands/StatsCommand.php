<?php

declare(strict_types=1);

namespace AlbaaDev\StatsX\Commands;

use AlbaaDev\StatsX\Forms\CustomForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat as C;
use AlbaaDev\StatsX\Main;

/**
 * Class StatsCommand
 * @package Vecnavium\SimpleStats\Commands
 */
class StatsCommand extends Command
{
	/** @var Main */
	private $plugin;

	/**
	 * StatsCommand constructor.
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin)
	{
		parent::__construct("stats", "stats command", "/stats", ['stats']);
		$this->setDescription("Get stats on a player or yourself.");
		$this->plugin = $plugin;
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return bool
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool
	{
		if (!$sender instanceof Player) {
			$sender->sendMessage("§cPlease run the command in-game.");
			return true;
		}
		switch ($args[0] ?? "stats") {
			case "stats":
				$this->mainForm($sender);
		}
		return true;
	}


	public function getPlugin(): Plugin
	{
		return $this->plugin;
	}

	/**
	 * @param Player $player
	 */
	public function MainForm(Player $player)
	{
		$form = new CustomForm(function (Player $sender, $data) {
			if ($data === null) {
				return true;
			}
			if (isset($data[1])) {
				$player = $this->getPlugin()->getServer()->getPlayerExact($data[1]);
				if ($player !== null) {
					$data = $this->plugin->getSessionFor($player);
					$name = $player->getName();
					$sender->sendMessage(C::RED . "[" . C::YELLOW . "Player" . C::YELLOW . "Statistics" . C::RED . "] \n" . C::RED . "=============\n" . C::WHITE . "+ Player: " . $name . "\n" . C::WHITE . "+ Kills: " . $data->getKills() . "\n" .  C::WHITE . "+ Deaths: " . $data->getDeaths() . "\n" . C::RED . "=============");
				} else {
					$sender->sendMessage("§cThis player either does not have any data or does not exist.");
					return true;
				}
			}
			return true;
		});
		$form->setTitle('§cStats§eX');
		$form->addLabel('Enter the in-game name of the player you wish to see stats for and then press submit.');
		$form->addInput('Username', 'Enter the username here');
		$player->sendForm($form);
	}
}
