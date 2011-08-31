<div id="footer">
    <div class="max">
        <div class="container_right">
            <a id="logo"  href="<?php bloginfo('url'); ?>/"><img src="<?php  echo(get_bloginfo('stylesheet_directory') . "/images/Logo.png"); ?>"></a>
            <?php include 'submenu.php'; ?>
        </div>
    </div>
</div>

	</div>

<?php wp_footer(); ?>	
<?php @include get_stylesheet_directory() . '/analytics.php'; ?>	

<style type="text/css" media="screen">
	/** css fixes for external plugins loaded in the footer **/
	/* facebook login */
	.fbc_loginstate_top  {
	position:fixed;
	z-index:999;
	}
</style>
</body>
</html>
