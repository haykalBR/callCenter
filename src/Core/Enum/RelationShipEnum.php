<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Core\Enum;

abstract class RelationShipEnum
{
    const SINGLE  = 0;
    const MARRIED = 1;

    public static array $typeName = [
        self::SINGLE  => 'Single',
        self::MARRIED => 'Married',
    ];

    /**
     * @param string $typeShortName
     *
     * @return string
     */
    public static function getTypeName($typeShortName) : string
    {
        if (!isset(static::$typeName[$typeShortName])) {
            return "Unknown type ($typeShortName)";
        }

        return static::$typeName[$typeShortName];
    }

    /**
     * @return array<int>
     */
    public static function getAvailableTypes(): array
    {
        return [
            self::SINGLE,
            self::MARRIED,
        ];
    }
}
