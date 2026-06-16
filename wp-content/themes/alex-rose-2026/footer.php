<?php
/**
 * Footer — marketing layout
 *
 * @package Alex_Rose_2026
 */
$year = (string) gmdate('Y');
?>
<footer class="site-footer site-footer--marketing" role="contentinfo">
	<div class="site-footer--marketing__grid">
		<div class="site-footer--marketing__brand">
			<a class="site-footer--marketing__brand-link" href="<?php echo esc_url(home_url('/')); ?>">
				<?php
				$logo_id = (int) get_theme_mod('custom_logo');
				if ($logo_id) {
					echo wp_get_attachment_image($logo_id, 'full', false, array('alt' => esc_attr(get_bloginfo('name'))));
				} else {
					?>
					<img src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/05/alex-rose-logo.png')); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
					<?php
				}
				?>
			</a>
			<p class="site-footer--marketing__tag"><?php esc_html_e('Traditional tailoring meets a modern ordering experience.', 'alex-rose-2026'); ?></p>
			<div class="site-footer--marketing__contact">
				<a class="site-footer--marketing__contact-phone" href="tel:+441132570022">0113 257 0022</a>
				<a class="site-footer--marketing__contact-phone site-footer--marketing__contact-phone--alt" href="tel:+441134688588">0113 468 8588</a>
				<a class="site-footer--marketing__contact-email" href="mailto:tailor@alexrose.uk">tailor@alexrose.uk</a>
				<p class="site-footer--marketing__address"><?php esc_html_e('2A Rodley Lane, Rodley, Leeds LS13 1HU', 'alex-rose-2026'); ?></p>
			</div>
			<div class="site-footer--marketing__social">
				<a href="https://www.tiktok.com/@alexrosetailoring" target="_blank" rel="noopener noreferrer" aria-label="TikTok">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.34 6.34 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.72a8.27 8.27 0 0 0 4.84 1.55V6.82a4.85 4.85 0 0 1-1.07-.13z"></path></svg>
				</a>
				<a href="https://www.facebook.com/alexrosesince1945/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
				</a>
				<a href="https://www.instagram.com/alexrosetailoring/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
				</a>
				<a href="https://www.linkedin.com/in/harold-rose/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
				</a>
			</div>
		</div>
		<div>
			<p class="site-footer--marketing__col-title"><?php esc_html_e('The Craft', 'alex-rose-2026'); ?></p>
			<ul class="site-footer--marketing__links">
				<li><a href="<?php echo esc_url(home_url('/our-story')); ?>"><?php esc_html_e('Our Story', 'alex-rose-2026'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/how-it-works')); ?>"><?php esc_html_e('How It Works', 'alex-rose-2026'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/design')); ?>"><?php esc_html_e('Design Your Jacket', 'alex-rose-2026'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/gift-vouchers')); ?>"><?php esc_html_e('Gift Vouchers', 'alex-rose-2026'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/faq')); ?>"><?php esc_html_e('FAQ', 'alex-rose-2026'); ?></a></li>
			</ul>
		</div>
		<div>
			<p class="site-footer--marketing__col-title"><?php esc_html_e('Get in Touch', 'alex-rose-2026'); ?></p>
			<ul class="site-footer--marketing__links">
				<li><a href="<?php echo esc_url(home_url('/contact')); ?>"><?php esc_html_e('Send an Enquiry', 'alex-rose-2026'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/showroom')); ?>"><?php esc_html_e('Visit the Showroom', 'alex-rose-2026'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/schedule-a-call')); ?>"><?php esc_html_e('Schedule a Call', 'alex-rose-2026'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/request-cloth-samples')); ?>"><?php esc_html_e('Request Free Samples', 'alex-rose-2026'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/request-tape-measure')); ?>"><?php esc_html_e('Request Tape Measure', 'alex-rose-2026'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/post-your-jacket')); ?>"><?php esc_html_e('Post Us Your Jacket', 'alex-rose-2026'); ?></a></li>
			</ul>
		</div>
		<div>
			<p class="site-footer--marketing__col-title"><?php esc_html_e('Information', 'alex-rose-2026'); ?></p>
			<ul class="site-footer--marketing__links">
				<li><a href="<?php echo esc_url(home_url('/delivery-information')); ?>"><?php esc_html_e('Delivery Information', 'alex-rose-2026'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>"><?php esc_html_e('Privacy Policy', 'alex-rose-2026'); ?></a></li>
				<li><a href="<?php echo esc_url(home_url('/terms-and-conditions')); ?>"><?php esc_html_e('Terms & Conditions', 'alex-rose-2026'); ?></a></li>
			</ul>
		</div>
	</div>
	<div class="site-footer--marketing__bar">
		<div class="site-footer--marketing__bar-inner">
			<p class="site-footer--marketing__copy">
				<?php
				printf(
					/* translators: %s: year */
					esc_html__('© %s Alex Rose Fine Tailoring Ltd · Company 02587407', 'alex-rose-2026'),
					esc_html($year)
				);
				?>
			</p>
			<p class="site-footer--marketing__tagline"><?php esc_html_e('Fine tailoring since 1945', 'alex-rose-2026'); ?></p>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
