
<div id="front-col-small"  style="width:310px; float:right; padding:0px;position: relative;top: -51px;">
			<div id="front_all_about" class="wtm_head_up">
			    <div id="front_about">
				<h1>About</h1>
				<p> 
				    Watch The Med is an online mapping platform to monitor the deaths and violations of migrants’ rights at the maritime borders of the EU
				</p>
			    </div>
			    <div id="front_flyer">			
				<p> 
				    Read the Flyer: <a href="<?php echo url::base(); ?>pdf/WTM-flyer-eng.pdf">english</a>, <a href="/">french</a>, <a href="/">arabic</a>
				</p>
			    </div>

			    <div id="how_to_report_box">
				<h2><?php echo Kohana::lang("ui_main.how_to_report");?></h2>
				<!-- Phone -->
				<?php if (!empty($phone_array)) { ?>
				<p>
					<?php echo Kohana::lang('ui_main.report_option_1'); ?>
					<?php foreach ($phone_array as $phone) { ?>
						<?php echo $phone; ?><br/>
					<?php } ?>
				</p>
				<?php } ?>

				<!-- External Apps -->
				<?php if (count($external_apps) > 0) { ?>
				<p>
					<?php echo Kohana::lang('ui_main.report_option_external_apps'); ?>:<br/>
					<?php 
					    $i = 0;
					    foreach ($external_apps as $app) { 
						$i++;
						if($i>1){echo ', ';}?><a href="<?php echo $app->url; ?>"><?php echo $app->name; ?></a><?php } ?>
				</p>
				<?php } ?>

				<!-- Email -->
				<?php if (!empty($report_email)) { ?>
				<p>
					<?php echo Kohana::lang('ui_main.report_option_2'); ?>:<br/>
					<a href="mailto:<?php echo $report_email?>"><?php echo $report_email?></a>
				</p>
				<?php } ?>

				<!-- Twitter -->
				<?php if (!empty($twitter_hashtag_array)) { ?>
				<p>
					<?php echo Kohana::lang('ui_main.report_option_3'); ?>:<br/>
					<?php foreach ($twitter_hashtag_array as $twitter_hashtag) { ?>
						<span>#<?php echo $twitter_hashtag; ?></span>
						<?php if ($twitter_hashtag != end($twitter_hashtag_array)) { ?>
							<br />
						<?php } ?>
					<?php } ?>
				</p>
				<?php } ?>

				<!-- Web Form -->
				<p>
					<a href="<?php echo url::site() . 'reports/submit/'; ?>">
					    <?php echo Kohana::lang('ui_main.report_option_4'); ?>
					</a>
				    
					<br/>
					<br/>
					<br/>
					<a id="submitAReport" class="sidepanel" href="<?php echo url::base();?>reports/submit">Submit Report</a>	
				</p>



			    </div>
			</div>
			
			<div id="front_social" class="wtm_head_up">
			    <a class="social" target="_blank" href="https://www.facebook.com/pages/Watch-The-Med/142123319326364#"><div id="social_facebook"></div></a>
			    <a class="social" target="_blank" href="http://twitter.com"><div id="social_twitter"></div></a>
			    <a class="social" target="_blank" href="<?php echo url::base();?>feed"><div id="social_rss"></div></a>
			</div>
			
			<!--
			<div id="tag cloud" class="wtm_head_up">
			    <h1>Not sure what goes here</h1>
			</div>
			-->

		    </div>

<div id="content" style="width:619px;min-height: 488px;">
	<div class="content-bg">
		<div class="big-block">
			<h1><?php echo html::escape($page_title) ?></h1>
			<div class="page_text"><?php 
			echo $page_description;
			Event::run('ushahidi_action.page_extra', $page_id);
			?></div>
		</div>
	</div>
</div>
