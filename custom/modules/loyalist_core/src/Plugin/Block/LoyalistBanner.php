<?php

namespace Drupal\loyalist_core\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\core\StringTranslation\TranslatableMarkup;

/**
 * Provides the Loyalist Atlantic Connections blog banner block.
 */

#[Block(
  id: "loyalist_blog_banner",
  admin_label: new TranslatableMarkup("Loyalist Blog Banner block"),
  category: new TranslatableMarkup("Loyalist Blog")
)]

class LoyalistBanner extends BlockBase {

  /**
   * {@inheritdoc }
   */
  public function build() {
    return [
      '#type' => 'html_tag',
      '#tag' => 'figure',
      '#value' => '<img src="/themes/custom/the_loyalist_collection/images/loyalist-blog-banner.jpg"
        alt="" class="img-fluid">',
    ];
  }
}
