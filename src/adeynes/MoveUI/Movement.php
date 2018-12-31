<?php
declare(strict_types=1);

namespace adeynes\MoveUI;

use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 *                MOVEMENT AXIS:
 *                   +y (pi/2)
 *                    forward
 *                       |
 *        -x (pi) left---+---right +x (0)
 *                       |
 *                   backward
 *                  -y (3pi/2)
 *
 *
 *
 *                MINECRAFT AXIS:
 *                  -z (pi yaw)
 *                       N
 *                       |
 *     -x (pi/2 yaw) W---+---E +x (3pi/2 yaw)
 *                       |
 *                       S
 *                  +z (0 yaw)
 */
class Movement extends Vector2
{

    public const FORWARD = 0;
    public const BACKWARD = 1;
    public const LEFT = 2;
    public const RIGHT = 3;

    /**
     * Returns a null movement
     *
     * @return Movement
     */
    public static function nullVector(): Movement
    {
        return new Movement(0, 0);
    }

    public static function fromVector2(Vector2 $vector): Movement
    {
        return new Movement($vector->getX(), $vector->getY());
    }

    /**
     * Returns a unit vector in the given direction
     *
     * @param int $direction
     * @return Movement
     *
     * @throws \InvalidArgumentException If the given direction is invalid
     */
    public static function fromDirection(int $direction): Movement
    {
        switch ($direction) {
            case self::FORWARD:
                return new Movement(0, 1);
            case self::BACKWARD:
                return new Movement(0, -1);
            case self::LEFT:
                return new Movement(-1, 0);
            case self::RIGHT:
                return new Movement(1, 0);
            default:
                throw new \InvalidArgumentException('Invalid direction passed to Movement::fromDirection()');
        }
    }

    /**
     * Turns a vector relative to a player into an absolute vector (relative to the map) given the player's yaw
     *
     * @param float $yaw
     * @param float $y The y value for the returned Vector3
     * @return Vector3
     */
    public function absolutize(float $yaw, float $y = 0): Vector3
    {
        // Normalizes the yaw to 0-360
        $yaw = fmod($yaw, 360);
        // Make it positive so we only have to use one rotation matrix
        if ($yaw < 0) {
            $yaw += 360;
        }
        $yaw = deg2rad($yaw);

        // Now we rotate our movement vector (which is in a normal plane) normally
        // And then transpose it into the minecraft plane:
        // x -> x; -y -> z

        $rotatedVector = Utils::matrixToVector2(Utils::get2dPositiveRotationMatrix($yaw - pi()/2)->product(Utils::vector2ToMatrix($this)));
        return new Vector3($rotatedVector->getX(), $y, -$rotatedVector->getY());
    }

    public function apply(Player $player): void
    {
        $player->setMotion($this->absolutize($player->getYaw()));
    }

}