<?php
/**
 * The contact form.
 *
 * A developer's site exists to start a conversation about a home, so Dreamrs
 * ships the form rather than requiring a plugin for the one thing a visitor
 * came to do.
 *
 * It is a **shortcode**, not inline PHP in the pattern. That is not a style
 * preference: inc/front-page-setup.php expands patterns into real post content
 * so the copy stays editable, and PHP inside stored post content never runs.
 * A pattern that rendered the form inline would freeze whatever it produced at
 * activation into the page forever.
 *
 * A theme must not create database tables or register a post type, so Dreamrs
 * stores nothing. It validates, then hands the message to whoever wants it:
 *
 *   - `dreamrs_contact_handlers` — return true from any handler to say the
 *     message has been dealt with, and the built-in email is skipped. This is
 *     where a CRM, a helpdesk or a webhook hooks in.
 *   - `dreamrs_contact_email_to` / `_subject` / `_body` — adjust the email the
 *     theme sends when nothing else claims the message.
 *   - `dreamrs_contact_fields` — add, remove or relabel fields.
 *
 * The form works with JavaScript off: it is a plain POST to the same URL,
 * answered with a redirect carrying the result.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;

const DREAMRS_CONTACT_ACTION = 'dreamrs_contact';

/**
 * The fields the form asks for, in the template's order: the message first,
 * then who is asking.
 *
 * @return array<string, array<string, mixed>>
 */
function dreamrs_contact_fields() {
	$fields = array(
		'message' => array(
			'label'    => __( 'Your message', 'dreamrs' ),
			'type'     => 'textarea',
			'required' => true,
		),
		'name'    => array(
			'label'        => __( 'Your name', 'dreamrs' ),
			'type'         => 'text',
			'autocomplete' => 'name',
			'required'     => true,
		),
		'email'   => array(
			'label'        => __( 'Email address', 'dreamrs' ),
			'type'         => 'email',
			'autocomplete' => 'email',
			'required'     => true,
		),
		'subject' => array(
			'label'    => __( 'Subject', 'dreamrs' ),
			'type'     => 'text',
			'required' => false,
		),
	);

	/**
	 * Filters the contact form fields.
	 *
	 * @param array $fields Field definitions keyed by name.
	 */
	return apply_filters( 'dreamrs_contact_fields', $fields );
}

/**
 * Render one field, label included.
 *
 * Labels are real <label for> elements, always. A placeholder is not a label:
 * it is unreadable to some screen readers and it disappears the moment the
 * field has content.
 *
 * @param string $name  Field name.
 * @param array  $field Field definition.
 * @return string
 */
function dreamrs_contact_field( $name, $field ) {
	$id       = 'dreamrs-contact-' . $name;
	$required = ! empty( $field['required'] );

	$attributes = array(
		'id'    => $id,
		'name'  => $name,
		'class' => 'dreamrs-field__control',
	);

	if ( $required ) {
		$attributes['required'] = 'required';
	}
	if ( ! empty( $field['autocomplete'] ) ) {
		$attributes['autocomplete'] = $field['autocomplete'];
	}

	$out  = '<p class="dreamrs-field dreamrs-field--' . esc_attr( $name ) . '">';
	$out .= '<label class="dreamrs-field__label" for="' . esc_attr( $id ) . '">' . esc_html( $field['label'] );
	if ( $required ) {
		$out .= ' <span class="dreamrs-field__required" aria-hidden="true">*</span>';
	}
	$out .= '</label>';

	if ( 'textarea' === $field['type'] ) {
		$attributes['rows'] = 7;
		$out               .= '<textarea' . dreamrs_attributes( $attributes ) . '></textarea>';
	} else {
		$attributes['type'] = $field['type'];
		$out               .= '<input' . dreamrs_attributes( $attributes ) . '>';
	}

	return $out . '</p>';
}

/**
 * Build an attribute string from a map, escaping every value.
 *
 * @param array $attributes Attribute map.
 * @return string
 */
function dreamrs_attributes( $attributes ) {
	$out = '';
	foreach ( $attributes as $key => $value ) {
		if ( '' === $value && 'value' !== $key ) {
			continue;
		}
		$out .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
	}
	return $out;
}

/**
 * The URL a form was shown on, without any previous result parameter.
 *
 * Carried in a hidden field rather than read back from the referer:
 * wp_get_referer() returns false whenever the referer matches the current
 * request URI, which is always the case for a form that posts to its own page,
 * and the visitor would land on the front page with no form in sight.
 *
 * @return string
 */
