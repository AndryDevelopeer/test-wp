<?php
/*
Plugin Name: Comment IP Check (test)
Description: Adds IP address checking in comments.
Version: 1.0
Author: Andrei Kudriavtcev
*/

// Add hidden input
function add_hidden_field_to_comment_form() {
    echo '<input type="hidden" name="comment_ip" id="comment_ip" value="' . esc_attr($_SERVER['REMOTE_ADDR']) . '" />';
}
add_action('comment_form_logged_in_after', 'add_hidden_field_to_comment_form');
add_action('comment_form_after_fields', 'add_hidden_field_to_comment_form');

// JavaScript for add IP to comments form
function set_comment_ip_field_value() {
    if (is_singular() && comments_open()) {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const commentIPField = document.getElementById('comment_ip');
                if (commentIPField) {
                    commentIPField.value = '<?php echo esc_attr($_SERVER['REMOTE_ADDR']); ?>';
                }
            });
        </script>
        <?php
    }
}
add_action('wp_footer', 'set_comment_ip_field_value');

// Checking IP address when posting a comment
function check_comment_ip($commentdata) {
    $user_ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_POST['comment_ip']) && $_POST['comment_ip'] !== $user_ip) {
        wp_die('The comment cannot be published.');
    }
    return $commentdata;
}
add_filter('preprocess_comment', 'check_comment_ip');
