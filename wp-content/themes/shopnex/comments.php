<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package shopnex
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
	return;
}
?>

<?php
// If comments are closed and there are comments, let's leave a little note.
if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
	?>
	<p class="no-comments"><?php esc_html_e('Comments are closed.', 'shopnex'); ?></p>
	<?php
endif;

// Comment Form
if (comments_open()) :
	?>
	<div class="comment-form-wrapper">
		<div class="comment-form-header">
			<div class="comment-form-icon">
				<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5">
					<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
				</svg>
			</div>
			<div>
				<div class="comment-form-title"><?php esc_html_e('Join the Conversation', 'shopnex'); ?></div>
				<p class="comment-form-subtitle"><?php esc_html_e('Share your thoughts — every perspective adds value.', 'shopnex'); ?></p>
			</div>
		</div>
		<?php
		$commenter = wp_get_current_commenter();
		comment_form(array(
			'class_form'           => 'comment-form-fields',
			'title_reply'         => '',
			'title_reply_to'       => '',
			'cancel_reply_link'    => '<span class="cancel-reply-link">' . esc_html__('Cancel reply', 'shopnex') . '</span>',
			'label_submit'         => esc_html__('Post Comment', 'shopnex'),
			'submit_button'       => '<button name="%1$s" type="submit" id="%2$s" class="%3$s"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg> %4$s</button>',
			'submit_field'        => '<div class="comment-form-actions">%1$s %2$s</div>',
			'comment_field'        => '<div class="comment-form-group"><label for="comment" class="comment-form-label">' . esc_html__('Your Comment', 'shopnex') . ' <span class="required">*</span></label><textarea id="comment" name="comment" class="comment-form-textarea" placeholder="' . esc_attr__('Write your comment here...', 'shopnex') . '" required></textarea></div>',
			'fields'              => array(
				'author' => '<div class="comment-form-group"><label for="author" class="comment-form-label">' . esc_html__('Name', 'shopnex') . ' <span class="required">*</span></label><input id="author" name="author" type="text" class="comment-form-input" placeholder="' . esc_attr__('John Doe', 'shopnex') . '" value="' . esc_attr($commenter['comment_author']) . '" required /></div>',
				'email'  => '<div class="comment-form-group"><label for="email" class="comment-form-label">' . esc_html__('Email', 'shopnex') . ' <span class="required">*</span></label><input id="email" name="email" type="email" class="comment-form-input" placeholder="' . esc_attr('john@example.com') . '" value="' . esc_attr($commenter['comment_author_email']) . '" required /></div>',
				'url'    => '',
			),
			'comment_notes_before' => '',
			'comment_notes_after'  => '',
		));
		?>
	</div>
	<?php
endif;

// Comment List
if (have_comments()) :
	?>
	<div class="comment-list-wrapper">
		<?php
		wp_list_comments(array(
			'style'      => 'div',
			'short_ping' => true,
			'callback'    => 'shopnex_comment_callback',
			'format'     => 'html5',
		));
		?>
	</div>
	
	<?php
	// Comment Pagination
	if (get_comment_pages_count() > 1 && get_option('page_comments')) :
		?>
		<nav class="comments-pagination">
			<?php
			paginate_comments_links(array(
				'prev_text' => '<svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>',
				'next_text' => '<svg viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>',
			));
			?>
		</nav>
	<?php endif; ?>
<?php endif; ?>
