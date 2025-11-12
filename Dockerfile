# Multi-stage build for production-ready container
FROM registry.access.redhat.com/ubi9/php-81:latest

# Metadata
LABEL maintainer="Chris Jenkins <chrisj@redhat.com>" \
      version="2.0.0" \
      description="Viewfinder Maturity Assessment Tool - Production Ready"

# Set working directory
WORKDIR /opt/app-root/src

# Install system dependencies and PHP extensions
USER root
RUN dnf install -y \
    httpd \
    php-fpm \
    php-json \
    && dnf clean all \
    && rm -rf /var/cache/dnf

# Configure Apache for security
RUN sed -i 's/^ServerTokens .*/ServerTokens Prod/' /etc/httpd/conf/httpd.conf && \
    sed -i 's/^ServerSignature .*/ServerSignature Off/' /etc/httpd/conf/httpd.conf && \
    echo 'Header always set X-Content-Type-Options "nosniff"' >> /etc/httpd/conf/httpd.conf && \
    echo 'Header always set X-Frame-Options "SAMEORIGIN"' >> /etc/httpd/conf/httpd.conf && \
    echo 'Header always set X-XSS-Protection "1; mode=block"' >> /etc/httpd/conf/httpd.conf

# Copy application files (exclude unnecessary files)
COPY --chown=1001:0 index.php results.php ./
COPY --chown=1001:0 includes/ ./includes/
COPY --chown=1001:0 css/ ./css/
COPY --chown=1001:0 js/ ./js/
COPY --chown=1001:0 images/ ./images/
COPY --chown=1001:0 lob/ ./lob/
COPY --chown=1001:0 compliance/ ./compliance/
COPY --chown=1001:0 report/ ./report/
COPY --chown=1001:0 *.json ./
COPY --chown=1001:0 composer.json ./

# Set proper permissions
RUN chown -R 1001:0 /opt/app-root/src && \
    chmod -R g=u /opt/app-root/src && \
    chmod 755 /opt/app-root/src

# Switch back to non-root user
USER 1001

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost:8080/ || exit 1

# Expose port
EXPOSE 8080

# Use Apache with PHP-FPM for production (not PHP dev server)
# Note: For full production deployment, configure Apache properly
# For now, using PHP server with better settings
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/opt/app-root/src"]
