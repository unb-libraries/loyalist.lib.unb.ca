<?php
/**
 * @file
 *
 * This was written to wrap paragraphs in <p> tags for the body, finding_aids, and background_information fields.
 * Previously, the data was stored in the database without any tags, and was displayed in a <pre> tag. This led to a
 * lot of formatting issues, and the data was not being displayed correctly.
 *
 * If you're reading this in the future, you shouldn't run this.
 */
$updates_to_make = [
  [
    'table_name' => 'node_revision__body',
    'col_name' => 'body_value',
    'field_name' => 'body',
    'updated' => 0,
  ],
  [
    'table_name' => 'field_data_field_finding_aids',
    'col_name' => 'field_finding_aids_value',
    'field_name' => 'field_finding_aids',
    'updated' => 0,
  ],
  [
    'table_name' => 'field_data_field_background_information',
    'col_name' => 'field_background_information_value',
    'field_name' => 'field_background_information',
    'updated' => 0,
  ],
];

foreach ($updates_to_make as $update_idx => $update)
{
  $updated_nodes = [];

  // Re-init the DB connection for each update.
  $db = \Drupal::service('database');
  $result = $db->query(
    "SELECT * FROM {$update['table_name']} WHERE {$update['col_name']} NOT LIKE :tagcode OR {$update['col_name']} NOT LIKE :tagcapcode",
    [
      ':tagcode' => '%' . $db->escapeLike('</p>') . '%',
      ':tagcapcode' => '%' . $db->escapeLike('</P>') . '%',
    ]
  );

  print "Processing {$update['table_name']}...\n";
  foreach ($result as $record) {
    $nid = $record->entity_id;
    if (in_array($nid, $updated_nodes)) {
      continue;
    }
    $updated_nodes[] = $nid;
    $body_value = $record->{$update['col_name']};

    $body_value = preg_replace('~\R~u', "\r\n", $body_value);
    $body_value = preg_replace('|\n{2,}|', "\n\n", $body_value);

    $paragraphs = preg_split('/\n+/', $body_value);
    $wrapped_body_value = '';

    foreach ($paragraphs as $paragraph) {
      // Case : paragraph ends by defining table or list.
      $ends_with_tag = preg_match('/<\w+.*?>$/', $paragraph);
      if ($ends_with_tag) {
        $moved_tag = '';
        preg_match('/<\w+.*?>$/', $paragraph, $matches);
        $tag = $matches[0];

        // Ignore closing tags.
        if (strpos($tag, '</') !== FALSE) {
          continue;
        }

        // If the paragraph is ONLY a tag, do nothing.
        if (strlen($paragraph) == strlen($tag)) {
          continue;
        }

        // Remove the tag from the paragraph
        $paragraph = preg_replace('/<\w+.*?>$/', '', $paragraph);
        $moved_tag = $tag;
      }

      $paragraph = trim($paragraph);
      if(paragraphShouldBeWrapped($paragraph)) {
        $wrapped_body_value .= '<p>' . $paragraph . "</p>\n";
      }
      else {
        $wrapped_body_value .= "$paragraph\n";
      }

      // If we moved a tag, now add it back.
      if (!empty($moved_tag)) {
        $wrapped_body_value .= $moved_tag . "\n";
      }
    }

    $node = \Drupal\node\Entity\Node::load($nid);
    $node->{$update['field_name']}->value = $wrapped_body_value;
    $node->save();

    $updates_to_make[$update_idx]['updated']++;
    print "https://local-loyalist.lib.unb.ca/node/$nid\n";
  }
}
print_r($updates_to_make);

/**
 * Determines if a plain-formatted paragraph should be wrapped in <p> tags.
 *
 * There are some cases where we don't want to wrap a paragraph: if it's a tag,
 * or if it's a list or table.
 *
 * @param string $paragraph
 *   The paragraph to check.
 *
 * @return bool
 *   TRUE if the paragraph should be wrapped in <p> tags, FALSE otherwise.
 */
function paragraphShouldBeWrapped($paragraph) {
  if (strlen($paragraph) > 0) {
    $paragraph = strtolower($paragraph);

    if (preg_match('/^<\/?\w+.*?>/', $paragraph)) {
      preg_match('/^<\/?\w+.*?>/', $paragraph, $matches);
      $tag = $matches[0];

      // Tags might have attributes.
      $exploded_tag = explode(' ', $tag);
      if (count($exploded_tag) > 1) {
        $check_tag = $exploded_tag[0];
      }
      else {
        $check_tag = rtrim($tag, '>');
      }

      // If the second character is a slash, treat it like an opening tag.
      if (substr($check_tag, 1, 1) == '/') {
        $check_tag = '<' . substr($check_tag, 2);
      }

      if (in_array(
        $check_tag,
        [
          '<table',
          '<tr',
          '<td',
          '<th',
          '<ul',
          '<ol',
          '<li',
        ]
      )) {
        return false;
      }
      return true;
    }
    return true;
  }
  return false;
}
