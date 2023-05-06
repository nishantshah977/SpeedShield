<?php
/*

 * Plugin Name:       Speed Shield

 * Plugin URI:        https://github.com/nishantshah977/SpeedShield

 * Description:       Add Security amd Speed to you website. Get free protection amd Optimization 

 * Version:           1.3

 * Requires at least: 5.2

 * Requires PHP:      5

 * Author:            Nishant Shah

 * Author URI:      https://www.facebook.com/nepalinishantshah

 * License:           GPL v2.3



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

# Serve images in next-gen formats
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTP_ACCEPT} image/webp
    RewriteCond %{REQUEST_FILENAME} \.(jpe?g|png)$
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule (.+)\.(jpe?g|png)$ $1.webp [T=image/webp,E=accept:1]
    Header append Vary Accept env=REDIRECT_accept
</IfModule>

# Reduce unused CSS
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} \.css$
    RewriteCond %{QUERY_STRING} !^no-min$
    RewriteRule (.+)\.css$ $1.min.css [L,QSA]
</IfModule>

# Eliminate render-blocking resources
<IfModule mod_headers.c>
    <FilesMatch "\.(js|css)$">
        Header set Cache-Control "max-age=31536000, public"
    </FilesMatch>
    <FilesMatch "\.(html|php)$">
        Header set Cache-Control "max-age=300, private, must-revalidate"
    </FilesMatch>
</IfModule>


# Protecting wp-config.php
<Files wp-config.php>
Order Allow,Deny
Deny from all
</files>

#Protect .htaccess file
<Files ~ "^.*\.([Hh][Tt][Aa])">
order allow, deny
deny from all
satisfy all
</Files>

# Preventing Access to .htaccess File
<Files .htaccess>
order allow,deny
deny from all
</Files>

# Speedshield Finsihed

';

function active_speedshield() {
    $htaccess_file = ABSPATH . '.htaccess';
    file_put_contents($htaccess_file, $content, FILE_APPEND | LOCK_EX);

    // Define a function to remove the generator tag
    function remove_generator_tag() {
        return '';
    }

    // Hook the function to the 'the_generator' filter
    add_filter('the_generator', 'remove_generator_tag');

    // Access the theme's functions.php file and remove the generator tag
    add_action('after_setup_theme', function() {
        $functions_file = get_stylesheet_directory() . '/functions.php';
        if (file_exists($functions_file)) {
            require_once $functions_file;

            // Change Error Message On Login Page
            function no_wordpress_errors() {
                return 'SOMETHING WENT WRONG!';
            }
            add_filter('login_errors', 'no_wordpress_errors');
        }
    });
}


function deactivate_speedshield() {
    $htaccess_file = ABSPATH . '.htaccess';
    $existing_content = file_get_contents( $htaccess_file );
    $new_content = str_replace($content, "", $existing_content);
    file_put_contents( $htaccess_file, $new_content, LOCK_EX );
}

register_activation_hook( __FILE__, 'active_speedshield' );
register_deactivation_hook( __FILE__, 'deactivate_speedshield' );

?>
