# Loyalist Core

TODO: write some documentation.

## Requirements

This module requires no modules outside of Drupal core.


## Front Page Carousel

The front page carousel depends on the JS library, Owl Carousel v2.3.4 (see subtheme
`owlcarousel2` library definition). It relies on `Media | Image` slide content which
references a <kbd>carousel</kbd> `Tags` taxonomy term.

The carousel itself is implemented by the view, `Loyalist Carousel`, `Owl Block` block
display. The view depends on the `Views Rows Wrapper` contrib module to provide a
`Rows Wrapper` formatter to minimize the HTML structure for use by the Owl Carousel
library.
