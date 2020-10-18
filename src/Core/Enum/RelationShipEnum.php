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
    const Single = 0;
    const Married = 1;

    public static $typeName = [
        self::Single => 'Single',
        self::Married => 'Married',
    ];

    /**
     * @param string $typeShortName
     *
     * @return string
     */
    public static function getTypeName($typeShortName)
    {
        if (!isset(static::$typeName[$typeShortName])) {
            return "Unknown type ($typeShortName)";
        }

        return static::$typeName[$typeShortName];
    }

    /**
     * @return array<string>
     */
    public static function getAvailableTypes(): array
    {
        return [
            self::Single,
            self::Married,
        ];
    }
}
