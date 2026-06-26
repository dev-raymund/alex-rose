<?php
/**
 * FAQ — grouped question list + bottom CTA block.
 *
 * @package Alex_Rose_2026
 */

if (! defined('ABSPATH')) {
	exit;
}

$groups = array(
	array(
		'title' => __('About Alex Rose', 'alex-rose-2026'),
		'items' => array(
			array(
				'q' => __('Who is Alex Rose?', 'alex-rose-2026'),
				'a' => sprintf(
					/* translators: %s: link to our story page */
					__('Alexander Rose founded the company in 1945, after leaving the army and deciding to open a small clothing factory in Cross Harrison Street, behind the Grand Theatre in Leeds. His son Harold Rose became Managing Director in 1972, carrying the family tradition of fine tailoring into the next generation. Today, the same passion for craft that Alexander built from nothing remains at the heart of everything we do. %s', 'alex-rose-2026'),
					'<a href="' . esc_url(home_url('/our-story/')) . '">' . esc_html__('Read our full story.', 'alex-rose-2026') . '</a>'
				),
			),
			array(
				'q' => __('Where are the jackets made?', 'alex-rose-2026'),
				'a' => __('All our garments are carefully crafted by one of Europe\'s top tailoring companies who also supply many Savile Row tailors.', 'alex-rose-2026'),
			),
			array(
				'q' => __('Where do you source the cloth from?', 'alex-rose-2026'),
				'a' => __('We have long standing trading relationships with the traditional mills of West Yorkshire along with the world\'s leading cloth merchants.', 'alex-rose-2026'),
			),
		),
	),
	array(
		'title' => __('Getting Measured', 'alex-rose-2026'),
		'items' => array(
			array(
				'q' => __('How do I supply my measurements?', 'alex-rose-2026'),
				'a' => sprintf(
					/* translators: %s: link to schedule a call page */
					__('There are instructions on how to measure. If you have any difficulty you can %s with our master tailor who will be delighted to assist you.', 'alex-rose-2026'),
					'<a href="' . esc_url(home_url('/schedule-a-call/')) . '">' . esc_html__('book a Zoom meeting', 'alex-rose-2026') . '</a>'
				),
			),
			array(
				'q' => __('I don\'t have a tape measure.', 'alex-rose-2026'),
				'a' => sprintf(
					/* translators: %s: email link */
					__('Just send us an email and we will post one out to you. %s', 'alex-rose-2026'),
					'<a href="mailto:tailor@alexrose.uk">tailor@alexrose.uk</a>'
				),
			),
		),
	),
	array(
		'title' => __('Fabrics & Samples', 'alex-rose-2026'),
		'items' => array(
			array(
				'q' => __('Can I request sample fabrics before I place an order?', 'alex-rose-2026'),
				'a' => __('We want you to be happy with your choice of fabric – therefore, we can post up to three sample cloths along with a tape measure, all free of charge.', 'alex-rose-2026'),
			),
		),
	),
	array(
		'title' => __('Your Order', 'alex-rose-2026'),
		'items' => array(
			array(
				'q' => __('What is so special about your jackets?', 'alex-rose-2026'),
				'a' => __('We are passionate about the quality of our garments. The fit is superb and the internal construction that you can\'t see only uses the very best interlinings. Your jacket will be so personal to you that we will even embroider your name in the jacket lining.', 'alex-rose-2026'),
			),
			array(
				'q' => __('What is the delivery date?', 'alex-rose-2026'),
				'a' => __('Normally around 5 weeks, subject to cloth availability and your location.', 'alex-rose-2026'),
			),
			array(
				'q' => __('What happens if the jacket doesn\'t fit?', 'alex-rose-2026'),
				'a' => sprintf(
					/* translators: %s: email link */
					__('If you have any problems, please email some photos and we will arrange a time to discuss over Zoom. %s', 'alex-rose-2026'),
					'<a href="mailto:tailor@alexrose.uk">tailor@alexrose.uk</a>'
				),
			),
			array(
				'q' => __('Can you despatch worldwide?', 'alex-rose-2026'),
				'a' => sprintf(
					/* translators: %s: email link */
					__('Yes, we can! Please %s if your country is not listed, and we will see what we can do.', 'alex-rose-2026'),
					'<a href="mailto:tailor@alexrose.uk">' . esc_html__('email us', 'alex-rose-2026') . '</a>'
				),
			),
		),
	),
	array(
		'title' => __('Getting in Touch', 'alex-rose-2026'),
		'items' => array(
			array(
				'q' => __('Can I talk to someone to discuss my order?', 'alex-rose-2026'),
				'a' => sprintf(
					/* translators: %s: link to schedule a call page */
					__('Absolutely! Please get in touch to %s with our master tailor.', 'alex-rose-2026'),
					'<a href="' . esc_url(home_url('/schedule-a-call/')) . '">' . esc_html__('book a 30-minute call on Teams or Google Meet', 'alex-rose-2026') . '</a>'
				),
			),
			array(
				'q' => __('How do I contact you?', 'alex-rose-2026'),
				'a' => sprintf(
					/* translators: 1: phone link, 2: email link, 3: contact form link */
					__('Call us on %1$s, email %2$s, or use the %3$s. We respond personally to every message.', 'alex-rose-2026'),
					'<a href="tel:+441134688588">+44 (0)113 468 8588</a>',
					'<a href="mailto:tailor@alexrose.uk">tailor@alexrose.uk</a>',
					'<a href="' . esc_url(home_url('/contact/')) . '">' . esc_html__('contact form', 'alex-rose-2026') . '</a>'
				),
			),
		),
	),
);

