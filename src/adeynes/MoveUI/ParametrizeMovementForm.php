<?php
declare(strict_types=1);

namespace adeynes\MoveUI;

use pocketmine\form\Form;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ParametrizeMovementForm implements Form
{

    public const DIRECTION_DROPDOWN_INDEX = 0;
    public const DISTANCE_SLIDER_INDEX = 1;

    private const DATA = [
        'type' => 'custom_form',
        'title' => 'How shall your movement be parametrized?',
        'content' => [
            [
                'type' => 'dropdown',
                'text' => 'Towards which direction would Your Excellency like to march?',
                'options' => ['Forward', 'Backward', 'Left', 'Right']
            ],
            [
                'type' => 'slider',
                'text' => 'How far wouldst thou like to move?',
                'min' => 1,
                'max' => 8,
                'step' => 1
            ]
        ]
    ];

    /** @var MoveUI */
    private $plugin;

    public function __construct(MoveUI $plugin)
    {
        $this->plugin = $plugin;
    }

    public function handleResponse(Player $player, $data): void
    {
        $direction = $data[self::DIRECTION_DROPDOWN_INDEX];
        $distance = $data[self::DISTANCE_SLIDER_INDEX];

        if ($direction === null || $distance === null) {
            $player->sendMessage(TextFormat::RED . 'Please fill out all fields!');
            return;
        }

        $this->plugin->handleParametrizeMovementResponse($player, $direction, $distance);
    }

    public function jsonSerialize()
    {
        return self::DATA;
    }

}