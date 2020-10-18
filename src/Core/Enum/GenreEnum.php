<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Core\Enum;

use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Scalar\String_;

abstract class GenreEnum
{
    const GENRE_MR   = 0;
    const GENRE_MME  = 1;
    const GENRE_MLLE = 2;

    public static array $typeName = [
        self::GENRE_MR   => 'MR',
        self::GENRE_MME  => 'Mme',
        self::GENRE_MLLE => 'Mlle',
    ];

    /**
     * @param string $typeShortName
     *
     * @return string
     */
    public static function getTypeName($typeShortName): string
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
            self::GENRE_MR,
            self::GENRE_MME,
            self::GENRE_MLLE,
        ];
    }
}
