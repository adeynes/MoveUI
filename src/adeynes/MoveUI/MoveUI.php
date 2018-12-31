<?php
declare(strict_types=1);

namespace adeynes\MoveUI;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector2;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class MoveUI extends PluginBase
{

    /** @var MoveUI */
    private static $instance;

    /** @var ParametrizeMovementForm */
    private $parametrizeMovementForm;

    /** @var AccumulateMovementModalForm */
    private $accumulateMovementModalForm;

    /** @var Movement[] */
    private $movementAccumulators;

    public static function getInstance(): MoveUI
    {
        return self::$instance;
    }

    public function onLoad(): void
    {
        self::$instance = $this;
    }

    public function onEnable(): void
    {
        $this->parametrizeMovementForm = new ParametrizeMovementForm($this);
        $this->accumulateMovementModalForm = new AccumulateMovementModalForm($this);
    }

    public function getMovementAccumulator(string $name): ?Movement
    {
        return $this->movementAccumulators[$name] ?? null;
    }

    public function setMovementAccumulator(string $name, Movement $accumulator): void
    {
        $this->movementAccumulators[$name] = $accumulator;
    }

    public function handleParametrizeMovementResponse(Player $player, int $direction, float $distance): void
    {
        $name = $player->getName();
        if ($this->getMovementAccumulator($name) === null) {
            $this->setMovementAccumulator($name, Movement::nullVector());
        }

        $movementVector = Movement::fromDirection($direction)->multiply($distance);
        $this->setMovementAccumulator($name, Movement::fromVector2($this->getMovementAccumulator($name)->add($movementVector)));

        $player->sendForm($this->accumulateMovementModalForm);
    }

    public function handleAccumulateMovementResponse(Player $player, bool $action): void
    {
        if ($action === AccumulateMovementModalForm::ACCUMULATE_MOVEMENT) {
            $player->sendForm($this->parametrizeMovementForm);
        }
        if ($action === AccumulateMovementModalForm::EXECUTE_ACCUMULATED_MOVEMENTS) {
            $this->getMovementAccumulator($player->getName())->apply($player);
            $this->setMovementAccumulator($player->getName(), Movement::nullVector());
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command->getName() === 'move') {
            if (!$sender instanceof Player) {
                $sender->sendMessage(TextFormat::RED . 'You must run /move in-game');
                return true;
            }

            $sender->sendForm($this->parametrizeMovementForm);
        }

        return true;
    }

}