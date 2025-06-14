<?php

namespace Drupal\movie\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\filter\FilterPluginBase;

/**
 * Filters nodes by year of publish date.
 *
 * @ViewsFilter("year_filter")
 */
class YearFilter extends FilterPluginBase {

  /**
   * {@inheritdoc}
   */
  public function adminSummary() {
    return $this->t('Filters nodes by year of publish date.');
  }

  /**
   * {@inheritdoc}
   */
  protected function valueForm(&$form, FormStateInterface $form_state) {
    $current_year = date('Y');
    $options = [];
    $options[''] = 'All';
    for ($year = $current_year; $year >= 1900; $year--) {
      $options[$year] = $year;
    }

    $form['value'] = [
      '#type' => 'select',
      '#title' => $this->t('Year'),
      '#options' => $options,
      '#default_value' => $current_year,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
   // $this->ensureMyTable();

    /** @var \Drupal\views\Plugin\views\query\Sql $query */
    $query = $this->query;
   // $table = array_key_first($query->tables);

    if (!empty($this->value[0])) {
      $value = $this->value[0]-1;
      $query->addField('node__field_releasedate', 'field_releasedate_value', 'field_releasedate_value');
 //$query->setWhereGroup('AND', 1);
 //$query->addWhere(1, "node__field_releasedate.field_releasedate_value", '106', "!=");
 //EXTRACT(year FROM creation_date)
 //$query->addWhere(0, "EXTRACT(year FROM node__field_releasedate.field_releasedate_value)", $this->value[0]);
   //   $query->addWhere(1, "EXTRACT(YEAR FROM FROM_UNIXTIME(node__field_releasedate.field_releasedate_value)) = :year", [':year' => $this->value[0]]);
   $this->query->addWhereExpression(1, "YEAR(FROM_UNIXTIME(node__field_releasedate.field_releasedate_value)) = $value"); 
  }
    
  }

}