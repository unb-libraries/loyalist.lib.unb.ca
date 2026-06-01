# Loyalist Core

TODO: write some documentation.

## Requirements

This module requires no modules outside of Drupal core.


## Front Page Carousel

The front page carousel depends on the JS library, SwiperJS (see module
`loyalist_carousel` library definition). It relies on `Media | Image` slide content which
references a <kbd>carousel</kbd> `Tags` taxonomy term.

The carousel itself is implemented by the view, `Loyalist Carousel`, `Owl Block` block
display.