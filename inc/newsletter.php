<?php
/**
 * The newsletter sign-up.
 *
 * The template puts an email field in its footer and its blog sidebar. A field
 * that posts to nowhere is worse than none, so this one works without a plugin:
 * it validates the address and emails it to the site owner, who can add it to
 * whatever list they keep. A mailing-list plugin takes over through one filter:
 *
 *   - `dreamrs_newsletter_handlers` — return true from a handler that has
 *     subscribed the address, and the theme sends no email of its own.
 *
 * Nothing is stored: a theme must not create tables, and an address list in
 * wp_options is personal data nobody would know to look for. It is a shortcode
 * for the same reason as the contact form (see inc/contact.php), and it shares
 * that form's redirect, notice and honeypot helpers.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;

const DREAMRS_NEWSLETTER_ACTION = 'dreamrs_newsletter';

/**
 * The sign-up form.
 *
 * `layout="inline"` is the footer's pill with a send button inside it; the
 * default stacks the field over a full-width button, as the sidebar does.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function dreamrs_newsletter_form( $atts = array() ) {
	static $count = 0;
	++$count;

	$atts = shortcode_atts(
		array(
			'layout' => 'stacked',
			'button' => __( 'Subscribe', 'dreamrs' ),
		),
		$atts,
		'dreamrs_newsletter'
	);

	$inline = 'inline' === $atts['layout'];
	$here   = dreamrs_current_url();
	$anchor = 'dreamrs-newsletter-' . $count;
	$id     = $anchor . '-email';

	$out  = '<form class="dreamrs-newsletter' . ( $inline ? ' dreamrs-newsletter--inline' : '' ) . '" method="post" action="' . esc_url( $here ) . '#' . esc_attr( $anchor ) . '">';
	$out .= '<div id="' . esc_attr( $anchor ) . '" class="dreamrs-form-anchor"></div>';

	// Only the form that was sent shows the result, when a page carries two.
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
	$which = isset( $_GET['dreamrs-newsletter-form'] ) ? absint( $_GET['dreamrs-newsletter-form'] ) : 0;
	if ( $which === $count ) {
		$out .= dreamrs_form_notice(
			'dreamrs-newsletter',
			array(
				'sent'    => array( 'ok', __( 'Thank you — you are on the list.', 'dreamrs' ) ),
				'email'   => array( 'error', __( 'That email address does not look right.', 'dreamrs' ) ),
				'failed'  => array( 'error', __( 'Sorry, that did not go through. Please try again later.', 'dreamrs' ) ),
				'expired' => array( 'error', __( 'That form had been open a while and expired. Please try again.', 'dreamrs' ) ),
			),
			'dreamrs-notice--compact'
		);
	}

	$out .= wp_nonce_field( DREAMRS_NEWSLETTER_ACTION, 'dreamrs_newsletter_nonce', true, false );
	$out .= '<input type="hidden" name="action" value="' . esc_attr( DREAMRS_NEWSLETTER_ACTION ) . '">';
	$out .= '<input type="hidden" name="dreamrs_newsletter_form" value="' . esc_attr( $count ) . '">';
	$out .= '<input type="hidden" name="dreamrs_newsletter_redirect" value="' . esc_url( $here ) . '">';

	$out .= '<p class="dreamrs-trap" aria-hidden="true">';
	$out .= '<label for="' . esc_attr( $anchor ) . '-website">' . esc_html__( 'Leave this field empty', 'dreamrs' ) . '</label>';
	$out .= '<input id="' . esc_attr( $anchor ) . '-website" type="text" name="dreamrs_website" tabindex="-1" autocomplete="off">';
	$out .= '</p>';

	$out .= '<p class="dreamrs-newsletter__row">';
	$out .= '<label class="screen-reader-text" for="' . esc_attr( $id ) . '">' . esc_html__( 'Email address', 'dreamrs' ) . '</label>';
	$out .= '<input id="' . esc_attr( $id ) . '" class="dreamrs-field__control dreamrs-newsletter__email" type="email" name="dreamrs_newsletter_email" required autocomplete="email" placeholder="' . esc_attr__( 'Email address', 'dreamrs' ) . '">';
	if ( $inline ) {
		$out .= '<button type="submit" class="dreamrs-newsletter__send"><span class="screen-reader-text">' . esc_html( $atts['button'] ) . '</span></button>';
	} else {
		$out .= '<button type="submit" class="wp-block-button__link wp-element-button dreamrs-newsletter__button">' . esc_html( $atts['button'] ) . '</button>';
	}
	$out .= '</p>';
	$out .= '</form>';

	return $out;
}

/**
 * Handle a sign-up.
 */
function dreamrs_handle_newsletter() {
	if ( 'POST' !== ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- checked immediately below.
	if ( ! isset( $_POST['action'] ) || DREAMRS_NEWSLETTER_ACTION !== $_POST['action'] ) {
		return;
	}

	$redirect = dreamrs_form_redirect_target( 'dreamrs_newsletter_redirect', 'dreamrs-newsletter' );
	$form     = isset( $_POST['dreamrs_newsletter_form'] ) ? absint( $_POST['dreamrs_newsletter_form'] ) : 1;
	$redirect = add_query_arg( 'dreamrs-newsletter-form', $form, remove_query_arg( 'dreamrs-newsletter-form', $redirect ) );
	$anchor   = 'dreamrs-newsletter-' . $form;

	$nonce = isset( $_POST['dreamrs_newsletter_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['dreamrs_newsletter_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, DREAMRS_NEWSLETTER_ACTION ) ) {
		dreamrs_form_redirect( $redirect, 'dreamrs-newsletter', 'expired', $anchor );
	}

	if ( ! empty( $_POST['dreamrs_website'] ) ) {
		dreamrs_form_redirect( $redirect, 'dreamrs-newsletter', 'sent', $anchor );
	}

	$email = isset( $_POST['dreamrs_newsletter_email'] ) ? sanitize_email( wp_unslash( $_POST['dreamrs_newsletter_email'] ) ) : '';
	if ( ! $email || ! is_email( $email ) ) {
		dreamrs_form_redirect( $redirect, 'dreamrs-newsletter', 'email', $anchor );
	}

	/**
	 * Filters whether the sign-up has already been handled.
	 *
	 * @param bool   $handled Whether something has subscribed the address.
	 * @param string $email   The sanitised address.
	 */
	$handled = apply_filters( 'dreamrs_newsletter_handlers', false, $email );

	if ( ! $handled ) {
		$to = apply_filters( 'dreamrs_contact_email_to', get_option( 'admin_email' ) );
		if ( $to && is_email( $to ) ) {
			/* translators: %s: site name. */
			$subject = sprintf( __( '[%s] Newsletter sign-up', 'dreamrs' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );
			/* translators: %s: email address. */
			$body    = sprintf( __( '%s asked to join the newsletter.', 'dreamrs' ), $email );
			$handled = (bool) wp_mail( $to, $subject, $body, array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email ) );
		}
	}

	dreamrs_form_redirect( $redirect, 'dreamrs-newsletter', $handled ? 'sent' : 'failed', $anchor );
}
add_action( 'template_redirect', 'dreamrs_handle_newsletter' );
