<?php

namespace Drupal\loyalist_migrate\Plugin\migrate\source;

use Drupal\migrate\Annotation\MigrateSource;
use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Migrates Loyalist Users.
 *
 * @MigrateSource(
 *   id = "loyalist_media_file",
 *   source_module = "loyalist_migrate",
 * )
 */
class LoyalistMediaFile extends SqlBase
{
  /**
   * {@inheritdoc}
   */
  public function query()
  {
    // Previously migrated files from FAR field.
    $prev_migrate_query = $this->select('field_data_field_finding_aid_record', 'far');
    $prev_migrate_query->leftJoin('file_managed', 'fm', 'far.field_finding_aid_record_fid = fm.fid');
    $prev_migrate_query->addField('fm', 'fid', 'fid');

    // Get rest of the files.
    $query = $this->select('file_managed', 'fm');
    $query->addField('fm', 'fid', 'fid');
    $query->addField('fm', 'uid', 'uid');
    $query->addField('fm', 'uri', 'uri');
    $query->addField('fm', 'filename', 'filename');
    $query->addField('fm', 'filemime', 'filemime');
    $query->addField('fm', 'filesize', 'filesize');
    $query->addField('fm', 'status', 'status');
    $query->addField('fm', 'timestamp', 'timestamp');
    $query->addField('fm', 'type', 'type');

    $query->condition('fm.fid', $prev_migrate_query, 'NOT IN');
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields()
  {
    // This maps the field from their name above to a destination field name that is specified in the process section. I generally keep them the same.
    $fields = [
      'fid' => 'fid',
      'uid' => 'uid',
      'uri' => 'uri',
      'filename' => 'filename',
      'filemime' => 'filemime',
      'filesize' => 'filesize',
      'status' => 'status',
      'timestamp' => 'timestamp',
      'type' => 'type',
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds()
  {
    return [
      'fid' => [
        'type' => 'integer',
        'alias' => 'u',
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
