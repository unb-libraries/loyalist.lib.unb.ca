<?php

namespace Drupal\loyalist_migrate\Plugin\migrate\source;

use Drupal\migrate\Annotation\MigrateSource;
use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Migrates Loyalist Items.
 *
 * @MigrateSource(
 *   id = "loyalist_item",
 *   source_module = "loyalist_migrate",
 * )
 */
class LoyalistItem extends SqlBase
{
  /**
   * {@inheritdoc}
   */
    public function query()
    {
        $query = $this->select('node', 'n');
        $query->condition('n.type', 'loyalist_record');
        $query->addField('n', 'nid', 'nid');
        $query->addField('n', 'title', 'title');

        $query->leftJoin('field_data_body', 'fbody', 'n.nid = fbody.entity_id AND fbody.deleted = 0');
        $query->addField('fbody', 'body_value', 'body');

        $query->leftJoin('field_data_field_accompanying_record', 'fac', 'n.nid = fac.entity_id AND fac.deleted = 0');
        $query->addField('fac', 'field_accompanying_record_value', 'field_accompanying_record_value');

        $query->leftJoin('field_data_field_other_numbers', 'fon', 'n.nid = fon.entity_id AND fon.deleted = 0');
        $query->addField('fon', 'field_other_numbers_value', 'field_other_numbers_value');
        $query->addField('fon', 'field_other_numbers_format', 'field_other_numbers_format');

        $query->leftJoin('field_data_field_issuing_body', 'fib', 'n.nid = fib.entity_id AND fib.deleted = 0');
        $query->leftJoin('taxonomy_term_data', 'ttdfib', 'fib.field_issuing_body_tid = ttdfib.tid');
        $query->addField('ttdfib', 'name', 'issuing_body_name');

        $query->leftJoin('field_data_field_subject_heading', 'fsh', 'n.nid = fsh.entity_id AND fsh.deleted = 0');
        $query->leftJoin('taxonomy_term_data', 'ttdfsh', 'fsh.field_subject_heading_tid = ttdfsh.tid');
        $query->addField('ttdfsh', 'name', 'subject_heading_name');

        $query->leftJoin('field_data_field_call_number', 'fcn', 'n.nid = fcn.entity_id AND fcn.deleted = 0');
        $query->addField('fcn', 'field_call_number_value', 'field_call_number_value');

        return $query;
    }

  /**
   * {@inheritdoc}
   */
    public function fields()
    {
        // This maps the field from their name above to a destination field name that is specified in the process section. I generally keep them the same.
        $fields = [
          'title'   => 'title',
          'nid' => 'nid',
          'field_accompanying_record_value' => 'field_accompanying_record_value',
          'issuing_body_name' => 'issuing_body_name',
          'subject_heading_name' => 'subject_heading_name',
          'field_other_numbers_value' => 'field_other_numbers_value',
          'field_other_numbers_format' => 'field_other_numbers_format',
          'body' => 'body',
        ];
        return $fields;
    }

  /**
   * {@inheritdoc}
   */
    public function getIds()
    {
        return [
          'nid' => [
            'type' => 'integer',
            'alias' => 'n',
          ],
        ];
    }

  /**
   * {@inheritdoc}
   */
    public function prepareRow(Row $row)
    {
        /*
        Example of how to manipulate data using prepareRow.
        We should generally use process plugins in the yaml definition instead.

        $date = $row->getSourceProperty('date');
        $time = $row->getSourceProperty('time');
        $datetime = $date . 'T' . $time . ':00';
        $row->setSourceProperty('datetime', $datetime);
        */
        return parent::prepareRow($row);
    }
}