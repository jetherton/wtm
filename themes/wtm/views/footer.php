			</div>
			<div style="clear:both;"></div>
		</div>
		<!-- / main body -->

	</div>
	<!-- / wrapper -->
<div style="clear:both;"></div>
	<!-- footer -->
	<div id="footer" class="clearingfix">

		<div id="underfooter"></div>

		<!-- footer content -->
		<div class="wrapper floatholder">


			<!-- footer menu -->
			<div class="footermenu">
				<ul class="clearingfix">
					<li>
						<a class="item1" href="<?php echo url::site(); ?>">
							<?php echo Kohana::lang('ui_main.home'); ?>
						</a>
					</li>

					<?php if (Kohana::config('settings.allow_reports')): ?>
					<li>
						<a href="<?php echo url::site()."reports/submit"; ?>">
							<?php echo Kohana::lang('ui_main.submit'); ?>
						</a>
					</li>
					<?php endif; ?>

					<?php if (Kohana::config('settings.allow_alerts')): ?>
					<li>
						<a href="<?php echo url::site()."alerts"; ?>">
							<?php echo Kohana::lang('ui_main.alerts'); ?>
						</a>
					</li>
					<?php endif; ?>
					
					<li>
						<a href="<?php echo url::base()."page/index/8"; ?>">
							Impressum
						</a>
					</li>

					
					<li>
						<a href="<?php echo url::base()."page/index/7"; ?>">
							<?php echo Kohana::lang('ui_main.contact'); ?>
						</a>
					</li>
					

					<?php
					// Action::nav_main_bottom - Add items to the bottom links
					Event::run('ushahidi_action.nav_main_bottom');
					?>

				</ul>
				<?php if ($site_copyright_statement != ''): ?>
	      		<p><?php echo $site_copyright_statement; ?></p>
		      	<?php endif; ?>
			</div>
			<!-- / footer menu -->


		</div>
		<!-- / footer content -->

	</div>
	<!-- / footer -->

	<?php
	echo $footer_block;
	// Action::main_footer - Add items before the </body> tag
	Event::run('ushahidi_action.main_footer');
	?>
</body>
</html>
