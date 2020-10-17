<?php


namespace App\Core\Enum;


abstract class GenreEnum
{
    const GENRE_MR = 0;
    const GENRE_MME = 1;
    const GENRE_MLLE = 2;

    public static $typeName = [
        self::GENRE_MR => 'MR',
        self::GENRE_MME => 'Mme',
        self::GENRE_MLLE => 'Mlle',
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
            self::GENRE_MR,
            self::GENRE_MME,
            self::GENRE_MLLE,
        ];
    }
}