function dreamrs_current_url() {
	global $wp;

	$permalink = is_singular() ? get_permalink() : '';
	if ( ! $permalink ) {
		// $wp->request is the path relative to the home URL, so this stays right
		// on a site that lives in a subdirectory.
		$path      = ( $wp && ! empty( $wp->request ) ) ? trailingslashit( $wp->request ) : '';
		$permalink = home_url( '/' . $path );
	}
	return remove_query_arg( array( 'dreamrs-contact', 'dreamrs-newsletter', 'dreamrs-newsletter-form' ), $permalink );
}

/**
 * Where to send a visitor back to after a POST, validated.
 *
 * @param string $field POST field holding the URL.
 * @param string $param Query parameter the result travels in.
 * @return string
 */
function dreamrs_form_redirect_target( $field, $param ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- the caller checks the nonce; this only picks where to redirect.
	$posted   = isset( $_POST[ $field ] ) ? esc_url_raw( wp_unslash( $_POST[ $field ] ) ) : '';
	$redirect = wp_validate_redirect( $posted, home_url( '/' ) );
	return remove_query_arg( array( $param ), $redirect );
}

/**
 * Redirect back to a form with a result, and stop.
 *
 * @param string $url    Where to go.
 * @param string $param  Query parameter carrying the result.
 * @param string $result Result key.
 * @param string $anchor Element id to scroll back to.
 */
function dreamrs_form_redirect( $url, $param, $result, $anchor ) {
	wp_safe_redirect( add_query_arg( $param, $result, $url ) . '#' . $anchor, 303 );
	exit;
}

/**
 * A result notice, styled by assets/css/forms.css.
 *
 * @param string $param    Query parameter carrying the result.
 * @param array  $messages Result key => [kind, text].
 * @param string $extra    Extra class for the notice.
 * @return string
 */
function dreamrs_form_notice( $param, $messages, $extra = '' ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
	$result = isset( $_GET[ $param ] ) ? sanitize_key( wp_unslash( $_GET[ $param ] ) ) : '';

	if ( ! isset( $messages[ $result ] ) ) {
		return '';
	}

	list( $kind, $text ) = $messages[ $result ];

	return '<p class="dreamrs-notice is-' . esc_attr( $kind ) . ( $extra ? ' ' . esc_attr( $extra ) : '' ) . '" role="status">' . esc_html( $text ) . '</p>';
}

/**
 * The contact form.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function dreamrs_contact_form( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'button' => __( 'Send message', 'dreamrs' ),
		),
		$atts,
		'dreamrs_contact_form'
	);

	$here = dreamrs_current_url();

	$out  = '<form class="dreamrs-contact" method="post" action="' . esc_url( $here ) . '#dreamrs-contact">';
	$out .= '<div id="dreamrs-contact" class="dreamrs-form-anchor"></div>';
	$out .= dreamrs_form_notice(
		'dreamrs-contact',
		array(
			'sent'    => array( 'ok', __( 'Thank you — your message is with us. We will reply by email within a working day.', 'dreamrs' ) ),
			'invalid' => array( 'error', __( 'Please check the form: every field marked * needs an answer.', 'dreamrs' ) ),
			'email'   => array( 'error', __( 'That email address does not look right.', 'dreamrs' ) ),
			'failed'  => array( 'error', __( 'Sorry, the message could not be sent. Please email or call us instead.', 'dreamrs' ) ),
			'expired' => array( 'error', __( 'That form had been open a while and expired. Please send it again.', 'dreamrs' ) ),
		)
	);
	$out .= wp_nonce_field( DREAMRS_CONTACT_ACTION, 'dreamrs_contact_nonce', true, false );
	$out .= '<input type="hidden" name="action" value="' . esc_attr( DREAMRS_CONTACT_ACTION ) . '">';
	$out .= '<input type="hidden" name="dreamrs_redirect" value="' . esc_url( $here ) . '">';

	// A field no visitor sees and no visitor fills in. Bots fill everything.
	$out .= '<p class="dreamrs-trap" aria-hidden="true">';
	$out .= '<label for="dreamrs-contact-website">' . esc_html__( 'Leave this field empty', 'dreamrs' ) . '</label>';
	$out .= '<input id="dreamrs-contact-website" type="text" name="dreamrs_website" tabindex="-1" autocomplete="off">';
	$out .= '</p>';

	$out .= '<div class="dreamrs-contact__grid">';
	foreach ( dreamrs_contact_fields() as $name => $field ) {
		$out .= dreamrs_contact_field( $name, $field );
	}
	$out .= '</div>';

	$out .= '<p class="dreamrs-contact__actions">';
	$out .= '<button type="submit" class="wp-block-button__link wp-element-button">' . esc_html( $atts['button'] ) . '</button>';
	$out .= '</p>';

	$out .= '</form>';

	return $out;
}

/**
 * Handle a submitted message.
 *
 * Runs on `template_redirect` so it can redirect before anything is sent —
 * the POST/redirect/GET that stops a refresh from sending it twice.
 */
