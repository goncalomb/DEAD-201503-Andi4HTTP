Andi4HTTP
=========

A neat directory index for HTTP.

Now with basic theme support. Icons and Markdown headers/footers planned.

Installing
----------

Andi4HTTP is for Apache only.

This is not prepared to work on aliased directories (mod_alias) or with rewrites (mod_rewrite).
Please only install on the root of your domain/sub-domain or on sub-directories without any fancy alias.
In the future I'll probably try to detect these special cases and ask to set a config with the base path.

1. Copy the contents of `www` into the directory that you want to enable listing.
2. Navigate to that directory on the browser.
3. Wait 0.001 seconds. It will automatically create all the required `.htaccess` files.
4. Done.

Customize to your liking using `config.php`, `header.php` and `footer.php`.
Or create a theme an put it on the `themes` directory (check the 'example' theme).
