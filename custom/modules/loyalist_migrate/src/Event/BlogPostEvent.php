<?php

namespace Drupal\loyalist_migrate\Event;

use Drupal\migrate\Event\MigrateEvents as CoreMigrateEvents;
use Drupal\migrate\Event\MigratePostRowSaveEvent;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Defines the blog post migrate event subscriber.
 */
class BlogPostEvent implements EventSubscriberInterface {

  const MIGRATION_DATABASE = 'migrate';
  const MIGRATION_ID = '2_loyalist_migrate_loyalist_blog';
  const TAXONOMY_CATEGORY_VID = 'category';

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[CoreMigrateEvents::POST_ROW_SAVE][] = ['attachTaxonomyTerms', 0];
    return $events;
  }

  /**
   * React to a new row being saved.
   *
   * @param \Drupal\migrate\Event\MigratePostRowSaveEvent $event
   *   The post-row-save event.
   */
  public function attachTaxonomyTerms(MigratePostRowSaveEvent $event) {
    $row = $event->getRow();
    $migration = $event->getMigration();
    $migration_id = $migration->id();

    // Only act on rows for this migration.
    if ($migration_id == self::MIGRATION_ID) {
      $id = $event->getDestinationIdValues();
      $id = reset($id);
      $node = Node::load($id);

      // Attach taxonomy terms.
      foreach ($this->querySourceDbForTaxonomyTerms($id) as $term_name) {
        $this->cleanUpTermName($term_name);
        $term = $this->getTaxonomyTermByNameCreateIfNotExists($term_name, self::TAXONOMY_CATEGORY_VID);
        $node->field_post_categories[] = [
          'target_id' => $term->id(),
        ];
      }

      // Attach Comments.

      $node->save();
    }
  }

  /**
   * Query the source database for category taxonomy terms for a node.
   *
   * @param int $nid
   *   The node ID.
   *
   * @return string[]
   *   An array of category names.
   */
  protected function querySourceDbForTaxonomyTerms($nid) {
    \Drupal\Core\Database\Database::setActiveConnection(self::MIGRATION_DATABASE);
    $database = \Drupal\Core\Database\Database::getConnection();
    $query = <<<EOT
    SELECT ttd.name
    FROM field_data_field_post_categories AS fdc
    LEFT JOIN taxonomy_term_data AS ttd
    ON fdc.field_post_categories_tid = ttd.tid
    WHERE fdc.entity_id = $nid;
EOT;
    $query = $database->query($query);
    return $query->fetchAllKeyed(0, 0);
  }

  /**
   * Query the source database for category taxonomy terms for a node.
   *
   * @param int $nid
   *   The node ID.
   *
   * @return string[]
   *   An array of category names.
   */
  protected function querySourceDbForComments($nid) {
    \Drupal\Core\Database\Database::setActiveConnection(self::MIGRATION_DATABASE);
    $database = \Drupal\Core\Database\Database::getConnection();
    $query = <<<EOT
    SELECT ttd.name
    FROM field_data_field_post_categories AS fdc
    LEFT JOIN taxonomy_term_data AS ttd
    ON fdc.field_post_categories_tid = ttd.tid
    WHERE fdc.entity_id = $nid;
EOT;
    $query = $database->query($query);
    return $query->fetchAllKeyed(0, 0);
  }

  /**
   * Get a taxonomy term by name, creating it if it doesn't exist.
   *
   * @param string $term_name
   *   The term name.
   * @param string $vid
   *   The vocabulary ID.
   *
   * @return \Drupal\taxonomy\Entity\Term
   *   The taxonomy term.
   */
  protected function getTaxonomyTermByNameCreateIfNotExists($term_name, $vid) {
    $term = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties(
        [
          'name' => $term_name,
          'vid' => $vid,
        ]
      );
    if (!$term) {
      $term = Term::create([
        'name' => $term_name,
        'vid' => $vid,
      ]);
      $term->save();
    }
    else {
      $term = reset($term);
    }
    return $term;
  }

  /**
   * Clean up a term name.
   *
   * @param string $term_name
   *   The term name.
   */
  protected function cleanUpTermName(&$term_name) {
    $term_name = trim($term_name);
    $term_name = strtolower($term_name);
    $term_name = ucwords($term_name);
  }

}
