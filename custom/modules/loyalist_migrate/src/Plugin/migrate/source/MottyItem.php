<?php

namespace Drupal\loyalist_migrate\Plugin\migrate\source;

use Drupal\migrate\Annotation\MigrateSource;
use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Migrates Loyalist Items.
 *
 * @MigrateSource(
 *   id = "motty_item",
 *   source_module = "loyalist_migrate",
 * )
 */
class MottyItem extends SqlBase
{
  /**
   * {@inheritdoc}
   */
    public function query()
    {
        $query = $this->select('node', 'n');
        $query->condition('n.type', 'motty_record');
        $query->addField('n', 'nid', 'nid');
        $query->addField('n', 'title', 'title');
        $query->addField('n', 'created', 'created');
        $query->addField('n', 'changed', 'changed');

        $query->leftJoin('field_data_field_event_date', 'fdate', 'n.nid = fdate.entity_id AND fdate.deleted = 0');
        $query->addField('fdate', 'field_event_date_value', 'field_event_date_value');

        $query->leftJoin('field_data_field_event_type', 'fet', 'n.nid = fet.entity_id AND fet.deleted = 0');
        $query->leftJoin('taxonomy_term_data', 'ttde', 'fet.field_event_type_tid = ttde.tid');
        $query->addField('ttde', 'name', 'event_type');

        $query->leftJoin('field_data_field_location', 'fel', 'n.nid = fel.entity_id AND fel.deleted = 0');
        $query->leftJoin('taxonomy_term_data', 'ttdl', 'fel.field_location_tid = ttdl.tid');
        $query->addField('ttdl', 'name', 'event_location');

        $query->leftJoin('field_data_field_first_name', 'ffn', 'n.nid = ffn.entity_id AND ffn.deleted = 0');
        $query->addField('ffn', 'field_first_name_value', 'field_first_name_value');

        $query->leftJoin('field_data_field_last_name', 'fln', 'n.nid = fln.entity_id AND fln.deleted = 0');
        $query->addField('fln', 'field_last_name_value', 'field_last_name_value');

        $query->leftJoin('field_data_field_sort_name', 'fsn', 'n.nid = fsn.entity_id AND fsn.deleted = 0');
        $query->addField('fsn', 'field_sort_name_value', 'field_sort_name_value');

        $query->leftJoin('field_data_field_sex', 'fs', 'n.nid = fs.entity_id AND fs.deleted = 0');
        $query->addField('fs', 'field_sex_value');

        $query->leftJoin('field_data_field_notes', 'fn', 'n.nid = fn.entity_id AND fn.deleted = 0');
        $query->addField('fn', 'field_notes_value', 'field_notes_value');

        return $query;
    }

  /**
   * {@inheritdoc}
   */
    public function fields()
    {
        // This maps the field from their name above to a destination field name that is specified in the process section. I generally keep them the same.
        $fields = [
          'nid' => 'nid',
          'created' => 'created',
          'changed' => 'changed',
          'title' => 'title',
          'field_event_date_value' => 'field_event_date_value',
          'event_type' => 'event_type',
          'event_location' => 'event_location',
          'field_first_name_value' => 'field_first_name_value',
          'field_last_name_value' => 'field_last_name_value',
          'field_sort_name_value' => 'field_sort_name_value',
          'field_sex_value' => 'field_sex_value',
          'field_notes_value' => 'field_notes_value',
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
        // print_r($row->getSourceProperty('field_event_date_value'));
        return parent::prepareRow($row);
    }
}
