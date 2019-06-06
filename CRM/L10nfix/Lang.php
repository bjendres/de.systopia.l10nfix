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

use CRM_L10nfix_ExtensionUtil as E;

/**
 * Base class for the language related fixes
 */
abstract class CRM_L10nfix_Lang {

  /**
   * Get a (cached) fixer instance for the given locale
   *
   * @param $locale string locale
   * @return CRM_L10nfix_Lang fixer
   */
  public static function getFixer($locale) {
    static $fixers = [];
    if (!isset($fixers[$locale])) {
      $class_name = "CRM_L10nfix_Lang_" . strtoupper(preg_replace('/_/', '', $locale));
      if (class_exists($class_name)) {
        // there is a fixer for this language!
        $fixers[$locale] = new $class_name();
      } else {
        // there is no fixer
        $fixers[$locale] = 'n/a';
      }
    }

    $fixer = $fixers[$locale];
    if ($fixer == 'n/a') {
      return NULL;
    } else {
      return $fixer;
    }
  }

  /**
   * Get the file and line of the caller of the ts function
   *
   * @param int $stack_offset  if you are calling from the fixTranslation function, you can leave this at 0,
   *                             but if you are in a subfunction, you have to increase it
   */
  public function getCaller($stack_offset = 0) {
    $stack_depth = 9; // 9 is the default call stack offset when called from the fixTranslation function directly
    $stack_depth += $stack_offset;
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, $stack_depth + 1);
    return $backtrace[$stack_depth];
  }

  /**
   * Check if the given caller object (from the getCaller function) is from
   *  a certain CiviCRM Core file within the given line range
   *
   * @param $caller        array caller info from the getCaller function
   * @param $civicrm_file  string class path relative to the civicrm module
   * @param $min_line      int minimum line
   * @param $max_line      int maximum line. if NULL will only use min_line
   */
  public function isFromCore($caller, $civicrm_file, $min_line, $max_line = NULL) {
    if ($max_line === NULL) {
      $max_line = $min_line;
    }

    return ($caller['line'] >= $min_line)
        && ($caller['line'] <= $max_line)
        && (substr_compare($caller['file'], $civicrm_file, -strlen($civicrm_file), strlen($civicrm_file)) == 0);
  }

  /**
   * Fix the given translation.
   *
   * Be careful with performance issues, this function is called A LOT!
   *
   * @param $original_text    string original language string
   * @param $translated_text  string current translation
   * @param $params           array ts parameters like domain and context
   */
  abstract public function fixTranslation($original_text, &$translated_text, &$params);

}
