<?php
/**
* Actionbox - List of surfers by topic tag
*
* Displaying surfers online list
*
* @copyright 2002-2017 by dimensional GmbH - All rights reserved.
* @link http://www.papaya-cms.com/
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, version 2
*
* You can redistribute and/or modify this script under the terms of the GNU General Public
* License (GPL) version 2, provided that the copyright and license notes, including these
* lines, remain unmodified. papaya is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.
*
* @package Papaya-Modules
* @subpackage _Base-Community
* @version $Id: actbox_surfers_online.php 39600 2014-03-18 11:43:38Z weinert $
*/

/**
* Actionbox - List of surfers by topic tag
*
* @package Papaya-Modules
* @subpackage _Base-Community
*/
class actionbox_topic_tag_surfers extends base_actionbox {
  /**
  * Edit fields
  * @var array
  */
  public $editFields = [
    'title' => [
      'Title',
      'isNoHTML',
      FALSE,
      'input',
      100
    ],
    'text' => [
      'Text',
      'isSomeText',
      FALSE,
      'richtext',
      7
    ],
    'include_avatars' => [
      'Include avatars?',
      'isNum',
      TRUE,
      'radio',
      [0 => 'No', 1 => 'Yes'],
      '',
      0
    ],
    'Captions',
    'caption_surfer_handle' => [
      'User name',
      'isNoHTML',
      TRUE,
      'input',
      100,
      '',
      'User name'
    ],
    'caption_surfer_givenname' => [
      'Given name',
      'isNoHTML',
      TRUE,
      'input',
      100,
      '',
      'Given name'
    ],
    'caption_surfer_surname' => [
      'Surname',
      'isNoHTML',
      TRUE,
      'input',
      100,
      '',
      'Surname'
    ],
    'caption_surfer_email' => [
      'Email',
      'isNoHTML',
      TRUE,
      'input',
      100,
      '',
      'Email'
    ],
    'caption_surfer_gender' => [
      'Gender',
      'isNoHTML',
      TRUE,
      'input',
      100,
      '',
      'Gender'
    ],
    'caption_gender_m' => [
      'Male',
      'isNoHTML',
      TRUE,
      'input',
      100,
      '',
      'Male'
    ],
    'caption_gender_f' => [
      'Female',
      'isNoHTML',
      TRUE,
      'input',
      100,
      '',
      'Female'
    ],
    'caption_avatar' => [
      'Avatar',
      'isNoHTML',
      TRUE,
      'input',
      100,
      '',
      'Avatar'
    ]
  ];

  /**
   * surfer_admin object
   * @var surfer_admin
   */
  private $_surferAdmin = NULL;

  /**
   * Get box content
   *
   * @return string XML
   */
  public function getParsedData() {
    $this->setDefaultData();
    $surferIds = $this->surferAdmin()->getIdsByTopicTags($this->parentObj->topicId);
    $data = $this->surferAdmin()->getBasicDataById($surferIds);
    $fields = $this->surferAdmin()->getDataFieldNames();
    $dynamicData = $this->surferAdmin()->getDynamicData($surferIds, $fields);
    $dynamicDataTitles = $this->surferAdmin()->getDynamicDataTitles(
      $this->papaya()->request->languageId
    );
    $avatars = [];
    if (isset($this->data['include_avatars']) && $this->data['include_avatars'] == 1) {
      $avatars = $this->surferAdmin()->getAvatar($surferIds);
    }
    $result = '<surfers>'.LF;
    if (isset($this->data['title'])) {
      $result .= sprintf(
        '<title>%s</title>'.LF,
        papaya_strings::escapeHTMLChars($this->data['title'])
      );
    }
    if (isset($this->data['text'])) {
      $result .= sprintf(
        '<text>%s</text>'.LF,
        $this->getXHTMLString($this->data['text'])
      );
    }
    foreach ($data as $surferId => $surferData) {
      $result .= sprintf('<surfer id="%s">'.LF, $surferId);
      foreach ($surferData as $field => $value) {
        $additional = '';
        $title = isset($this->data['caption_'.$field]) ?
          papaya_strings::escapeHTMLChars($this->data['caption_'.$field]) :
          papaya_strings::escapeHTMLChars($field);
        if ($field == 'surfer_gender') {
          if ($value == 'f' && isset($this->data['caption_gender_f'])) {
            $value = $this->data['caption_gender_f'];
            $additional = ' rawvalue="f"';
          } elseif ($value == 'm' && isset($this->data['caption_gender_m'])) {
            $value = $this->data['caption_gender_m'];
            $additional = ' rawvalue="m"';
          }
        }
        $result .= sprintf(
          '<field name="%s" title="%s" value="%s"%s/>'.LF,
          papaya_strings::escapeHTMLChars($field),
          $title,
          papaya_strings::escapeHTMLChars($value),
          $additional
        );
      }
      if (isset($dynamicData[$surferId])) {
        foreach ($dynamicData[$surferId] as $field => $value) {
          $title = isset($dynamicDataTitles[$field]) ?
            papaya_strings::escapeHTMLChars($dynamicDataTitles[$field]) :
            papaya_strings::escapeHTMLChars($field);
          if (is_array($value)) {
            $result .= sprintf(
              '<field name="%s" title="%s">'.LF.'%s</field>'.LF,
              papaya_strings::escapeHTMLChars($field),
              $title,
              $this->prepareValue($value)
            );
          } else {
            $result .= sprintf(
              '<field name="%s" title="%s" value="%s"/>'.LF,
              papaya_strings::escapeHTMLChars($field),
              $title,
              papaya_strings::escapeHTMLChars($value)
            );
          }
        }
      }
      if (isset($avatars[$surferId])) {
        $title = isset($this->data['caption_avatar']) ?
          papaya_strings::escapeHTMLChars($this->data['caption_avatar']) :
          'avatar';
        $result .= sprintf(
          '<field name="avatar" title="%s" value="%s"/>'.LF,
          $title,
          $avatars[$surferId]
        );
      }
      $result .= '</surfer>'.LF;
    }
    $result .= '</surfers>'.LF;
    return $result;
  }

  /**
   * Get/set the surfer_admin object
   *
   * @param surfer_admin $surferAdmin
   * @return surfer_admin
   */
  public function surferAdmin($surferAdmin = NULL) {
    if ($surferAdmin !== NULL) {
      $this->_surferAdmin = $surferAdmin;
    } elseif ($this->_surferAdmin === NULL) {
      $this->_surferAdmin = surfer_admin::getInstance();
    }
    return $this->_surferAdmin;
  }

  /**
  * Prepare array value
  *
  * @param array $data
  * @return string
  */
  private function prepareValue($data) {
    $result = '';
    foreach ($data as $field => $value) {
      if (is_array($value)) {
        $result .= sprintf(
          '<field name="%s">%s</field>'.LF,
          papaya_strings::escapeHTMLChars($field),
          $this->prepareValue($value)
        );
      } else {
        $result .= sprintf(
          '<field name="%s" value="%s"/>'.LF,
          papaya_strings::escapeHTMLChars($field),
          papaya_strings::escapeHTMLChars($value)
        );
      }
    }
    return $result;
  }
}