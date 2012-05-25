<html>
	<body>
		<h1>404 Page not Found</h1>
		<p>The page you requested could not be loaded.</p>
		<p>You can try again, or you can <a href="/">return to the main page</a></p>
	</body>
</html>
<?php
/** 
 * Some browsers require x amount of bytes to show a custom 404 page.
 * 
 * IE and Chrome require at least 512B
 * 
 * We figured forcing additional whitespace would do the trick.
 * 
 * If you compress your web pages, this guy may have a solution for you:
 * @see http://www.clintharris.net/2009/ie-512-byte-error-pages-and-wordpress/
 * 
 * If you encounter a browser that needs a different amount, let us know. If you encounter a better 
 * way to handle this, let us know.
 */
print str_repeat(' ', 350);
?>