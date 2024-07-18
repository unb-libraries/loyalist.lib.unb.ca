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

        $query->leftJoin('field_data_field_accompanying_record', 'fac', 'n.nid = fac.entity_id AND fac.deleted = 0');
        $query->addField('fac', 'field_accompanying_record_value', 'field_accompanying_record_value');

        $query->leftJoin('field_data_field_background_information', 'fbi', 'n.nid = fbi.entity_id AND fbi.deleted = 0');
        $query->addField('fbi', 'field_background_information_value', 'field_background_information_value');

        $query->leftJoin('field_data_field_call_number', 'fcn', 'n.nid = fcn.entity_id AND fcn.deleted = 0');
        $query->addField('fcn', 'field_call_number_value', 'field_call_number_value');

        $query->leftJoin('field_data_field_contents', 'fcon', 'n.nid = fcon.entity_id AND fcon.deleted = 0');
        $query->addField('fcon', 'field_contents_value', 'field_contents_value');

        $query->leftJoin('field_data_field_document_id', 'fdid', 'n.nid = fdid.entity_id AND fdid.deleted = 0');
        $query->addField('fdid', 'field_document_id_value', 'field_document_id_value');

        $query->leftJoin('field_data_field_finding_aids', 'fa', 'n.nid = fa.entity_id AND fa.deleted = 0');
        $query->addField('fa', 'field_finding_aids_value', 'field_finding_aids_value');

        $query->leftJoin('field_data_field_gauge', 'fg', 'n.nid = fg.entity_id AND fg.deleted = 0');
        $query->addField('fg', 'field_gauge_value', 'field_gauge_value');

        $query->leftJoin('field_data_field_issuing_body', 'fib', 'n.nid = fib.entity_id AND fib.deleted = 0');
        $query->leftJoin('taxonomy_term_data', 'ttdfib', 'fib.field_issuing_body_tid = ttdfib.tid');
        $query->addField('ttdfib', 'name', 'issuing_body_name');

        $query->leftJoin('field_data_field_media_type', 'fmt', 'n.nid = fmt.entity_id AND fmt.deleted = 0');
        $query->leftJoin('taxonomy_term_data', 'ttdfmt', 'fmt.field_media_type_tid = ttdfmt.tid');
        $query->addField('ttdfmt', 'name', 'media_type_name');

        $query->leftJoin('field_data_field_notes', 'fn', 'n.nid = fn.entity_id AND fn.deleted = 0');
        $query->addField('fn', 'field_notes_value', 'field_notes_value');

        $query->leftJoin('field_data_field_number_of_sources', 'fns', 'n.nid = fns.entity_id AND fns.deleted = 0');
        $query->addField('fns', 'field_number_of_sources_value', 'field_number_of_sources_value');

        $query->leftJoin('field_data_field_originals', 'fo', 'n.nid = fo.entity_id AND fo.deleted = 0');
        $query->addField('fo', 'field_originals_value', 'field_originals_value');

        $query->leftJoin('field_data_field_other_numbers', 'fon', 'n.nid = fon.entity_id AND fon.deleted = 0');
        $query->addField('fon', 'field_other_numbers_value', 'field_other_numbers_value');

        $query->leftJoin('field_data_field_other_with', 'fow', 'n.nid = fow.entity_id AND fow.deleted = 0');
        $query->addField('fow', 'field_other_with_value', 'field_other_with_value');

        $query->leftJoin('field_data_field_record_info', 'fri', 'n.nid = fri.entity_id AND fri.deleted = 0');
        $query->addField('fri', 'field_record_info_value', 'field_record_info_value');

        $query->leftJoin('field_data_field_subject_heading', 'fsh', 'n.nid = fsh.entity_id AND fsh.deleted = 0');
        $query->leftJoin('taxonomy_term_data', 'ttdfsh', 'fsh.field_subject_heading_tid = ttdfsh.tid');
        $query->addField('ttdfsh', 'name', 'subject_heading_name');

        $query->leftJoin('field_data_field_this_is_part_of', 'ftpo', 'n.nid = ftpo.entity_id AND ftpo.deleted = 0');
        $query->addField('ftpo', 'field_this_is_part_of_value', 'field_this_is_part_of_value');

        $query->leftJoin('field_data_field_volume_info', 'fvi', 'n.nid = fvi.entity_id AND fvi.deleted = 0');
        $query->addField('fvi', 'field_volume_info_value', 'field_volume_info_value');

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
          'title' => 'title',
          'field_accompanying_record_value' => 'field_accompanying_record_value',
          'field_background_information_value' => 'field_background_information_value',
          'field_call_number_value' => 'field_call_number_value',
          'field_contents_value' => 'field_contents_value',
          'field_document_id_value' => 'field_document_id_value',
          'field_finding_aids_value' => 'field_finding_aids_value',
          'field_gauge_value' => 'field_gauge_value',
          'field_notes_value' => 'field_notes_value',
          'field_number_of_sources' => 'field_number_of_sources',
          'field_other_numbers_value' => 'field_other_numbers_value',
          'field_other_with_value' => 'field_other_with_value',
          'field_record_info' => 'field_record_info',
          'field_subject_heading' => 'field_subject_heading',
          'field_this_is_part_of_value' => 'field_this_is_part_of_value',
          'field_volume_info_value' => 'field_volume_info_value',
          'issuing_body_name' => 'issuing_body_name',
          'media_type_name' => 'media_type_name',
          'subject_heading_name' => 'subject_heading_name',
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