function dreamrs_handle_contact() {
	if ( 'POST' !== ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- checked immediately below.
	if ( ! isset( $_POST['action'] ) || DREAMRS_CONTACT_ACTION !== $_POST['action'] ) {
		return;
	}

	$redirect = dreamrs_form_redirect_target( 'dreamrs_redirect', 'dreamrs-contact' );

	$nonce = isset( $_POST['dreamrs_contact_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['dreamrs_contact_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, DREAMRS_CONTACT_ACTION ) ) {
		dreamrs_form_redirect( $redirect, 'dreamrs-contact', 'expired', 'dreamrs-contact' );
	}

	// Silently accept and discard anything that filled the honeypot: telling a
	// bot it failed only teaches it to try again differently.
	if ( ! empty( $_POST['dreamrs_website'] ) ) {
		dreamrs_form_redirect( $redirect, 'dreamrs-contact', 'sent', 'dreamrs-contact' );
	}

	$message = array();
	foreach ( dreamrs_contact_fields() as $name => $field ) {
		$raw = isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised by type below.
		$raw = is_string( $raw ) ? $raw : '';

		if ( 'textarea' === $field['type'] ) {
			$value = sanitize_textarea_field( $raw );
		} elseif ( 'email' === $field['type'] ) {
			$value = sanitize_email( $raw );
		} else {
			$value = sanitize_text_field( $raw );
		}

		// Something typed that sanitize_email() threw away is a bad address, not
		// a missing one, and the visitor should be told which.
		if ( 'email' === $field['type'] && '' === $value && '' !== trim( $raw ) ) {
			dreamrs_form_redirect( $redirect, 'dreamrs-contact', 'email', 'dreamrs-contact' );
		}

		if ( ! empty( $field['required'] ) && '' === $value ) {
			dreamrs_form_redirect( $redirect, 'dreamrs-contact', 'invalid', 'dreamrs-contact' );
		}

		$message[ $name ] = $value;
	}

	if ( ! empty( $message['email'] ) && ! is_email( $message['email'] ) ) {
		dreamrs_form_redirect( $redirect, 'dreamrs-contact', 'email', 'dreamrs-contact' );
	}

	/**
	 * Filters whether the message has already been handled.
	 *
	 * Return true from any handler and Dreamrs will not send its own email —
	 * which is how a form plugin, a CRM or a webhook takes this over.
	 *
	 * @param bool  $handled Whether something has dealt with the message.
	 * @param array $message The sanitised message.
	 */
	$handled = apply_filters( 'dreamrs_contact_handlers', false, $message );

	if ( ! $handled ) {
		$handled = dreamrs_contact_email( $message );
	}

	dreamrs_form_redirect( $redirect, 'dreamrs-contact', $handled ? 'sent' : 'failed', 'dreamrs-contact' );
}
add_action( 'template_redirect', 'dreamrs_handle_contact' );

/**
 * Email the message to the site's admin address.
 *
 * From: is the site's own address, never the visitor's. Putting the visitor
 * there fails SPF and DMARC — the mail is sent by this server, not by their
 * provider — and it is the classic route to header injection. Reply-To carries
 * them instead, and wp_mail() rejects a header containing a newline.
 *
 * @param array $message Sanitised message.
 * @return bool
 */
function dreamrs_contact_email( $message ) {
	$to = apply_filters( 'dreamrs_contact_email_to', get_option( 'admin_email' ) );

	if ( ! $to || ! is_email( $to ) ) {
		return false;
	}

	/* translators: %s: site name. */
	$subject = sprintf( __( '[%s] Website message', 'dreamrs' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );
	if ( ! empty( $message['subject'] ) ) {
		$subject .= ': ' . $message['subject'];
	}
	$subject = apply_filters( 'dreamrs_contact_email_subject', $subject, $message );

	$lines  = array();
	$fields = dreamrs_contact_fields();
	foreach ( $message as $name => $value ) {
		if ( '' === $value ) {
			continue;
		}
		$label   = isset( $fields[ $name ]['label'] ) ? $fields[ $name ]['label'] : $name;
		$lines[] = $label . ': ' . $value;
	}

	$body = apply_filters( 'dreamrs_contact_email_body', implode( "\n", $lines ), $message );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( ! empty( $message['email'] ) && is_email( $message['email'] ) ) {
		$headers[] = 'Reply-To: ' . $message['email'];
	}

	return (bool) wp_mail( $to, $subject, $body, $headers );
}
