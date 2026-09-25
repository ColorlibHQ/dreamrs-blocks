<?php
/**
 * Dreamrs demo content: everything colorlibhub.com/dreamrs-blocks/ shows beyond
 * what the theme builds by itself.
 *
 * Run it against the demo site, as the web server's user, with the theme active:
 *
 *   sudo -u www-data wp --path=/var/www/colorlibhub.com/public \
 *     --url=https://colorlibhub.com/dreamrs-blocks/ eval "require '<dir>/import.php';"
 *
 * `wp eval` + `require`, not `wp eval-file`: eval-file runs the file inside a
 * function, where a `global` is null. This file uses no globals at all, so it
 * also works from a Playground blueprint's runPHP step.
 *
 * What it does, and it is safe to run again (each step looks for its own work
 * first and leaves it alone):
 *   1. site title and tagline;
 *   2. the theme's starter pages and menu, by calling the theme's own
 *      dreamrs_create_front_page() — it is guarded by the theme's flag, so on a
 *      site where activation already built them it does nothing;
 *   3. an author, "Dreamrs Studio" (Author role, this site only), and six
 *      posts by it with categories, tags, excerpts and featured images, and
 *      two comments on the newest;
 *   4. removes WordPress's "Hello world!" post and "Sample Page".
 *
 * The photographs are the files in media/, sideloaded into the media library.
 * They are demo content, not theme files: the theme zip never contains them
 * (build-zip.sh leaves .dev out), and none of them is a photograph the theme
 * itself uses, so no picture appears twice on the home page.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$dreamrs_demo_log = static function ( $message ) {
	if ( class_exists( 'WP_CLI' ) ) {
		WP_CLI::log( $message );
	} else {
		echo esc_html( $message ) . "\n";
	}
};

if ( ! function_exists( 'dreamrs_create_front_page' ) ) {
	$dreamrs_demo_log( 'Dreamrs is not the active theme on this site; nothing imported.' );
	return;
}

$dreamrs_demo_dir = __DIR__ . '/media/';

// 1. Site identity.
update_option( 'blogname', 'Dreamrs' );
update_option( 'blogdescription', 'Homes designed and built in the city' );

// 2. The theme's own starter pages and menu. Idempotent by the theme's flag.
dreamrs_create_front_page();
$dreamrs_demo_log( 'Starter pages: ' . ( get_option( 'dreamrs_front_page_created' ) ? 'built (' . get_option( 'dreamrs_front_page_created' ) . ')' : 'NOT built' ) );

// 3. Posts. Photographs are Unsplash-licensed; the credit is stored in each
// attachment's description.
$dreamrs_demo_posts = array(
	array(
		'slug'     => 'why-we-draw-the-stairs-first',
		'title'    => 'Why we draw the stairs first',
		'category' => 'Interiors',
		'tags'     => array( 'stairs', 'daylight', 'lofts' ),
		'days'     => 3,
		'image'    => 'folded-stair-oak-floor.jpg',
		'alt'      => 'A folded white steel stair with oak treads climbing a sunlit white wall above an oak floor',
		'credit'   => 'Photo: r t on Unsplash, https://unsplash.com/photos/DG1V-Cor1Es',
		'excerpt'  => 'A staircase decides where the light falls, where the doors go and how a house feels on the way to bed. So it is the first thing on our drawing board.',
		'body'     => array(
			'A staircase is the one piece of a house you use twenty times a day and never think about. It decides where the light falls, where the doors go, and how the house feels on the way to bed. So on every project it is the first thing we draw.',
			'At Linden Yard the stair is a single folded plate of steel, painted white, with oak treads that match the floor. It runs along the one wall that gets the afternoon sun, and because it has no risers the light carries straight through it into the hall.',
			'Getting the stair right early saves a surprising amount later: the landing sets the ceiling heights upstairs, and the space underneath became a pantry rather than a cupboard nobody opens.',
		),
		'quote'    => 'Draw the stair first and the rest of the plan falls into place around it.',
	),
	array(
		'slug'     => 'buying-off-plan-without-the-nerves',
		'title'    => 'Buying off-plan without the nerves',
		'category' => 'Buying a home',
		'tags'     => array( 'off-plan', 'apartments' ),
		'days'     => 9,
		'image'    => 'crane-over-new-block.jpg',
		'alt'      => 'A yellow tower crane working over the top floor of a new concrete apartment block, framed by trees against a blue sky',
		'credit'   => 'Photo: Lomig on Unsplash, https://unsplash.com/photos/Gau_dRIYPCw',
		'excerpt'  => 'Reserving a home that is still a hole in the ground takes nerve. Here is what we send every buyer, every month, until the keys are handed over.',
		'body'     => array(
			'Reserving a home that is still a hole in the ground takes nerve, and we would rather earn that trust than ask for it. Every buyer on Harbour Street gets the same three things, every month, until the keys are handed over.',
			'First, photographs from site, taken from the same spots each time so progress is easy to see. Second, the programme, with anything that has slipped marked in red and a reason beside it. Third, a named person to call, who was on site that week.',
			'None of it is complicated. It simply means nobody has to wonder what is happening to the biggest purchase of their life.',
		),
		'quote'    => 'Nobody should have to wonder what is happening to the biggest purchase of their life.',
	),
	array(
		'slug'     => 'a-terrace-that-steps-down-to-the-pool',
		'title'    => 'A terrace that steps down to the pool',
		'category' => 'Projects',
		'tags'     => array( 'gardens', 'exteriors' ),
		'days'     => 16,
		'image'    => 'terrace-steps-to-pool.jpg',
		'alt'      => 'A white two-storey house with glass balconies above a stone terrace whose wide steps lead down to a swimming pool',
		'credit'   => 'Photo: Alejandra Cifre González on Unsplash, https://unsplash.com/photos/ylyn5r4vxcA',
		'excerpt'  => 'The bottom of this garden took water every spring. We raised the house on a stone terrace and let the pool become the lowest point of the plot.',
		'body'     => array(
			'The bottom of this garden took water every spring, which is exactly where the owners wanted to swim. So the house sits on a stone terrace a metre above the old lawn, and five wide steps run the full width of it down to the pool.',
			'The steps are deep enough to sit on, and the paving falls gently away from the house towards planting beds that soak up the run-off. The pool stays clean, the ground floor stays dry, and the terrace has become the room the family uses most from May to September.',
		),
		'quote'    => 'The steps are deep enough to sit on, and the terrace has become the room the family uses most.',
	),
	array(
		'slug'     => 'glass-that-keeps-its-cool',
		'title'    => 'Glass that keeps its cool',
		'category' => 'Architecture',
		'tags'     => array( 'glazing', 'energy' ),
		'days'     => 24,
		'image'    => 'glass-balconies-timber-panel.jpg',
		'alt'      => 'Balconies with glass balustrades and dark steel soffits stacked up a pale apartment building with a strip of timber cladding, against a blue sky',
		'credit'   => 'Photo: Marija Zaric on Unsplash, https://unsplash.com/photos/cz75BNrAzOs',
		'excerpt'  => 'Floor-to-ceiling glass can bake the rooms behind it. Deep balconies above every window shade it when the sun is high and let it in when the sun is low.',
		'body'     => array(
			'Floor-to-ceiling glass can bake the rooms behind it by mid-afternoon. On the Riverside block every window sits under the balcony of the flat above, and each balcony projects far enough to shade the glass below it when the summer sun is high.',
			'In winter the sun is low enough to reach under the balconies and warm the floors. The balustrades are glass so the view is not lost, and the timber strip marks the stair core that runs up the middle of the building.',
			'The result is a building that looks almost entirely glazed and stays at a steady temperature through August without the air conditioning working overtime.',
		),
		'quote'    => 'Each balcony is the sunshade for the window below it.',
	),
	array(
		'slug'     => 'brick-that-gets-better-with-age',
		'title'    => 'Brick that gets better with age',
		'category' => 'Architecture',
		'tags'     => array( 'brick', 'townhouses' ),
		'days'     => 33,
		'image'    => 'brick-townhouse-pair.jpg',
		'alt'      => 'Two neighbouring red-brick townhouses with dark window surrounds and grey weatherboarding between them, in late afternoon light',
		'credit'   => 'Photo: James Sestric on Unsplash, https://unsplash.com/photos/hybV7dpIVlc',
		'excerpt'  => 'We choose a brick by leaving samples outside for a winter. The one that looks better in March than it did in October is the one we build with.',
		'body'     => array(
			'We choose a brick the slow way: a panel of each candidate goes up on site in October and stays there through the winter. The one that looks better in March than it did in the autumn is the one we build with.',
			'For the Mill Lane townhouses that was a dark, slightly irregular red, laid in lime mortar so the walls can breathe and any brick can be replaced without scarring its neighbours. The grey weatherboarding between the houses is the same timber as the window frames, painted to match the pointing.',
			'A new street should look settled within a few years, not tired. Brick is the one material we have found that does both.',
		),
		'quote'    => 'The brick that looks better in March than it did in October is the one we build with.',
	),
	array(
		'slug'     => 'planning-a-kitchen-around-one-window',
		'title'    => 'Planning a kitchen around one window',
		'category' => 'Interiors',
		'tags'     => array( 'kitchens', 'daylight', 'apartments' ),
		'days'     => 41,
		'image'    => 'galley-kitchen-window.jpg',
		'alt'      => 'A white galley kitchen with a black induction hob and a steel sink beneath a window that looks out over brick rooftops',
		'credit'   => 'Photo: gustaf von zeipel on Unsplash, https://unsplash.com/photos/CTS0Ppq4OfQ',
		'excerpt'  => 'A galley kitchen with one window has to earn every centimetre. We put the sink under the glass, the hob beside it and the tall cupboards where the light never reaches.',
		'body'     => array(
			'The flats on Harbour Street have galley kitchens with a single window at the end, and a galley has to earn every centimetre. We start from the window and work backwards.',
			'The sink goes under the glass, because that is where you stand longest. The hob sits beside it on the same run, so the cook still gets the view. The tall cupboards and the fridge go at the far end, where the daylight never reaches and nobody minds.',
			'Vertical tiles make the narrow room read taller, and white doors with slim steel handles keep it from feeling like a corridor.',
		),
		'quote'    => 'Put the sink under the window, because that is where you stand longest.',
	),
);

// The posts' author. The single-post template prints the author's name and
// biography under every post, so the demo writes as the firm rather than as
// whichever administrator runs this. An Author-role account on this site only,
// with a random password and a reserved example.com address: nobody logs in as
// it and nothing is ever mailed to it.
$dreamrs_demo_user = get_user_by( 'login', 'dreamrs-studio' );
if ( ! $dreamrs_demo_user ) {
	$dreamrs_demo_uid = wp_insert_user(
		wp_slash(
			array(
				'user_login'   => 'dreamrs-studio',
				'user_pass'    => wp_generate_password( 32, true, true ),
				'user_email'   => 'dreamrs-studio@example.com',
				'display_name' => 'Dreamrs Studio',
				'nickname'     => 'Dreamrs Studio',
				'first_name'   => 'Dreamrs',
				'last_name'    => 'Studio',
				'description'  => 'Architects, engineers and site managers under one roof in the Riverside Quarter. We write here about the homes we design, build and sell, and what we learn on site.',
				'role'         => 'author',
			)
		)
	);
	$dreamrs_demo_author = is_wp_error( $dreamrs_demo_uid ) ? 0 : (int) $dreamrs_demo_uid;
	$dreamrs_demo_log( $dreamrs_demo_author ? "Author created: Dreamrs Studio (#{$dreamrs_demo_author})" : 'FAILED to create the author: ' . $dreamrs_demo_uid->get_error_message() );
} else {
	$dreamrs_demo_author = (int) $dreamrs_demo_user->ID;
	if ( is_multisite() && ! is_user_member_of_blog( $dreamrs_demo_author ) ) {
		add_user_to_blog( get_current_blog_id(), $dreamrs_demo_author, 'author' );
	}
}
if ( ! $dreamrs_demo_author ) {
	$dreamrs_demo_admins = get_users(
		array(
			'role'    => 'administrator',
			'number'  => 1,
			'orderby' => 'ID',
			'fields'  => 'ID',
		)
	);
	$dreamrs_demo_author = $dreamrs_demo_admins ? (int) $dreamrs_demo_admins[0] : 1;
}

// kses would strip nothing here, but the post content is ours, so do not let a
// missing capability in a CLI request decide that.
$dreamrs_demo_kses = (bool) has_filter( 'content_save_pre', 'wp_filter_post_kses' );
if ( $dreamrs_demo_kses ) {
	kses_remove_filters();
}

foreach ( $dreamrs_demo_posts as $dreamrs_demo_post ) {
	$existing = get_posts(
		array(
			'name'           => $dreamrs_demo_post['slug'],
			'post_type'      => 'post',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future', 'trash' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( $existing ) {
		$post_id = (int) $existing[0];
		$dreamrs_demo_log( "Post exists: {$dreamrs_demo_post['slug']} (#{$post_id})" );
	} else {
		$blocks = '';
		foreach ( $dreamrs_demo_post['body'] as $i => $paragraph ) {
			$blocks .= "<!-- wp:paragraph -->\n<p>" . esc_html( $paragraph ) . "</p>\n<!-- /wp:paragraph -->\n\n";
			if ( 0 === $i ) {
				$blocks .= "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>" . esc_html( $dreamrs_demo_post['quote'] ) . "</p>\n<!-- /wp:paragraph --></blockquote>\n<!-- /wp:quote -->\n\n";
			}
		}

		$term = term_exists( $dreamrs_demo_post['category'], 'category' );
		if ( ! $term ) {
			$term = wp_insert_term( $dreamrs_demo_post['category'], 'category' );
		}
		$cat_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;

		$post_id = wp_insert_post(
			wp_slash(
				array(
					'post_title'    => $dreamrs_demo_post['title'],
					'post_name'     => $dreamrs_demo_post['slug'],
					'post_author'   => $dreamrs_demo_author,
					'post_content'  => trim( $blocks ),
					'post_excerpt'  => $dreamrs_demo_post['excerpt'],
					'post_status'   => 'publish',
					'post_type'     => 'post',
					'post_date'     => wp_date( 'Y-m-d H:i:s', time() - $dreamrs_demo_post['days'] * DAY_IN_SECONDS ),
					'post_category' => array( $cat_id ),
					'tags_input'    => $dreamrs_demo_post['tags'],
				)
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			$dreamrs_demo_log( "FAILED post {$dreamrs_demo_post['slug']}: " . $post_id->get_error_message() );
			continue;
		}
		$dreamrs_demo_log( "Post created: {$dreamrs_demo_post['slug']} (#{$post_id})" );
	}

	if ( has_post_thumbnail( $post_id ) ) {
		continue;
	}

	// The attachment, found by the key this script gives it, or sideloaded
	// from media/ (a copy: media_handle_sideload() moves the file it is given).
	$key         = 'dreamrs-demo/' . $dreamrs_demo_post['image'];
	$attachments = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'meta_key'       => '_dreamrs_demo_media',
			'meta_value'     => $key,
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( $attachments ) {
		$attachment_id = (int) $attachments[0];
	} else {
		$source = $dreamrs_demo_dir . $dreamrs_demo_post['image'];
		if ( ! is_readable( $source ) ) {
			$dreamrs_demo_log( "MISSING media file {$source}" );
			continue;
		}
		$tmp = wp_tempnam( $dreamrs_demo_post['image'] );
		copy( $source, $tmp );

		$attachment_id = media_handle_sideload(
			array(
				'name'     => $dreamrs_demo_post['image'],
				'tmp_name' => $tmp,
			),
			$post_id,
			null,
			wp_slash(
				array(
					// Not the post's title: the attachment would take the post's
					// slug and push it to "-2".
					'post_title'   => ucfirst( str_replace( '-', ' ', basename( $dreamrs_demo_post['image'], '.jpg' ) ) ),
					'post_content' => $dreamrs_demo_post['credit'],
				)
			)
		);

		if ( is_wp_error( $attachment_id ) ) {
			wp_delete_file( $tmp );
			$dreamrs_demo_log( "FAILED media {$dreamrs_demo_post['image']}: " . $attachment_id->get_error_message() );
			continue;
		}

		update_post_meta( $attachment_id, '_dreamrs_demo_media', $key );
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', wp_slash( $dreamrs_demo_post['alt'] ) );
		$dreamrs_demo_log( "Media imported: {$dreamrs_demo_post['image']} (#{$attachment_id})" );
	}

	set_post_thumbnail( $post_id, $attachment_id );
}

if ( $dreamrs_demo_kses ) {
	kses_init_filters();
}

// Two comments on the newest post, so the comment list has something to show.
$dreamrs_demo_first = get_posts(
	array(
		'name'           => 'why-we-draw-the-stairs-first',
		'post_type'      => 'post',
		'posts_per_page' => 1,
		'fields'         => 'ids',
	)
);
if ( $dreamrs_demo_first && ! get_comments(
	array(
		'post_id' => $dreamrs_demo_first[0],
		'count'   => true,
	)
) ) {
	$dreamrs_demo_comments = array(
		array( 'Priya Nair', 'We have a stair in the middle of our plan and it eats the whole ground floor. Would you ever move one in a renovation?', 2 ),
		array( 'Tom Hadley', 'The pantry under the landing is a brilliant touch. How deep are the treads?', 1 ),
	);
	foreach ( $dreamrs_demo_comments as $dreamrs_demo_comment ) {
		wp_insert_comment(
			wp_slash(
				array(
					'comment_post_ID'  => $dreamrs_demo_first[0],
					'comment_author'   => $dreamrs_demo_comment[0],
					'comment_content'  => $dreamrs_demo_comment[1],
					'comment_approved' => 1,
					'comment_date'     => wp_date( 'Y-m-d H:i:s', time() - $dreamrs_demo_comment[2] * DAY_IN_SECONDS ),
				)
			)
		);
	}
	$dreamrs_demo_log( 'Comments added.' );
}

// 4. WordPress's own sample content, which is off-story.
foreach ( array(
	'post' => 'hello-world',
	'page' => 'sample-page',
) as $dreamrs_demo_type => $dreamrs_demo_slug ) {
	$dreamrs_demo_ids = get_posts(
		array(
			'name'           => $dreamrs_demo_slug,
			'post_type'      => $dreamrs_demo_type,
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'trash' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	foreach ( $dreamrs_demo_ids as $dreamrs_demo_id ) {
		wp_delete_post( $dreamrs_demo_id, true );
		$dreamrs_demo_log( "Removed {$dreamrs_demo_type} {$dreamrs_demo_slug}." );
	}
}

$dreamrs_demo_log( 'Done.' );
