		</div>
	</div>
<div class="footer">
	<div class="container">
		<ul class="footer-links">
			<li><a href="#"><?php _e('main.help'); ?></a></li>
			<li><a href="#"><?php _e('main.about'); ?></a></li>
			<li><a href="contact.php"><?php _e('main.contact'); ?></a></li>
		</ul>
		<p>&copy; <?php echo date('Y', time()) .' '. Config::get('app.name'); ?></p>
	</div>
</div>

<?php echo View::make('modals.load')->render() ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-62774364-1', 'auto');
  ga('send', 'pageview');

</script>

</body>
</html>