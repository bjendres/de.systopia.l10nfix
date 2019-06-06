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
| copyright header is strictly prohibited without        |`
| written permission from the original author(s).        |
+--------------------------------------------------------*/

use CRM_L10nfix_ExtensionUtil as E;

/**
 * German (de_DE) language localisation fixes
 */
class CRM_L10nfix_Lang_DEDE extends CRM_L10nfix_Lang {

  /**
   * Fix the given translation.
   *
   * Be careful with performance issues, this function is called A LOT!
   *
   * @param $original_text    string original language string
   * @param $translated_text  string current translation
   * @param $params           array ts parameters like domain and context
   */
  public function fixTranslation($original_text, &$translated_text, &$params) {
    // first fix: identify the locations where 'To' is meant in a 'from/to' range context (von/bis)
    //   instead of 'email to' (E-Mail an). Give a little bit of leeway with the line numbers, so it works
    //   on different CiviCRM versions.
    if ($original_text == 'To') {
      $caller = $this->getCaller();
      if (   $this->isFromCore($caller, 'CRM/Contribute/BAO/Query.php', 930, 940)
          || $this->isFromCore($caller, 'CRM/Core/BAO/CustomField.php', 880, 1000)
          || $this->isFromCore($caller, 'CRM/Case/Form/CaseView.php', 487, 500)
          || $this->isFromCore($caller, 'CRM/Pledge/BAO/Query.php', 540, 560)
          || $this->isFromCore($caller, 'CRM/Event/BAO/Query.php', 635, 650)
          || $this->isFromCore($caller, 'CRM/Event/Form/SearchEvent.php', 75, 80)
          || $this->isFromCore($caller, 'CRM/Contact/Form/Search/Criteria.php', 390, 410)) {
        // match:
        $translated_text = "Bis";
      }
    }
  }
}
