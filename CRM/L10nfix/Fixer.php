<?php
/*-------------------------------------------------------+
| L10n Localisation Fixes                                |
| Copyright (C) 2019 SYSTOPIA                            |
| Author: B. Endres (endres@systopia.de)                 |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

use Civi\API\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \Civi\Core\Event\GenericHookEvent;

/**
 * Injects custom MO files according to the configuration
 */
class CRM_L10nfix_Fixer implements EventSubscriberInterface {

  /**
   * Define which events we subscribe to
   * @return array
   */
  public static function getSubscribedEvents() {
    return array(
        'civi.l10n.ts_post' => array(
            array('customTranslation', Events::W_EARLY),
        ),
    );
  }

  /**
   * Inject custom MO files according to the configuration
   *
   * @param GenericHookEvent $ts_event mo event
   */
  public function customTranslation(GenericHookEvent $ts_event) {
    $fixer = CRM_L10nfix_Lang::getFixer($ts_event->locale);
    if ($fixer) {
      $fixer->fixTranslation($ts_event->original_text, $ts_event->translated_text, $ts_event->params);
    }
  }

}
