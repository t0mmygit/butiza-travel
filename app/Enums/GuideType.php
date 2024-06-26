<?php

namespace App\Enums;

enum GuideType: string
{
    case Guided = 'guided';

    case Private = 'private';

    case Independent = 'independent';

    public static function getLabel($state)
    {
        switch ($state) {
            case self::Guided: 
                return 'Guided';
            case self::Private:
                return 'Private Guided';
            case self::Independent:
                return 'Independent';
        }
    }
}
