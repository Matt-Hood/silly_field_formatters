<?php

namespace Drupal\silly_field_formatters\Plugin\Field\FieldFormatter;

use Cocur\Slugify\Slugify;
use Drupal\Component\Utility\Html;
use Drupal\Core\Field\Annotation\FieldFormatter;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\silly_field_formatters\MySillyServices\SillyRotThirteenService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Plugin implementation of the 'slugify_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "slugify_field_formatter",
 *   label = @Translation("Slugify field formatter"),
 *   field_types = {
 *     "string",
 *     "string_long",
 *     "text",
 *     "text_long",
 *     "text_with_summary",
 *   }
 * )
 */
class SlugifyFieldFormatter extends FormatterBase
{

  /**
   * The slugify manager service
   *
   * @var
   */
    protected $slugifyLogic;

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
        $slugifyLogic
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


        $this->slugifyLogic = $slugifyLogic;
    }


  /**
   * {@inheritdoc}
   */
    public static function defaultSettings()
    {
        return [
      // Implement default settings.
        'seperator' => '-',
        ] + parent::defaultSettings();
    }

  /**
   * {@inheritdoc}
   */
    public function settingsForm(array $form, FormStateInterface $form_state)
    {
        $form['seperator'] = [
          '#title' => $this->t('seperator'),
          '#type' => 'textfield',
          '#size' => 5,
          '#maxlength' =>1,
          '#default_value' => $this->getSetting('seperator'),
        ];

        return $form;
    }

  /**
   * {@inheritdoc}
   */
    public function settingsSummary(): array
    {
        $summary = [];
        // Implement settings summary.
        $summary[] = $this->getSetting('seperator') ? t('The current seperator being used is:'). ' '.
          $this->getSetting('seperator') :
          t('The default seperator is being used');
        return $summary;
    }

  /**
   * {@inheritdoc}
   */
    public function viewElements(FieldItemListInterface $items, $langcode)
    {
        $elements = [];

        foreach ($items as $delta => $item) {
            $elements[$delta] = ['#markup' => $this->slugifyLogic->slugify(
                $this->viewValue($item),
                $this->getSetting('seperator')
            )];
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
    protected function viewValue(FieldItemInterface $item)
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
            //injecting slugify service
            $container->get('slugify_client')
        );
    }
}
