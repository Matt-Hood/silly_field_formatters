<?php

namespace Drupal\silly_field_formatters\MySillyServices;

class SillyRotThirteenService {

  public function myRot13AlgorithmHelper($wholeWord, $alphabetArray = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'))
  {

    if ($wholeWord == ' ') {
      $encryptedLetter = ' ';
      return $encryptedLetter;
    }

    $key = array_search(strtolower($wholeWord), $alphabetArray);

    if ($key > 13) {
      $key -= 13;
      $encryptedLetter = $alphabetArray[$key];

      return $encryptedLetter;
    }
    $key += 13;
    $encryptedLetter = $alphabetArray[$key];

    if (strtolower($wholeWord) == $wholeWord) {
      return $encryptedLetter;
    }
    return strtoupper($encryptedLetter);
  }

  public function myRot13Algorithm($word)
  {
    $result = array_map(array($this, 'myRot13AlgorithmHelper'), str_split($word));
    $result = implode($result);
    return $result;
  }
}
