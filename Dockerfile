FROM ghcr.io/unb-libraries/drupal:11.x-1.x-unblib

# Install additional OS packages.
ENV ADDITIONAL_OS_PACKAGES="postfix php-ldap php-zip php84-pecl-redis"
ENV DRUPAL_SITE_ID="loyalist"
ENV DRUPAL_SITE_URI="loyalist.lib.unb.ca"
ENV DRUPAL_SITE_UUID="9ae92cc6-c0b6-41d0-b619-778b7e928952"

# Build application.
COPY ./build/ /build/
RUN ${RSYNC_MOVE} /build/scripts/container/ /scripts/ && \
  /scripts/addOsPackages.sh && \
  /scripts/initOpenLdap.sh && \
  /scripts/setupStandardConf.sh && \
  /scripts/build.sh

# Deploy custom assets, configuration.
COPY ./configuration ${DRUPAL_CONFIGURATION_DIR}
COPY ./custom/themes ${DRUPAL_ROOT}/themes/custom
COPY ./custom/modules ${DRUPAL_ROOT}/modules/custom

# Container metadata.
ARG BUILD_DATE
ARG VCS_REF
ARG VERSION
LABEL ca.unb.lib.generator="drupal11" \
  org.opencontainers.image.title="loyalist.lib.unb.ca" \
  org.opencontainers.image.description="loyalist.lib.unb.ca examines recipes circulating before 1800 in print and manuscript in the area now defined as Canada's Maritime provinces." \
  org.opencontainers.image.vendor="University of New Brunswick Libraries" \
  org.opencontainers.image.authors="UNB Libraries <libsupport@unb.ca>" \
  org.opencontainers.image.url="https://loyalist.lib.unb.ca" \
  org.opencontainers.image.source="https://github.com/unb-libraries/loyalist.lib.unb.ca" \
  org.opencontainers.image.version="$VERSION" \
  org.opencontainers.image.revision="$VCS_REF" \
  org.opencontainers.image.created="$BUILD_DATE"
