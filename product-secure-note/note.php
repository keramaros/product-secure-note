<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( get_option( 'psnp_enable' ) !== 'yes' ) {
	return;
}
?>
<p class="psnp">
    <span class="psnp-title">
        <?php if ( get_option( 'psnp_icon' ) === 'yes' ): ?>
            <svg fill="<?php echo esc_attr( get_option( 'psnp_color' ) ); ?>" width="<?php echo esc_attr( get_option( 'psnp_svg_size' ) ); ?>px" viewBox="0 0 36 36" version="1.1" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <path d="M31.25,7.4a43.79,43.79,0,0,1-6.62-2.35,45,45,0,0,1-6.08-3.21L18,1.5l-.54.35a45,45,0,0,1-6.08,3.21A43.79,43.79,0,0,1,4.75,7.4L4,7.59v8.34c0,13.39,13.53,18.4,13.66,18.45l.34.12.34-.12c.14,0,13.66-5.05,13.66-18.45V7.59ZM30,15.93c0,11-10,15.61-12,16.43-2-.82-12-5.44-12-16.43V9.14a47.54,47.54,0,0,0,6.18-2.25,48.23,48.23,0,0,0,5.82-3,48.23,48.23,0,0,0,5.82,3A47.54,47.54,0,0,0,30,9.14Z" class="clr-i-outline clr-i-outline-path-1"></path>
            <path d="M10.88,16.87a1,1,0,0,0-1.41,1.41l6,6L26.4,13.77A1,1,0,0,0,25,12.33l-9.47,9.19Z" class="clr-i-outline clr-i-outline-path-2"></path>
            <rect x="0" y="0" width="36" height="36" fill-opacity="0"/>
        </svg>
        <?php endif; ?>
        <strong><?php echo esc_html( get_option( 'psnp_title' ) ); ?></strong><br>
    </span>
	<?php if ( strlen( get_option( 'psnp_excerpt' ) ) ): ?>
        <small>
			<?php echo esc_html( get_option( 'psnp_excerpt' ) ); ?>
			<?php if ( strlen( get_option( 'psnp_content' ) ) ): ?>
                <input type="checkbox" id="psnp-checkbox">
                <label style="color: <?php echo esc_attr( get_option( 'psnp_color' ) ); ?>" for="psnp-checkbox"><?php echo esc_html( get_option( 'psnp_user_action_label' ) ?: __( 'Read more', 'product-secure-note' ) ); ?></label>
                <span><?php echo esc_html( get_option( 'psnp_content' ) ); ?></span>
			<?php endif; ?>
        </small>
	<?php endif; ?>
</p>