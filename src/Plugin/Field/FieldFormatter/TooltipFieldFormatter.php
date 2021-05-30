<?php

namespace Drupal\silly_field_formatters\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\Annotation\FieldFormatter;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'tooltip_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "tooltip_field_formatter",
 *   label = @Translation("Tooltip field formatter"),
 *   field_types = {
 *     "string",
 *     "string_long",
 *     "text",
 *     "text_long",
 *     "text_with_summary",
 *   }
 * )
 */
class TooltipFieldFormatter extends FormatterBase
{

  /**
   * {@inheritdoc}
   */
    public function viewElements(FieldItemListInterface $items, $langcode)
    {
        $elements = [];

        foreach ($items as $delta => $item) {
            $elements[$delta] = [
            '#theme' => 'silly_field_formatters_tooltip',
            '#markup' => $this->viewValue($item),
            '#attached' => [
              'library' => [
                'silly_field_formatters/silly-library',
              ]
            ]
            ];
        }
        return $elements;
    }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
    protected function viewValue(FieldItemInterface $item): string
    {
        return nl2br(Html::escape($item->value));
    }

}
