<?php

namespace Drupal\loyalist_migrate\Plugin\migrate\source;

use Drupal\migrate\Annotation\MigrateSource;
use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Migrates Loyalist Users.
 *
 * @MigrateSource(
 *   id = "loyalist_user",
 *   source_module = "loyalist_migrate",
 * )
 */
class LoyalistUser extends SqlBase
{
  /**
   * {@inheritdoc}
   */
  public function query()
  {
    $query = $this->select('users', 'u');
    $query->condition('u.uid', 0, '>');
    $query->addField('u', 'uid', 'uid');
    $query->addField('u', 'name', 'name');
    $query->addField('u', 'mail', 'mail');
    $query->addField('u', 'created', 'created');
    $query->addField('u', 'access', 'access');
    $query->addField('u', 'status', 'status');
    $query->addField('u', 'timezone', 'timezone');
    $query->addField('u', 'changed', 'changed');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields()
  {
    // This maps the field from their name above to a destination field name that is specified in the process section. I generally keep them the same.
    $fields = [
      'uid' => 'uid',
      'name' => 'name',
      'mail' => 'mail',
      'created' => 'created',
      'access' => 'access',
      'status' => 'status',
      'timezone' => 'timezone',
      'changed' => 'changed',
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds()
  {
    return [
      'uid' => [
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
