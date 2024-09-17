<?php

namespace Drupal\loyalist_migrate\Event;

use Drupal\Core\File\FileSystemInterface;
use Drupal\migrate\Event\MigrateEvents as CoreMigrateEvents;
use Drupal\migrate\Event\MigratePostRowSaveEvent;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Defines the loyalist item migrate event subscriber.
 */
class LoyalistItemEvent implements EventSubscriberInterface {

  const MIGRATION_DATABASE = 'migrate';
  const MIGRATION_ID = '1_loyalist_migrate_loyalist_items';
  const LOCAL_FILE_SOURCE_PATH = '/files';

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[CoreMigrateEvents::POST_ROW_SAVE][] = ['attachFindingAidRecords', 0];
    return $events;
  }

  /**
   * React to a new row being saved.
   *
   * @param \Drupal\migrate\Event\MigratePostRowSaveEvent $event
   *   The post-row-save event.
   */
  public function attachFindingAidRecords(MigratePostRowSaveEvent $event) {
    $row = $event->getRow();
    $migration = $event->getMigration();
    $migration_id = $migration->id();

    // Only act on rows for this migration.
    if ($migration_id == self::MIGRATION_ID) {
      $id = $event->getDestinationIdValues();
      $nid = reset($id);
      $node = Node::load($nid);

      foreach ($this->querySourceDbForAttachedFiles($nid) as $file) {
        $file_path = $this->getFilePathOnDisk($file);
        if (!file_exists($file_path)) {
          echo "File not found: $file_path\n";
          continue;
        }
        $file_components = pathinfo($file_path);
        $uri_directory = 'public:/'. $file_components['dirname'];
        $full_uri = $uri_directory . '/' . $file_components['basename'];

        $file_system = \Drupal::service('file_system');
        $file_system->prepareDirectory($uri_directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
        $file_system->copy($file_path, $full_uri, FileSystemInterface::EXISTS_REPLACE);

        $file = File::create([
          'filename' => $file->filename,
          'uri' => $full_uri,
          'status' => $file->status,
          'uid' => $file->uid,
        ]);
        $file->save();
        
        $file_usage = \Drupal::service('file.usage');
        $file_usage->add($file, 'loyalist_migrate', 'node', $nid);

        $node->field_finding_aid_record[] = [
          'target_id' => $file->id(),
          'display' => $file->display,
          'description' => $file->description,
        ];
      }
      $node->save();
    }
  }

  /**
   * Get the file path on disk.
   *
   * @param object $file
   *   The file object.
   *
   * @return string
   *   The file path on disk.
   */
  protected function getFilePathOnDisk($file) {
    return self::LOCAL_FILE_SOURCE_PATH . str_replace('public:/', '', $file->uri);
  }

  /**
   * Query the source database for files attached to the finding_aid_record field.
   *
   * @param int $nid
   *   The node ID.
   *
   */
  protected function querySourceDbForAttachedFiles($nid) {
    \Drupal\Core\Database\Database::setActiveConnection(self::MIGRATION_DATABASE);
    $database = \Drupal\Core\Database\Database::getConnection();
    $query = <<<EOT
    SELECT fm.fid as fid, fm.uri as uri, fm.filename as filename, fm.uri as uri, fm.filemime as filemime, fm.filesize as filesize, fm.status as status, fm.timestamp as timestamp, fm.type as type, fm.uid as uid, far.field_finding_aid_record_display as display, far.field_finding_aid_record_description as description
    FROM field_data_field_finding_aid_record AS far
    LEFT JOIN file_managed AS fm
    ON far.field_finding_aid_record_fid = fm.fid
    WHERE far.entity_id = $nid;
EOT;
    $query = $database->query($query);
    return $query->fetchAll();
  }

}
