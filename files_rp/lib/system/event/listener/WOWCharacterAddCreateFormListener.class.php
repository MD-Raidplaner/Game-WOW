<?php

namespace rp\system\event\listener;

use rp\event\character\CharacterAddCreateForm;
use rp\system\cache\eager\ClassificationCache;
use rp\system\cache\eager\ServerCache;
use rp\system\form\builder\field\DynamicSelectFormField;
use rp\system\race\RaceHandler;
use rp\system\skill\SkillHandler;
use wcf\system\form\builder\field\IntegerFormField;
use wcf\system\form\builder\field\SingleSelectionFormField;
use wcf\system\form\builder\field\validation\FormFieldValidationError;
use wcf\system\form\builder\field\validation\FormFieldValidator;

/**
 * Creates the character equipment form.
 * 
 * @author  Marco Daries
 * @copyright   2025 MD-Raidplaner
 * @license MD-Raidplaner is licensed under Creative Commons Attribution-ShareAlike 4.0 International
 */
final class WOWCharacterAddCreateFormListener
{
    public function __invoke(CharacterAddCreateForm $event)
    {
        $section = $event->form->getNodeById('characterGeneralSection');
        $section->appendChildren([
            SingleSelectionFormField::create('race')
                ->label('rp.race.title')
                ->required()
                ->options(['' => 'wcf.global.noSelection'] + RaceHandler::getInstance()->getRaces())
                ->addValidator(new FormFieldValidator('check', function (SingleSelectionFormField $formField) {
                    $value = $formField->getSaveValue();

                    if (empty($value)) {
                        $formField->addValidationError(new FormFieldValidationError('empty'));
                    }
                })),
            DynamicSelectFormField::create('classificationID')
                ->label('rp.classification.title')
                ->required()
                ->options((new ClassificationCache())->getCache()->getClassifications())
                ->triggerSelect('race')
                ->optionsMapping(static function () {
                    $races = (new ClassificationCache())->getCache()->getClassificationRaces();

                    $mapping = \array_reduce(\array_keys($races), function ($carry, $classificationID) use ($races) {
                        foreach ($races[$classificationID] as $raceID) {
                            $carry[$raceID][] = $classificationID;
                        }
                        return $carry;
                    }, []);

                    return $mapping;
                })
                ->addValidator(new FormFieldValidator('check', function (SingleSelectionFormField $formField) {
                    $value = $formField->getSaveValue();

                    if (empty($value)) {
                        $formField->addValidationError(new FormFieldValidationError('empty'));
                    }
                })),
            DynamicSelectFormField::create('talent1')
                ->label('rp.character.wow.talent.primary')
                ->required()
                ->options(SkillHandler::getInstance()->getSkills())
                ->triggerSelect('classificationID')
                ->optionsMapping((new ClassificationCache())->getCache()->getClassificationSkills())
                ->addValidator(new FormFieldValidator('check', function (SingleSelectionFormField $formField) {
                    $value = $formField->getSaveValue();

                    if (empty($value)) {
                        $formField->addValidationError(new FormFieldValidationError('empty'));
                    }
                })),
            DynamicSelectFormField::create('talent2')
                ->label('rp.character.wow.talent.secondary')
                ->nullable()
                ->options(SkillHandler::getInstance()->getSkills())
                ->triggerSelect('classificationID')
                ->optionsMapping((new ClassificationCache())->getCache()->getClassificationSkills()),
            IntegerFormField::create('level')
                ->label('rp.character.wow.level')
                ->required()
                ->minimum(1)
                ->maximum(80)
                ->value(0),
            SingleSelectionFormField::create('serverID')
                ->label('rp.server.title')
                ->options(['' => 'wcf.global.noSelection'] + (new ServerCache())->getCache()->getServers())
                ->nullable(),
        ]);
    }
}
