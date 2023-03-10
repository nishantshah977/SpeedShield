<?php
/*

 * Plugin Name:       Speed Shield

 * Plugin URI:        https://github.com/nishantshah977/SpeedShield

 * Description:       Add Security amd Speed to you website. Get free protection amd Optimization 

 * Version:           1.1

 * Requires at least: 5.2

 * Requires PHP:      5

 * Author:            Nishant Shah

 * Author URI:        https://studentforum.xyz

 * License:           GPL v2l3



 */


$content = '
# Compress text, html, javascript, css, xml:


AddOutputFilterByType DEFLATE text/plain
AddOutputFilterByType DEFLATE text/html
AddOutputFilterByType DEFLATE text/xml
AddOutputFilterByType DEFLATE text/css
AddOutputFilterByType DEFLATE application/xml
AddOutputFilterByType DEFLATE application/xhtml+xml
AddOutputFilterByType DEFLATE application/rss+xml
AddOutputFilterByType DEFLATE application/javascript
AddOutputFilterByType DEFLATE application/x-javascript

# Remove server signature

ServerSignature Off

# Remove ETags

Header unset ETag
FileETag None

# Enable browser caching

<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/jpg "access plus 1 year"
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/gif "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType text/css "access plus 1 month"
  ExpiresByType application/javascript "access plus 1 month"
</IfModule>


# Minifying CSS and JavaScript files
<IfModule mod_deflate.c>
  <IfModule mod_setenvif.c>
    BrowserMatch ^Mozilla/4 gzip-only-text/html
    BrowserMatch ^Mozilla/4\.[0678] no-gzip
    BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
  </IfModule>
  <IfModule mod_headers.c>
    Header append Vary User-Agent env=!dont-vary
  </IfModule>
  <IfModule mod_filter.c>
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE text/html text/plain text/xml
  </IfModule>
</IfModule>


# Minify HTML


<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/plain text/xml application/xml
</IfModule>



# Prevent cross-site scripting (XSS) attacks

Header set X-XSS-Protection "1; mode=block"

# Prevent cross-site request forgery (CSRF) attacks

Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "SAMEORIGIN"

# Protect against HTTP request smuggling attacks

Header set Transfer-Encoding chunked

# Secure cookie handling

Header edit Set-Cookie ^(.*)$ $1;HttpOnly;Secure

# Enable mod_security for added security

<IfModule mod_security2.c>
  SecRuleEngine On
  SecRequestBodyAccess On
  SecResponseBodyAccess On
  SecDefaultAction "deny,log,status:500"
  SecDebugLog /var/log/httpd/modsec_debug.log
  SecDebugLogLevel 3
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
