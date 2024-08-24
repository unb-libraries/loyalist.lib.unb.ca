FROM ghcr.io/unb-libraries/drupal:10.x-1.x-unblib
MAINTAINER UNB Libraries <libsupport@unb.ca>

# Install additional OS packages.
ENV ADDITIONAL_OS_PACKAGES="postfix php-ldap php-zip php81-pecl-redis"
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
LABEL ca.unb.lib.generator="drupal10" \
  com.microscaling.docker.dockerfile="/Dockerfile" \
  com.microscaling.license="MIT" \
  org.label-schema.build-date=$BUILD_DATE \
  org.label-schema.description="loyalist.lib.unb.ca examines recipes circulating before 1800 in print and manuscript in the area now defined as Canada's Maritime provinces." \
  org.label-schema.name="loyalist.lib.unb.ca" \
  org.label-schema.schema-version="1.0" \
  org.label-schema.url="https://loyalist.lib.unb.ca" \
  org.label-schema.vcs-ref=$VCS_REF \
  org.label-schema.vcs-url="https://github.com/unb-libraries/loyalist.lib.unb.ca" \
  org.label-schema.vendor="University of New Brunswick Libraries" \
  org.label-schema.version=$VERSION \
  org.opencontainers.image.source="https://github.com/unb-libraries/loyalist.lib.unb.ca"