$allowed_a = array(
	'a' => array(
		'href'   => array(),
		'class'  => array(),
		'target' => array(),
		'rel'    => array(),
	),
	'strong' => array(),
	'em'     => array(),
	'br'     => array(),
);

$item_counter = 0;
?>
<section class="faq-list">
	<div class="faq-list__inner ar-container ar-container--4xl">
		<?php foreach ($groups as $group_index => $group) : ?>
			<section class="faq-group" aria-labelledby="faq-group-<?php echo esc_attr((string) $group_index); ?>">
				<h2 id="faq-group-<?php echo esc_attr((string) $group_index); ?>" class="faq-group__title">
					<?php echo esc_html($group['title']); ?>
				</h2>
				<div class="faq-group__items">
					<?php foreach ($group['items'] as $item) :
						$item_counter += 1;
						$question_id = 'faq-q-' . $item_counter;
						$answer_id   = 'faq-a-' . $item_counter;
						?>
						<div class="faq-item">
							<button
								type="button"
								class="faq-item__trigger"
								id="<?php echo esc_attr($question_id); ?>"
								aria-controls="<?php echo esc_attr($answer_id); ?>"
								aria-expanded="false"
								data-faq-trigger
							>
								<span class="faq-item__question"><?php echo esc_html($item['q']); ?></span>
								<span class="faq-item__icon" aria-hidden="true">
									<svg width="13" height="13" viewBox="0 0 13 13" fill="none" focusable="false">
										<line class="faq-item__icon-v" x1="6.5" y1="0" x2="6.5" y2="13" stroke="currentColor" stroke-width="1.2"></line>
										<line x1="0" y1="6.5" x2="13" y2="6.5" stroke="currentColor" stroke-width="1.2"></line>
									</svg>
								</span>
							</button>
							<div
								id="<?php echo esc_attr($answer_id); ?>"
								class="faq-item__panel"
								role="region"
								aria-labelledby="<?php echo esc_attr($question_id); ?>"
								aria-hidden="true"
							>
								<div class="faq-item__answer">
									<?php echo wp_kses($item['a'], $allowed_a); ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endforeach; ?>

		<div class="faq-cta">
			<p class="faq-cta__lead"><?php esc_html_e('Still have a question? Your master tailor reads every message personally.', 'alex-rose-2026'); ?></p>
			<div class="faq-cta__actions">
				<a class="faq-cta__btn faq-cta__btn--primary" href="<?php echo esc_url(home_url('/schedule-a-call/')); ?>">
					<?php esc_html_e('Book a Free Discovery Call', 'alex-rose-2026'); ?>
				</a>
				<a class="faq-cta__btn faq-cta__btn--ghost" href="<?php echo esc_url(home_url('/contact/')); ?>">
					<?php esc_html_e('Send an Enquiry', 'alex-rose-2026'); ?>
				</a>
				<a class="faq-cta__btn faq-cta__btn--ghost" href="tel:+441134688588">
					+44 (0)113 468 8588
				</a>
			</div>
		</div>
	</div>
</section>
