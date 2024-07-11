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
        $query->join('field_data_field_accompanying_record', 'fac', 'n.nid = fac.entity_id AND fac.deleted = 0');
        $query->join('field_data_field_issuing_body', 'fib', 'n.nid = fib.entity_id AND fib.deleted = 0');
        $query->join('taxonomy_term_data', 'ttd', 'fib.field_issuing_body_tid = ttd.tid');

        $query->addField('n', 'nid', 'nid');
        $query->addField('n', 'title', 'title');
        $query->addField('fac', 'field_accompanying_record_value', 'field_accompanying_record_value');
        $query->addField('ttd', 'name', 'issuing_body_name');
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
          'field_accompanying_record_value' => 'field_accompanying_record_value',
          'issuing_body_name' => 'issuing_body_name',
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
