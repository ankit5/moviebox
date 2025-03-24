<?php

declare(strict_types=1);

namespace Drupal\views_rest_field_format\Plugin\views\row;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\rest\Plugin\views\row\DataFieldRow;
use Drupal\views\Attribute\ViewsRow;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ViewExecutable;

/**
 * Plugin which displays fields as raw data.
 *
 * @ingroup views_row_plugins
 */
#[ViewsRow(
  id: 'formatted_data_field',
  title: new TranslatableMarkup('Formatted Fields'),
  help: new TranslatableMarkup('Use fields as row data with format options.'),
  display_types: ['data']
)]
class FormattedDataFieldRow extends DataFieldRow {

  /**
   * Array of available formats.
   */
  protected array $formats = [];

  /**
   * {@inheritdoc}
   */
  public function init(
    ViewExecutable $view,
    DisplayPluginBase $display,
    ?array &$options = NULL,
  ) {
    parent::init($view, $display, $options);

    if (!empty($this->options['field_options'])) {
      $this->formats = static::extractFromOptionsArray('format', $options);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['field_options']['#header'][] = $this->t('Format');
    $options = $this->options['field_options'];

    if ($fields = $this->view->display_handler->getOption('fields')) {
      foreach ($fields as $id => $field) {
        // Don't show the field if it has been excluded.
        if (!empty($field['exclude'])) {
          continue;
        }

        $form['field_options'][$id]['format'] = [
          '#title' => $this->t('Format for @id', ['@id' => $id]),
          '#title_display' => 'invisible',
          '#type' => 'select',
          '#options' => [
            'string' => $this->t('String'),
            'json_string' => $this->t('JSON String'),
            'boolean' => $this->t('Boolean'),
            'int' => $this->t('Integer'),
            'float' => $this->t('Float'),
          ],
          '#default_value' => $options[$id]['format'] ?? 'string',
        ];
      }
    }
  }

  /**
   * Renders the row values according to set format.
   *
   * Sets the value to NULL if field value is empty.
   *
   * {@inheritdoc}
   */
  public function render($row) {
    $output = [];

    foreach ($this->view->field as $id => $field) {
      $rawValue = $field->getValue($row);
      $fieldEmpty = $rawValue === FALSE;

      // If the raw output option has been set, just get the raw value.
      if (!empty($this->rawOutputOptions[$id])) {
        $value = $rawValue;
      }
      // Otherwise, get rendered field.
      else {
        $format = $this->formats[$id] ?? NULL;

        switch ($format) {
          case 'boolean':
            $value = (boolean) $rawValue;
            break;

          case 'int':
            $value = $fieldEmpty ? NULL : (int) $rawValue;
            break;

          case 'float':
            $value = $fieldEmpty ? NULL : (float) $rawValue;
            break;

          case 'json_string':
            $value = Json::decode($this->renderValue($field, $row));
            break;

          case 'string':
          default:
            $value = $fieldEmpty ? NULL : $this->renderValue($field, $row);

            break;
        }
      }

      // Omit excluded fields from the rendered output.
      if (empty($field->options['exclude'])) {
        $output[$this->getFieldKeyAlias($id)] = $value;
      }
    }

    return $output;
  }

  /**
   * Render a field value to string.
   */
  protected function renderValue(FieldPluginBase $field, object $row): MarkupInterface|string|null {
    // Advanced render for token replacement.
    $markup = $field->advancedRender($row);
    // Post render to support uncacheable fields.
    $field->postRender($row, $markup);

    return $field->last_render;
  }

}
