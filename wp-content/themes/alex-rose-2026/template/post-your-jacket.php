<?php
/**
 * Template Name: Post Your Jacket
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header();
?>

<main id="main" class="page-post-your-jacket" tabindex="-1">
	<section class="pyj-hero">
		<img class="pyj-hero__img" src="<?php echo esc_url(alex_rose_2026_uploads_url('2026/06/process-basting.jpg')); ?>" alt="" aria-hidden="true" loading="eager">
		<div class="pyj-hero__shade" aria-hidden="true"></div>
		<div class="pyj-hero__inner">
			<div class="pyj-hero__rule" aria-hidden="true"></div>
			<p class="pyj-hero__kicker"><?php esc_html_e('No Tape Measure Required', 'alex-rose-2026'); ?></p>
			<h1 class="pyj-hero__title"><?php esc_html_e('Post Your Jacket.', 'alex-rose-2026'); ?></h1>
			<p class="pyj-hero__lead"><?php esc_html_e('Send us a jacket that fits you. We measure it, then send it straight back, all within 48 hours.', 'alex-rose-2026'); ?></p>
		</div>
	</section>

	<section class="pyj-steps">
		<div class="pyj-steps__inner ar-container ar-container--6xl">
			<div class="pyj-steps__grid">
				<article class="pyj-step">
					<span class="pyj-step__num">01</span>
					<h3 class="pyj-step__title"><?php esc_html_e('We send you a box', 'alex-rose-2026'); ?></h3>
					<p class="pyj-step__body"><?php esc_html_e('Request below and we post you a prepaid box and returns label, free of charge.', 'alex-rose-2026'); ?></p>
				</article>
				<article class="pyj-step">
					<span class="pyj-step__num">02</span>
					<h3 class="pyj-step__title"><?php esc_html_e('Post us your jacket', 'alex-rose-2026'); ?></h3>
					<p class="pyj-step__body"><?php esc_html_e('Pack your jacket, attach the label, and drop it at any Royal Mail collection point.', 'alex-rose-2026'); ?></p>
				</article>
				<article class="pyj-step">
					<span class="pyj-step__num">03</span>
					<h3 class="pyj-step__title"><?php esc_html_e('We take your measurements', 'alex-rose-2026'); ?></h3>
					<p class="pyj-step__body"><?php esc_html_e('Your master tailor measures your jacket by hand, precisely, so your new jacket fits perfectly.', 'alex-rose-2026'); ?></p>
				</article>
				<article class="pyj-step">
					<span class="pyj-step__num">04</span>
					<h3 class="pyj-step__title"><?php esc_html_e('We return it within 48 hours', 'alex-rose-2026'); ?></h3>
					<p class="pyj-step__body"><?php esc_html_e('Your jacket is carefully repacked and sent straight back, usually within two working days.', 'alex-rose-2026'); ?></p>
				</article>
			</div>
		</div>
	</section>

	<section class="pyj-main">
		<div class="pyj-main__inner ar-container ar-container--5xl">
			<div class="pyj-main__grid">
				<div class="pyj-main__form-col">
					<p class="pyj-main__kicker"><?php esc_html_e('Get started', 'alex-rose-2026'); ?></p>
					<h2 class="pyj-main__title"><?php esc_html_e('Request your free box.', 'alex-rose-2026'); ?></h2>
					<p class="pyj-main__lead"><?php esc_html_e('Fill in your delivery address and we will send everything you need, no charge, no commitment.', 'alex-rose-2026'); ?></p>

					<div class="pyj-main__notice">
						<svg class="pyj-main__notice-icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<circle cx="6" cy="6" r="5.5" stroke="#C8A96A" stroke-width="1"></circle>
							<line x1="6" y1="5" x2="6" y2="9" stroke="#C8A96A" stroke-width="1" stroke-linecap="round"></line>
							<circle cx="6" cy="3.5" r="0.6" fill="#C8A96A"></circle>
						</svg>
						<span><?php esc_html_e('This service is available within the UK only.', 'alex-rose-2026'); ?></span>
					</div>

					<form class="pyj-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" data-pyj-form data-pyj-schedule-url="<?php echo esc_url(home_url('/schedule-a-call/')); ?>" novalidate aria-describedby="pyj-form-confirmation">
						<?php wp_nonce_field('pyj_request_box', 'pyj_nonce'); ?>
						<input type="hidden" name="action" value="pyj_request_box">
						<?php alex_rose_2026_form_honeypot_field(); ?>

						<div class="pyj-form__grid pyj-form__grid--2">
							<div class="pyj-form__field">
								<label class="pyj-form__label" for="pyj-name"><?php esc_html_e('Full name', 'alex-rose-2026'); ?><span aria-hidden="true">*</span></label>
								<input class="pyj-form__input" id="pyj-name" name="pyj_name" type="text" placeholder="<?php esc_attr_e('John Smith', 'alex-rose-2026'); ?>" required data-pyj-required>
							</div>
							<div class="pyj-form__field">
								<label class="pyj-form__label" for="pyj-email"><?php esc_html_e('Email address', 'alex-rose-2026'); ?><span aria-hidden="true">*</span></label>
								<input class="pyj-form__input" id="pyj-email" name="pyj_email" type="email" placeholder="john@example.com" required data-pyj-required>
							</div>
						</div>

						<div class="pyj-form__field">
							<label class="pyj-form__label" for="pyj-phone"><?php esc_html_e('Phone number (optional)', 'alex-rose-2026'); ?></label>
							<input class="pyj-form__input" id="pyj-phone" name="pyj_phone" type="tel" placeholder="+44 7700 000000">
						</div>

						<div class="pyj-form__field pyj-form__field--address">
							<p class="pyj-form__label"><?php esc_html_e('Delivery address for the box', 'alex-rose-2026'); ?></p>
							<div class="pyj-form__field">
								<label class="pyj-form__label" for="pyj-addr1"><?php esc_html_e('Address line 1', 'alex-rose-2026'); ?><span aria-hidden="true">*</span></label>
								<input class="pyj-form__input" id="pyj-addr1" name="pyj_addr1" type="text" placeholder="<?php esc_attr_e('12 High Street', 'alex-rose-2026'); ?>" required data-pyj-required>
							</div>
							<div class="pyj-form__field">
								<label class="pyj-form__label" for="pyj-addr2"><?php esc_html_e('Address line 2 (optional)', 'alex-rose-2026'); ?></label>
								<input class="pyj-form__input" id="pyj-addr2" name="pyj_addr2" type="text" placeholder="<?php esc_attr_e('Apartment, suite, etc.', 'alex-rose-2026'); ?>">
							</div>
							<div class="pyj-form__grid pyj-form__grid--2">
								<div class="pyj-form__field">
									<label class="pyj-form__label" for="pyj-city"><?php esc_html_e('Town or city', 'alex-rose-2026'); ?><span aria-hidden="true">*</span></label>
									<input class="pyj-form__input" id="pyj-city" name="pyj_city" type="text" placeholder="<?php esc_attr_e('Leeds', 'alex-rose-2026'); ?>" required data-pyj-required>
								</div>
								<div class="pyj-form__field">
									<label class="pyj-form__label" for="pyj-postcode"><?php esc_html_e('Postcode', 'alex-rose-2026'); ?><span aria-hidden="true">*</span></label>
									<input class="pyj-form__input" id="pyj-postcode" name="pyj_postcode" type="text" placeholder="<?php esc_attr_e('LS1 1AB', 'alex-rose-2026'); ?>" required data-pyj-required>
								</div>
							</div>
						</div>

						<div class="pyj-form__field">
							<label class="pyj-form__label" for="pyj-notes"><?php esc_html_e('Any notes? (optional)', 'alex-rose-2026'); ?></label>
							<textarea class="pyj-form__input pyj-form__textarea" id="pyj-notes" name="pyj_notes" rows="3" placeholder="<?php esc_attr_e('Jacket type, any special requirements, etc.', 'alex-rose-2026'); ?>"></textarea>
						</div>

						<button class="pyj-form__btn" type="submit" data-pyj-submit disabled><?php esc_html_e('Send Me the Box', 'alex-rose-2026'); ?></button>
						<p class="pyj-form__error" data-pyj-error role="alert" hidden></p>
					</form>

					<div id="pyj-form-confirmation" class="pyj-form__confirmation" role="status" aria-live="polite" hidden>
						<p class="pyj-form__confirmation-kicker"><?php esc_html_e('Request received', 'alex-rose-2026'); ?></p>
						<h3 class="pyj-form__confirmation-title"><?php esc_html_e('Your box request is in.', 'alex-rose-2026'); ?></h3>
						<p class="pyj-form__confirmation-body"><?php esc_html_e('Thank you. Harold will arrange your prepaid box and returns label shortly.', 'alex-rose-2026'); ?></p>
					</div>
				</div>

				<aside class="pyj-main__side-col">
					<div class="pyj-side__card">
						<p class="pyj-side__kicker"><?php esc_html_e('Our address', 'alex-rose-2026'); ?></p>
						<address class="pyj-side__address">
							<?php esc_html_e('Alex Rose Fine Tailoring', 'alex-rose-2026'); ?><br>
							<?php esc_html_e('2A Rodley Lane', 'alex-rose-2026'); ?><br>
							<?php esc_html_e('Rodley, Leeds', 'alex-rose-2026'); ?><br>
							<?php esc_html_e('LS13 1HU', 'alex-rose-2026'); ?>
						</address>
						<p class="pyj-side__text"><?php esc_html_e('Please request the box first. We will send you a prepaid label before you post anything.', 'alex-rose-2026'); ?></p>
					</div>

					<div class="pyj-side__list">
						<div class="pyj-side__item">
							<p class="pyj-side__item-title"><?php esc_html_e('Measurement only', 'alex-rose-2026'); ?></p>
							<p class="pyj-side__item-body"><?php esc_html_e('We do not alter or clean your jacket in any way.', 'alex-rose-2026'); ?></p>
						</div>
						<div class="pyj-side__item">
							<p class="pyj-side__item-title"><?php esc_html_e('Returned within 48 hrs', 'alex-rose-2026'); ?></p>
							<p class="pyj-side__item-body"><?php esc_html_e('By tracked post, at no cost to you.', 'alex-rose-2026'); ?></p>
						</div>
						<div class="pyj-side__item">
							<p class="pyj-side__item-title"><?php esc_html_e('Postage covered both ways', 'alex-rose-2026'); ?></p>
							<p class="pyj-side__item-body"><?php esc_html_e('There is no charge, no obligation, and no catch.', 'alex-rose-2026'); ?></p>
						</div>
					</div>

					<div class="pyj-side__callout">
						<p><?php echo wp_kses_post(sprintf(__('Prefer to call? Reach us on %s, Monday to Friday, 9am to 5pm.', 'alex-rose-2026'), '<a href="tel:+441134688588">0113 468 8588</a>')); ?></p>
					</div>
				</aside>
			</div>
		</div>
	</section>
</main>

<?php get_footer();
