<?php

namespace Drupal\loyalist_migrate\Plugin\migrate\source;

use Drupal\migrate\Annotation\MigrateSource;
use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Migrates Loyalist Users.
 *
 * @MigrateSource(
 *   id = "loyalist_comment",
 *   source_module = "loyalist_migrate",
 * )
 */
class LoyalistComment extends SqlBase
{
  /**
   * {@inheritdoc}
   */
  public function query()
  {
    $query = $this->select('comment', 'c');
    $query->addField('c', 'cid', 'cid');
    $query->addField('c', 'pid', 'pid');
    $query->addField('c', 'nid', 'nid');
    $query->addField('c', 'uid', 'uid');
    $query->addField('c', 'subject', 'subject');
    $query->addField('c', 'hostname', 'hostname');
    $query->addField('c', 'created', 'created');
    $query->addField('c', 'changed', 'changed');
    $query->addField('c', 'status', 'status');
    $query->addField('c', 'thread', 'thread');
    $query->addField('c', 'name', 'name');
    $query->addField('c', 'mail', 'mail');
    $query->addField('c', 'homepage', 'homepage');
    $query->addField('c', 'language', 'language');

    $query->leftJoin('field_data_comment_body', 'cb', 'cb.entity_id=c.cid');
    $query->addField('cb', 'comment_body_value', 'comment_body_value');

    $query->leftJoin('node', 'n', 'n.nid = c.nid');
    $query->addField('n', 'uid', 'nuid');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields()
  {
    // This maps the field from their name above to a destination field name that is specified in the process section. I generally keep them the same.
    $fields = [
      'cid' => 'cid',
      'pid' => 'pid',
      'nid' => 'nid',
      'uid' => 'uid',
      'nuid' => 'nuid',
      'subject' => 'subject',
      'hostname' => 'hostname',
      'created' => 'created',
      'changed' => 'changed',
      'status' => 'status',
      'thread' => 'thread',
      'name' => 'name',
      'mail' => 'mail',
      'homepage' => 'homepage',
      'language' => 'language',
      'comment_body_value' => 'comment_body_value',
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds()
  {
    return [
      'cid' => [
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
