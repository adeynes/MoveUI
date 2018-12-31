<?php
declare(strict_types=1);

namespace adeynes\MoveUI;

use pocketmine\math\Matrix;
use pocketmine\math\Vector2;

class Utils
{

    /**
     * Converts a Vector2 to a Matrix
     *
     * @param Vector2 $vector
     * @return Matrix
     */
    public static function vector2ToMatrix(Vector2 $vector): Matrix
    {
        return new Matrix(
            2,
            1,
            [
                [$vector->getX()],
                [$vector->getY()]
            ]
        );
    }

    /**
     * Converts a Matrix (2x1) to a Vector2
     *
     * @param Matrix $matrix A 2x1 Matrix
     * @return Vector2
     */
    public static function matrixToVector2(Matrix $matrix): Vector2
    {
        $x = $matrix->getElement(0, 0);
        $y = $matrix->getElement(1, 0);
        if ($x === false || $y === false) {
            throw new \InvalidArgumentException('Matrix passed to Utils::matrixToVector2() is not 2x1');
        }
        return new Vector2((float)$x, (float)$y);
    }

    /**
     * Returns the 2D rotation matrix for the given angle (positive)
     *
     * @param float $angle
     * @return Matrix
     */
    public static function get2dPositiveRotationMatrix(float $angle): Matrix
    {
        return new Matrix(
            2,
            2,
            [
                [cos($angle), -sin($angle)],
                [sin($angle), cos($angle)]
            ]
        );
    }

}