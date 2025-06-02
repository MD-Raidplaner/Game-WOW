<?php

use rp\event\character\AvailableCharactersChecking;
use rp\event\character\CharacterAddCreateForm;
use rp\event\event\EventCreateForm;
use rp\event\faction\FactionCollecting;
use rp\event\game\GameCollecting;
use rp\event\race\RaceCollecting;
use rp\event\raid\AddAttendeesChecking;
use rp\event\role\RoleCollecting;
use rp\event\skill\SkillCollecting;
use rp\system\cache\eager\GameCache;
use rp\system\event\listener\DefaultAddAttendeesChecking;
use rp\system\event\listener\WOWAvailableCharactersChecking;
use rp\system\event\listener\WOWCharacterAddCreateFormListener;
use rp\system\event\listener\WOWEventCreateFormListener;
use rp\system\faction\FactionItem;
use rp\system\game\GameItem;
use rp\system\race\RaceItem;
use rp\system\role\RoleItem;
use rp\system\skill\SkillItem;
use wcf\system\event\EventHandler;

return static function (): void {
    if ((new GameCache())->getCache()->getCurrentGame()->identifier !== 'wow') return;

    $eventHandler = EventHandler::getInstance();

    $eventHandler->register(AddAttendeesChecking::class, DefaultAddAttendeesChecking::class);
    $eventHandler->register(AvailableCharactersChecking::class, WOWAvailableCharactersChecking::class);
    $eventHandler->register(CharacterAddCreateForm::class, WOWCharacterAddCreateFormListener::class);
    $eventHandler->register(EventCreateForm::class, WOWEventCreateFormListener::class);

    $eventHandler->register(GameCollecting::class, static function (GameCollecting $event) {
        $event->register(new GameItem('wow'));
    });

    $eventHandler->register(FactionCollecting::class, static function (FactionCollecting $event) {
        $event->register(new FactionItem('alliance', 'wow', 'alliance'));
        $event->register(new FactionItem('horde', 'wow', 'horde'));
    });

    $eventHandler->register(RaceCollecting::class, static function (RaceCollecting $event) {
        $event->register(new RaceItem(
            'bloodElf',
            'wow',
            factions: ['horde']
        ));
        $event->register(new RaceItem(
            'darkIronDwarf',
            'wow',
            factions: ['alliance']
        ));
        $event->register(new RaceItem(
            'dracthyr',
            'wow',
            factions: [
                'alliance',
                'horde'
            ]
        ));
        $event->register(new RaceItem(
            'draenei',
            'wow',
            factions: ['alliance']
        ));
        $event->register(new RaceItem(
            'dwarf',
            'wow',
            factions: ['alliance']
        ));
        $event->register(new RaceItem(
            'gnome',
            'wow',
            factions: [
                'alliance',
                'horde'
            ]
        ));
        $event->register(new RaceItem(
            'goblin',
            'wow',
            factions: ['horde']
        ));
        $event->register(new RaceItem(
            'highmountainTauren',
            'wow',
            factions: ['horde']
        ));
        $event->register(new RaceItem(
            'human',
            'wow',
            factions: ['alliance']
        ));
        $event->register(new RaceItem(
            'kulTiran',
            'wow',
            factions: ['alliance']
        ));
        $event->register(new RaceItem(
            'lightforgedDraenei',
            'wow',
            factions: ['alliance']
        ));
        $event->register(new RaceItem(
            'magharOrc',
            'wow',
            factions: ['horde']
        ));
        $event->register(new RaceItem(
            'mechagnome',
            'wow',
            factions: ['alliance']
        ));
        $event->register(new RaceItem(
            'nightborne',
            'wow',
            factions: ['horde']
        ));
        $event->register(new RaceItem(
            'nightElf',
            'wow',
            factions: ['alliance']
        ));
        $event->register(new RaceItem(
            'orc',
            'wow',
            factions: ['horde']
        ));
        $event->register(new RaceItem(
            'pandaren',
            'wow',
            factions: [
                'alliance',
                'horde'
            ]
        ));
        $event->register(new RaceItem(
            'tauren',
            'wow',
            factions: ['horde']
        ));
        $event->register(new RaceItem(
            'troll',
            'wow',
            factions: ['horde']
        ));
        $event->register(new RaceItem(
            'undead',
            'wow',
            factions: ['horde']
        ));
        $event->register(new RaceItem(
            'voidElf',
            'wow',
            factions: ['alliance']
        ));
        $event->register(new RaceItem(
            'vulpera',
            'wow',
            factions: ['horde']
        ));
        $event->register(new RaceItem(
            'worgen',
            'wow',
            factions: ['alliance']
        ));
        $event->register(new RaceItem(
            'zandalariTroll',
            'wow',
            factions: ['horde']
        ));
    });

    $eventHandler->register(RoleCollecting::class, static function (RoleCollecting $event) {
        $event->register(new RoleItem('ddDistance', 'wow'));
        $event->register(new RoleItem('ddNear', 'wow'));
        $event->register(new RoleItem('healer', 'wow'));
        $event->register(new RoleItem('tank', 'wow'));
    });

    $eventHandler->register(SkillCollecting::class, static function (SkillCollecting $event) {
        $event->register(new SkillItem('affliction', 'wow'));
        $event->register(new SkillItem('arcane', 'wow'));
        $event->register(new SkillItem('arms', 'wow'));
        $event->register(new SkillItem('assassination', 'wow'));
        $event->register(new SkillItem('augmentation', 'wow'));
        $event->register(new SkillItem('balance', 'wow'));
        $event->register(new SkillItem('beastMastery', 'wow'));
        $event->register(new SkillItem('blood', 'wow'));
        $event->register(new SkillItem('brewmaster', 'wow'));
        $event->register(new SkillItem('demonology', 'wow'));
        $event->register(new SkillItem('destruction', 'wow'));
        $event->register(new SkillItem('devastation', 'wow'));
        $event->register(new SkillItem('discipline', 'wow'));
        $event->register(new SkillItem('elemental', 'wow'));
        $event->register(new SkillItem('enhancement', 'wow'));
        $event->register(new SkillItem('feral', 'wow'));
        $event->register(new SkillItem('fire', 'wow'));
        $event->register(new SkillItem('frost', 'wow'));
        $event->register(new SkillItem('frost2', 'wow'));
        $event->register(new SkillItem('fury', 'wow'));
        $event->register(new SkillItem('guardian', 'wow'));
        $event->register(new SkillItem('havoc', 'wow'));
        $event->register(new SkillItem('holy', 'wow'));
        $event->register(new SkillItem('holy2', 'wow'));
        $event->register(new SkillItem('marksmanship', 'wow'));
        $event->register(new SkillItem('mistweaver', 'wow'));
        $event->register(new SkillItem('outlaw', 'wow'));
        $event->register(new SkillItem('preservation', 'wow'));
        $event->register(new SkillItem('protection', 'wow'));
        $event->register(new SkillItem('protection2', 'wow'));
        $event->register(new SkillItem('restoration', 'wow'));
        $event->register(new SkillItem('restoration2', 'wow'));
        $event->register(new SkillItem('retribution', 'wow'));
        $event->register(new SkillItem('shadow', 'wow'));
        $event->register(new SkillItem('subtlety', 'wow'));
        $event->register(new SkillItem('survival', 'wow'));
        $event->register(new SkillItem('unholy', 'wow'));
        $event->register(new SkillItem('vengeance', 'wow'));
        $event->register(new SkillItem('windwalker', 'wow'));
    });
};
