<?php

namespace Drupal\silly_field_formatters\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\Annotation\FieldFormatter;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\silly_field_formatters\MySillyServices\SillyRotThirteenService;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Plugin implementation of the 'rot13_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "rot13_field_formatter",
 *   module = "silly_field_formatters",
 *   label = @Translation("Silly Rot13 formatter"),
 *   field_types = {
 *     "string",
 *     "string_long",
 *     "text",
 *     "text_long",
 *     "text_with_summary",
 *   }
 * )
 */
class RotThirteenFormatter extends FormatterBase
{

  /**
   * The Rot13 Service Manager
   *
   * @var
   */
    protected $rotThirteenLogic;

  /**
   * Construct a MyFormatter object.
   *
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   Defines an interface for entity field definitions.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param
   *
   */
    public function __construct(
        $plugin_id,
        $plugin_definition,
        FieldDefinitionInterface $field_definition,
        array $settings,
        $label,
        $view_mode,
        array $third_party_settings,
        SillyRotThirteenService  $rotThirteenLogic
    ) {
        parent::__construct(
            $plugin_id,
            $plugin_definition,
            $field_definition,
            $settings,
            $label,
            $view_mode,
            $third_party_settings
        );


        $this->rotThirteenLogic = $rotThirteenLogic;
    }



  /**
   * {@inheritdoc}
   */
    public function viewElements(FieldItemListInterface $items, $langcode): array
    {
        $elements = [];

        foreach ($items as $delta => $item) {
            if (isset($item)) {
                $elements[$delta] = ['#markup' => $this->rotThirteenLogic->myRot13Algorithm($this->viewValue($item))];
            }
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

    public static function create(
        ContainerInterface $container,
        array $configuration,
        $plugin_id,
        $plugin_definition
    ) {
        return new static(
            $plugin_id,
            $plugin_definition,
            $configuration['field_definition'],
            $configuration['settings'],
            $configuration['label'],
            $configuration['view_mode'],
            $configuration['third_party_settings'],
            // injecting my rot13 service
            $container->get('rot13_client')
        );
    }
}
