<?php

namespace Drupal\loyalist_migrate\Plugin\migrate\source;

use Drupal\migrate\Annotation\MigrateSource;
use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Migrates Loyalist Blog Posts.
 *
 * @MigrateSource(
 *   id = "loyalist_blog",
 *   source_module = "loyalist_migrate",
 * )
 */
class BlogPost extends SqlBase
{
  /**
   * {@inheritdoc}
   */
  public function query()
  {
    $query = $this->select('node', 'n');
    $query->condition('n.type', 'blog');
    $query->addField('n', 'nid', 'nid');
    $query->addField('n', 'vid', 'vid');
    $query->addField('n', 'title', 'title');
    $query->addField('n', 'status', 'status');
    $query->addField('n', 'created', 'created');
    $query->addField('n', 'changed', 'changed');
    $query->addField('n', 'promote', 'promote');
    $query->addField('n', 'sticky', 'sticky');
    $query->leftJoin('field_data_body', 'fb', 'n.nid = fb.entity_id AND fb.deleted = 0');
    $query->addField('fb', 'body_value', 'body_value');
    $query->addField('fb', 'body_summary', 'body_summary');

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
      'vid' => 'vid',
      'title' => 'title',
      'status' => 'status',
      'created' => 'created',
      'changed' => 'changed',
      'promote' => 'promote',
      'sticky' => 'sticky',
      'body_value' => 'body_value',
      'body_summary' => 'body_summary',
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
