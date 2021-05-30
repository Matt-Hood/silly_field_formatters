<?php

namespace Drupal\silly_field_formatters\MySillyServices;

class SillyRotThirteenService
{

    public function myRot13AlgorithmHelper($wholeWord)
    {
      /**
       * creates an alphabet array
       */
        foreach (range('a', 'z') as $elements) {
            $alphabetArray[] = $elements;
        }

      /**
       * handles edge case when there is a blank space
       */
        if ($wholeWord === ' ') {
            return $wholeWord;
        }

      /* Checks if the letter passed is in the alphabet else return '?' */
        if (!in_array(strtolower($wholeWord), $alphabetArray)) {
            return '?';
        }
      /**
       * handles logic for when the letter is past the 13th index in the array
       * includes logic for handling capital vs non capital letters
       */
        $key = array_search(strtolower($wholeWord), $alphabetArray, true);

        /* handles logic if letter is at the 13th index  */
        if ($key === 13) {
            return $alphabetArray[25];
        }

        if ($key > 13) {
            $key -= 13;
            if (strtolower($wholeWord) === $wholeWord) {
                return $alphabetArray[$key];
            }
                return strtoupper($alphabetArray[$key]);
        }

      /**
       * handles logic for when the letter is before or at the 13th index in the array
       * includes logic for handling capital vs non capital letters
       */
        $key += 13;

        if (strtolower($wholeWord) === $wholeWord) {
            return $alphabetArray[$key];
        }
        return strtoupper($alphabetArray[$key]);
    }

    public function myRot13Algorithm($word): string
    {
        $result = array_map(array($this, 'myRot13AlgorithmHelper'), str_split($word));
        return implode($result);
    }
}
