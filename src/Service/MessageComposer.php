<?php

namespace App\Service;

class MessageComposer
{
    public const STATUS_ACTIVATION = 'activate';

    public const STATUS_DEACTIVATION = 'deactivate';

    public const TYPE_AIR = 'air';

    public const TYPE_ARTILLERY = 'artillery';

    public const TYPE_URBAN_FIGHTS = 'urban_fights';

    public const TYPE_CHEMICAL = 'chemical';

    public const TYPE_NUCLEAR = 'nuclear';

    private const TYPE_MESSAGE_MAP = [
        self::TYPE_AIR => [
            self::STATUS_ACTIVATION   => "🔴 УВАГА! ПОВІТРЯНА ТРИВОГА!\r\n\r\n🔴 ВСІ В УКРИТТЯ!",
            self::STATUS_DEACTIVATION => "🟢 УВАГА! Відбій повітряної тривоги!\r\n\r\n🟢 Можна виходити з укриття",
        ],

        self::TYPE_ARTILLERY => [
            self::STATUS_ACTIVATION   => "🔴 УВАГА! ЗАГРОЗА ЗАСТОСУВАННЯ АРТИЛЕРІЇ!\r\n\r\n🔴 ВСІ В УКРИТТЯ!",
            self::STATUS_DEACTIVATION => "🟢 УВАГА! Відбій артилерійської загрози!\r\n\r\n🟢 Можна виходити з укриття",
        ],

        self::TYPE_URBAN_FIGHTS => [
            self::STATUS_ACTIVATION   => "🔴 УВАГА! МОЖЛИВІ ВУЛИЧНІ БОЇ\r\n\r\n🔴 ВСІ В УКРИТТЯ!",
            self::STATUS_DEACTIVATION => "🟢 УВАГА! Зазгрози вуличних боїв нема!\r\n\r\n🟢 Можна виходити з укриття",
        ],

        self::TYPE_CHEMICAL => [
            self::STATUS_ACTIVATION   => "🔴 УВАГА! ХІМІЧНА НЕБЕЗПЕКА\r\n\r\n🔴 ВСІ В УКРИТТЯ!",
            self::STATUS_DEACTIVATION => "🟢 УВАГА! Відбій хімічної небезпеки!\r\n\r\n🟢 Можна виходити в укриття",
        ],

        self::TYPE_NUCLEAR => [
            self::STATUS_ACTIVATION   => "🔴 УВАГА! РАДІАЦІЙНА НЕБЕЗПЕКА\r\n\r\n🔴 ВСІ В УКРИТТЯ!",
            self::STATUS_DEACTIVATION => "🟢 УВАГА! Відбій радіаційної небезпеки!\r\n\r\n🟢 Можна виходити в укриття",
        ],
    ];

    public function composeMessage(string $status, string $type): string
    {
        $status = strtolower($status);
        $type = strtolower($type);

        return self::TYPE_MESSAGE_MAP[$type][$status];
    }
}