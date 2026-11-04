<?php
/**
 * Shortcodes used inside the Zora's House templates:
 *   [zh_breadcrumbs]    Home / parent pages / current page, built from the page hierarchy.
 *   [zh_contact_form]   Contact Inquiry form. Submissions are saved under Messages and emailed to the site admin.
 *   [zh_newsletter]     Footer newsletter signup. Posts to a mailing-list provider when `action` is set,
 *                       otherwise saves the signup under Messages and emails the site admin.
 */

defined( 'ABSPATH' ) || exit;

/*
 * Run our shortcodes wherever their Shortcode block is placed. WordPress only expands shortcodes in
 * page content, not inside synced patterns such as the global footer.
 */
add_filter(
	'render_block_core/shortcode',
	function ( $html ) {
		return false !== strpos( $html, '[zh_' ) ? do_shortcode( shortcode_unautop( $html ) ) : $html;
	}
);

/*
 * Messages inbox: every form submission is stored here, so nothing is lost if email delivery fails.
 */
add_action(
	'init',
	function () {
		register_post_type(
			'zh_message',
			array(
				'labels'          => array(
					'name'          => __( 'Messages', 'zoras-house-pages' ),
					'singular_name' => __( 'Message', 'zoras-house-pages' ),
					'menu_name'     => __( 'Messages', 'zoras-house-pages' ),
					'all_items'     => __( 'All Messages', 'zoras-house-pages' ),
					'edit_item'     => __( 'Message', 'zoras-house-pages' ),
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => true,
				'menu_icon'       => 'dashicons-email-alt',
				'menu_position'   => 26,
				'supports'        => array( 'title', 'editor' ),
				'capability_type' => 'post',
				'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'    => true,
			)
		);
	}
);

/**
 * Save a submission as a private Message and email it to the site admin.
 *
 * @param string $title Message title.
 * @param array  $lines Label => value pairs.
 * @param string $reply Reply-to email address, if any.
 */
function zh_pages_store_message( $title, array $lines, $reply = '' ) {
	$body = '';
	foreach ( $lines as $label => $value ) {
		$body .= $label . ': ' . $value . "\n";
	}

	wp_insert_post(
		array(
			'post_type'    => 'zh_message',
			'post_status'  => 'private',
			'post_title'   => $title,
			'post_content' => esc_html( $body ),
		)
	);

	/**
	 * Filters who receives Zora's House form submissions. Defaults to the site admin email (Settings → General).
	 */
	$to      = apply_filters( 'zh_pages_form_recipient', get_option( 'admin_email' ) );
	$headers = $reply ? array( 'Reply-To: ' . $reply ) : array();
	wp_mail( $to, '[' . wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . '] ' . $title, $body, $headers );
}

/**
 * Where to send the visitor after a submission: back to the page they came from.
 */
function zh_pages_form_return( $status, $anchor ) {
	$back = isset( $_POST['zh_return'] ) ? wp_validate_redirect( esc_url_raw( wp_unslash( $_POST['zh_return'] ) ), home_url( '/' ) ) : home_url( '/' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- validated, and only used for the redirect.
	$back = remove_query_arg( array( 'zh_form', 'zh_news' ), $back );
	wp_safe_redirect( add_query_arg( $anchor === 'zh-newsletter' ? 'zh_news' : 'zh_form', $status, $back ) . '#' . $anchor );
	exit;
}

/*
 * [zh_breadcrumbs]
 */
add_shortcode(
	'zh_breadcrumbs',
	function () {
		$id = get_queried_object_id();
		if ( ! $id ) {
			return '';
		}
		$items = array( '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'zoras-house-pages' ) . '</a>' );
		foreach ( array_reverse( get_post_ancestors( $id ) ) as $ancestor ) {
			$items[] = '<a href="' . esc_url( get_permalink( $ancestor ) ) . '">' . esc_html( get_the_title( $ancestor ) ) . '</a>';
		}
		$items[] = '<span aria-current="page">' . esc_html( get_the_title( $id ) ) . '</span>';
		return '<nav class="zh-crumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'zoras-house-pages' ) . '">' . implode( ' <span aria-hidden="true">/</span> ', $items ) . '</nav>';
	}
);

/*
 * [zh_contact_form topics="Membership|Events|…" button="Send message"]
 * The "I'm reaching out about" list comes from `topics`. A link like /contact/?topic=Events preselects one.
 */
add_shortcode(
	'zh_contact_form',
	function ( $atts ) {
		$atts   = shortcode_atts(
			array(
				'topics' => 'Membership|Programs & leadership|Events & retreats|Partnering with Zora’s House|Visiting or booking the House|Something else',
				'button' => __( 'Send message', 'zoras-house-pages' ),
			),
			$atts,
			'zh_contact_form'
		);
		$topics = array_filter( array_map( 'trim', explode( '|', $atts['topics'] ) ) );
		$picked = isset( $_GET['topic'] ) ? sanitize_text_field( wp_unslash( $_GET['topic'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- only preselects an option.
		$status = isset( $_GET['zh_form'] ) ? sanitize_key( $_GET['zh_form'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		ob_start();
		?>
		<form class="zh-form" id="zh-contact" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php if ( 'sent' === $status ) : ?>
				<p class="zh-form__notice zh-form__notice--ok" role="status"><?php esc_html_e( 'Thank you — your message is on its way. We’ll be in touch soon.', 'zoras-house-pages' ); ?></p>
			<?php elseif ( 'error' === $status ) : ?>
				<p class="zh-form__notice zh-form__notice--err" role="alert"><?php esc_html_e( 'Please add your name, a valid email address and a message, then try again.', 'zoras-house-pages' ); ?></p>
			<?php endif; ?>
			<input type="hidden" name="action" value="zh_contact">
			<input type="hidden" name="zh_return" value="<?php echo esc_url( get_permalink() ); ?>">
			<?php wp_nonce_field( 'zh_contact', 'zh_nonce' ); ?>
			<div class="zh-form__row">
				<p class="zh-form__field">
					<label for="zh-name"><?php esc_html_e( 'Name', 'zoras-house-pages' ); ?></label>
					<input id="zh-name" name="zh_name" type="text" autocomplete="name" required maxlength="120">
				</p>
				<p class="zh-form__field">
					<label for="zh-email"><?php esc_html_e( 'Email', 'zoras-house-pages' ); ?></label>
					<input id="zh-email" name="zh_email" type="email" autocomplete="email" required maxlength="190">
				</p>
			</div>
			<p class="zh-form__field">
				<label for="zh-topic"><?php esc_html_e( 'I’m reaching out about', 'zoras-house-pages' ); ?></label>
				<select id="zh-topic" name="zh_topic">
					<?php foreach ( $topics as $topic ) : ?>
						<option<?php selected( $picked, $topic ); ?>><?php echo esc_html( $topic ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p class="zh-form__field">
				<label for="zh-message"><?php esc_html_e( 'Message', 'zoras-house-pages' ); ?></label>
				<textarea id="zh-message" name="zh_message" rows="7" required maxlength="5000"></textarea>
			</p>
			<p class="zh-form__hp" aria-hidden="true">
				<label for="zh-website">Website</label>
				<input id="zh-website" name="zh_website" type="text" tabindex="-1" autocomplete="off">
			</p>
			<div class="wp-block-buttons"><div class="wp-block-button is-style-zh-primary"><button class="wp-block-button__link wp-element-button" type="submit"><?php echo esc_html( $atts['button'] ); ?></button></div></div>
		</form>
		<?php
		return ob_get_clean();
	}
);

$zh_contact_handler = static function () {
	if ( ! isset( $_POST['zh_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['zh_nonce'] ), 'zh_contact' ) ) {
		zh_pages_form_return( 'error', 'zh-contact' );
	}
	// Honeypot: bots fill the hidden "website" field. Pretend it worked.
	if ( ! empty( $_POST['zh_website'] ) ) {
		zh_pages_form_return( 'sent', 'zh-contact' );
	}
	$name    = isset( $_POST['zh_name'] ) ? sanitize_text_field( wp_unslash( $_POST['zh_name'] ) ) : '';
	$email   = isset( $_POST['zh_email'] ) ? sanitize_email( wp_unslash( $_POST['zh_email'] ) ) : '';
	$topic   = isset( $_POST['zh_topic'] ) ? sanitize_text_field( wp_unslash( $_POST['zh_topic'] ) ) : '';
	$message = isset( $_POST['zh_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['zh_message'] ) ) : '';
	if ( '' === $name || ! is_email( $email ) || '' === $message ) {
		zh_pages_form_return( 'error', 'zh-contact' );
	}
	zh_pages_store_message(
		sprintf( /* translators: 1: topic, 2: sender name */ __( 'Contact: %1$s — %2$s', 'zoras-house-pages' ), $topic, $name ),
		array(
			__( 'Name', 'zoras-house-pages' )    => $name,
			__( 'Email', 'zoras-house-pages' )   => $email,
			__( 'Topic', 'zoras-house-pages' )   => $topic,
			__( 'Message', 'zoras-house-pages' ) => "\n" . $message,
		),
		$email
	);
	zh_pages_form_return( 'sent', 'zh-contact' );
};
add_action( 'admin_post_nopriv_zh_contact', $zh_contact_handler );
add_action( 'admin_post_zh_contact', $zh_contact_handler );

/*
 * [zh_newsletter action="https://…list-manage.com/subscribe/post?u=…" field="EMAIL" button="Sign up"]
 * Leave `action` empty until the mailing-list provider is chosen; signups are then saved under Messages.
 */
add_shortcode(
	'zh_newsletter',
	function ( $atts ) {
		$atts     = shortcode_atts(
			array(
				'action' => '',
				'field'  => 'EMAIL',
				'button' => __( 'Sign up', 'zoras-house-pages' ),
			),
			$atts,
			'zh_newsletter'
		);
		$external = '' !== trim( $atts['action'] );
		$status   = isset( $_GET['zh_news'] ) ? sanitize_key( $_GET['zh_news'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		ob_start();
		?>
		<form class="zh-signup" id="zh-newsletter" method="post" action="<?php echo esc_url( $external ? $atts['action'] : admin_url( 'admin-post.php' ) ); ?>"<?php echo $external ? ' target="_blank"' : ''; ?>>
			<label for="zh-signup-email"><?php esc_html_e( 'Email address', 'zoras-house-pages' ); ?></label>
			<div class="zh-signup__row">
				<input id="zh-signup-email" type="email" name="<?php echo esc_attr( $external ? $atts['field'] : 'zh_email' ); ?>" placeholder="you@example.com" autocomplete="email" required>
				<button type="submit"><?php echo esc_html( $atts['button'] ); ?></button>
			</div>
			<?php if ( ! $external ) : ?>
				<input type="hidden" name="action" value="zh_newsletter">
				<input type="hidden" name="zh_return" value="<?php echo esc_url( is_singular() ? get_permalink() : home_url( '/' ) ); ?>">
				<?php wp_nonce_field( 'zh_newsletter', 'zh_nonce' ); ?>
				<span class="zh-form__hp" aria-hidden="true"><input name="zh_website" type="text" tabindex="-1" autocomplete="off"></span>
			<?php endif; ?>
			<?php if ( 'sent' === $status ) : ?>
				<p class="zh-signup__msg" role="status"><?php esc_html_e( 'You’re on the list. Thank you!', 'zoras-house-pages' ); ?></p>
			<?php elseif ( 'error' === $status ) : ?>
				<p class="zh-signup__msg" role="alert"><?php esc_html_e( 'Please enter a valid email address.', 'zoras-house-pages' ); ?></p>
			<?php endif; ?>
		</form>
		<?php
		return ob_get_clean();
	}
);

$zh_newsletter_handler = static function () {
	if ( ! isset( $_POST['zh_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['zh_nonce'] ), 'zh_newsletter' ) ) {
		zh_pages_form_return( 'error', 'zh-newsletter' );
	}
	if ( ! empty( $_POST['zh_website'] ) ) {
		zh_pages_form_return( 'sent', 'zh-newsletter' );
	}
	$email = isset( $_POST['zh_email'] ) ? sanitize_email( wp_unslash( $_POST['zh_email'] ) ) : '';
	if ( ! is_email( $email ) ) {
		zh_pages_form_return( 'error', 'zh-newsletter' );
	}
	zh_pages_store_message(
		/* translators: %s: email address */
		sprintf( __( 'Newsletter signup — %s', 'zoras-house-pages' ), $email ),
		array( __( 'Email', 'zoras-house-pages' ) => $email ),
		$email
	);
	zh_pages_form_return( 'sent', 'zh-newsletter' );
};
add_action( 'admin_post_nopriv_zh_newsletter', $zh_newsletter_handler );
add_action( 'admin_post_zh_newsletter', $zh_newsletter_handler );
