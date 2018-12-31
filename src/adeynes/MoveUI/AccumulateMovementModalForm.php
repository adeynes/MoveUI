<?php
declare(strict_types=1);

namespace adeynes\MoveUI;

use pocketmine\form\Form;
use pocketmine\Player;

class AccumulateMovementModalForm implements Form
{

    public const ACCUMULATE_MOVEMENT = true;
    public const EXECUTE_ACCUMULATED_MOVEMENTS = false;

    private const DATA = [
        'type' => 'modal',
        'title' => 'MOVEMENT_ACCUMULATOR',
        'content' => 'Would you enjoy accumulating more movements, or execute those already parametrized?',
        'button1' => 'Accumulate MOAR!',
        'button2' => 'I would rather begin locomoting.'
    ];

    /** @var MoveUI */
    private $plugin;

    public function __construct(MoveUI $plugin)
    {
        $this->plugin = $plugin;
    }

    public function handleResponse(Player $player, $data): void
    {
        $this->plugin->handleAccumulateMovementResponse($player, $data);
    }

    public function jsonSerialize()
    {
        return self::DATA;
    }

}