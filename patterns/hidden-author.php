<?php
/**
 * Title: Author box
 * Slug: dreamrs/hidden-author
 * Description: The author's photograph, name and biography under a post.
 * Inserter: no
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"dreamrs-author-box","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"backgroundColor":"tint","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-author-box has-tint-background-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:post-author {"avatarSize":96,"showBio":true,"className":"dreamrs-author"} /--></div>
<!-- /wp:group -->
