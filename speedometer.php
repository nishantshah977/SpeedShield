<?php
/*
Plugin Name: Speedometer 
Description: "Speedometer" is a powerful and easy-to-use WordPress plugin that optimizes your website's performance by compressing images and videos and caching resources to improve page load times.
Version: 1.0
Author: Nishant Shah
*/


$content = '

# Increase website speed with Speedometer

# 1. Turn on mod_expires to set expiration headers
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType image/gif "access plus 1 year"
  ExpiresByType text/css "access plus 1 month"
  ExpiresByType text/javascript "access plus 1 month"
</IfModule>

# 2. Enable mod_deflate to compress content
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/plain text/css application/json
  AddOutputFilterByType DEFLATE application/javascript
  AddOutputFilterByType DEFLATE text/xml application/xml text/css
  AddOutputFilterByType DEFLATE application/xml+rss text/javascript
</IfModule>

# 3. Set the cache control header for static resources
<filesMatch "\.(css|jpg|jpeg|png|gif|js|svg)$">
  Header set Cache-Control "public, max-age=31536000, immutable"
</filesMatch>

# 4. Turn on mod_headers to set cross-domain cache headers
<IfModule mod_headers.c>
  Header set X-Content-Type-Options nosniff
  Header set X-XSS-Protection "1; mode=block"
  Header set X-Frame-Options "SAMEORIGIN"
</IfModule>



';

function edit_htaccess_file() {
    $htaccess_file = ABSPATH . '.htaccess';
    file_put_contents( $htaccess_file, $content, FILE_APPEND | LOCK_EX );
}

function deactivate_edit_htaccess_file() {
    $htaccess_file = ABSPATH . '.htaccess';
    $existing_content = file_get_contents( $htaccess_file );
    $new_content = str_replace($content, "", $existing_content);
    file_put_contents( $htaccess_file, $new_content, LOCK_EX );
}

register_activation_hook( __FILE__, 'edit_htaccess_file' );
register_deactivation_hook( __FILE__, 'deactivate_edit_htaccess_file' );

?>
