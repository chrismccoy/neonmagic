<?php
/**
 * NeonMagic Admin Meta Boxes
 *
 * @package NeonMagic
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Register Metaboxes
function neonmagic_register_meta_boxes() {
    $post_types = [ 'post' ];

    foreach ( $post_types as $type ) {
        add_meta_box(
            'nm_pricing',
            '<i class="dashicons dashicons-cart"></i> ' . __( 'Pricing & Download', 'neonmagic' ),
            'neonmagic_meta_pricing',
            $type,
            'normal',
            'high'
        );
        add_meta_box(
            'nm_file_info',
            '<i class="dashicons dashicons-media-archive"></i> ' . __( 'File Information', 'neonmagic' ),
            'neonmagic_meta_file_info',
            $type,
            'normal',
            'high'
        );
        add_meta_box(
            'nm_gallery',
            '<i class="dashicons dashicons-format-gallery"></i> ' . __( 'Preview Gallery', 'neonmagic' ),
            'neonmagic_meta_gallery',
            $type,
            'normal',
            'default'
        );
        add_meta_box(
            'nm_whats_included',
            '<i class="dashicons dashicons-yes-alt"></i> ' . __( "What's Included", 'neonmagic' ),
            'neonmagic_meta_whats_included',
            $type,
            'normal',
            'default'
        );
        add_meta_box(
            'nm_requirements',
            '<i class="dashicons dashicons-info-outline"></i> ' . __( 'Requirements', 'neonmagic' ),
            'neonmagic_meta_requirements',
            $type,
            'normal',
            'default'
        );
        add_meta_box(
            'nm_changelog',
            '<i class="dashicons dashicons-backup"></i> ' . __( 'Changelog', 'neonmagic' ),
            'neonmagic_meta_changelog',
            $type,
            'normal',
            'default'
        );
    }
}
add_action( 'add_meta_boxes', 'neonmagic_register_meta_boxes' );

// Pricing and Download
function neonmagic_meta_pricing( $post ) {
    wp_nonce_field( 'neonmagic_meta_pricing', 'nm_pricing_nonce' );

    $is_paid      = (bool) get_post_meta( $post->ID, '_nm_is_paid', true );
    $price        = get_post_meta( $post->ID, '_nm_price', true );
    $buy_url      = get_post_meta( $post->ID, '_nm_buy_url', true );
    $download_url = get_post_meta( $post->ID, '_nm_download_url', true );
    ?>
    <div class="nm-meta-box">
        <table>
            <tr>
                <th><label><?php esc_html_e( 'Paid Item?', 'neonmagic' ); ?></label></th>
                <td>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="checkbox"
                               id="nm_is_paid"
                               name="nm_is_paid"
                               value="1"
                               <?php checked( $is_paid ); ?>
                               style="width:18px;height:18px;cursor:pointer;" />
                        <span style="font-weight:600;"><?php esc_html_e( 'Yes — this is a paid item', 'neonmagic' ); ?></span>
                    </label>
                    <p class="description" style="margin-top:4px;font-size:12px;color:#666;">
                        <?php esc_html_e( 'When checked the download button becomes a "Buy Now" link pointing to the external buy URL.', 'neonmagic' ); ?>
                    </p>
                </td>
            </tr>

            <tr class="nm-toggle-paid" id="nm-row-price" <?php echo $is_paid ? '' : 'style="display:none"'; ?>>
                <th><label for="nm_price"><?php esc_html_e( 'Price ($)', 'neonmagic' ); ?></label></th>
                <td>
                    <input type="text"
                           id="nm_price"
                           name="nm_price"
                           value="<?php echo esc_attr( $price ); ?>"
                           placeholder="e.g. 29" />
                </td>
            </tr>
            <tr class="nm-toggle-paid" id="nm-row-buy-url" <?php echo $is_paid ? '' : 'style="display:none"'; ?>>
                <th><label for="nm_buy_url"><?php esc_html_e( 'Buy URL', 'neonmagic' ); ?></label></th>
                <td>
                    <input type="url"
                           id="nm_buy_url"
                           name="nm_buy_url"
                           value="<?php echo esc_attr( $buy_url ); ?>"
                           placeholder="https://gumroad.com/l/..." />
                    <p class="description" style="font-size:12px;color:#666;margin-top:3px;">
                        <?php esc_html_e( 'External purchase URL (Gumroad, Payhip, Lemon Squeezy, etc.)', 'neonmagic' ); ?>
                    </p>
                </td>
            </tr>

            <tr class="nm-toggle-free" id="nm-row-download-url" <?php echo $is_paid ? 'style="display:none"' : ''; ?>>
                <th><label for="nm_download_url"><?php esc_html_e( 'Download URL', 'neonmagic' ); ?></label></th>
                <td>
                    <input type="url"
                           id="nm_download_url"
                           name="nm_download_url"
                           value="<?php echo esc_attr( $download_url ); ?>"
                           placeholder="https://example.com/files/my-file.zip" />
                    <p class="description" style="font-size:12px;color:#666;margin-top:3px;">
                        <?php esc_html_e( 'Direct link to the downloadable file. Can be a CDN URL or a file in your media library.', 'neonmagic' ); ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <script>
    (function(){
        var cb = document.getElementById('nm_is_paid');
        if (!cb) return;
        function toggle() {
            var paid = cb.checked;
            document.querySelectorAll('.nm-toggle-paid').forEach(function(el){ el.style.display = paid ? '' : 'none'; });
            document.querySelectorAll('.nm-toggle-free').forEach(function(el){ el.style.display = paid ? 'none' : ''; });
        }
        cb.addEventListener('change', toggle);
    })();
    </script>
    <?php
}

// File Information
function neonmagic_meta_file_info( $post ) {
    wp_nonce_field( 'neonmagic_meta_file_info', 'nm_file_info_nonce' );

    $file_type      = get_post_meta( $post->ID, '_nm_file_type', true );
    $file_size      = get_post_meta( $post->ID, '_nm_file_size', true );
    $compatibility  = get_post_meta( $post->ID, '_nm_compatibility', true );
    $version        = get_post_meta( $post->ID, '_nm_version', true );
    $download_count = get_post_meta( $post->ID, '_nm_download_count', true );
    $license        = get_post_meta( $post->ID, '_nm_license', true );
    $attribution    = get_post_meta( $post->ID, '_nm_attribution', true );
    ?>
    <div class="nm-meta-box">
        <table>
            <tr>
                <th><label for="nm_file_type"><?php esc_html_e( 'File Extension', 'neonmagic' ); ?></label></th>
                <td><input type="text" id="nm_file_type" name="nm_file_type" value="<?php echo esc_attr( $file_type ); ?>" placeholder="ZIP" /></td>
            </tr>
            <tr>
                <th><label for="nm_file_size"><?php esc_html_e( 'File Size', 'neonmagic' ); ?></label></th>
                <td><input type="text" id="nm_file_size" name="nm_file_size" value="<?php echo esc_attr( $file_size ); ?>" placeholder="48 MB" /></td>
            </tr>
            <tr>
                <th><label for="nm_compatibility"><?php esc_html_e( 'Compatibility', 'neonmagic' ); ?></label></th>
                <td><input type="text" id="nm_compatibility" name="nm_compatibility" value="<?php echo esc_attr( $compatibility ); ?>" placeholder="Figma, Photoshop, Any..." /></td>
            </tr>
            <tr>
                <th><label for="nm_version"><?php esc_html_e( 'Version', 'neonmagic' ); ?></label></th>
                <td><input type="text" id="nm_version" name="nm_version" value="<?php echo esc_attr( $version ); ?>" placeholder="v1.0.0" /></td>
            </tr>
            <tr>
                <th><label for="nm_license"><?php esc_html_e( 'License', 'neonmagic' ); ?></label></th>
                <td><input type="text" id="nm_license" name="nm_license" value="<?php echo esc_attr( $license ); ?>" placeholder="Free — Personal &amp; Commercial" /></td>
            </tr>
            <tr>
                <th><label for="nm_attribution"><?php esc_html_e( 'Attribution', 'neonmagic' ); ?></label></th>
                <td><input type="text" id="nm_attribution" name="nm_attribution" value="<?php echo esc_attr( $attribution ); ?>" placeholder="Not Required" /></td>
            </tr>
            <tr>
                <th><label for="nm_download_count"><?php esc_html_e( 'Download Count', 'neonmagic' ); ?></label></th>
                <td>
                    <input type="number" id="nm_download_count" name="nm_download_count" value="<?php echo esc_attr( $download_count ?: 0 ); ?>" min="0" />
                    <p class="description" style="font-size:12px;color:#666;margin-top:3px;">
                        <?php esc_html_e( 'Auto-incremented on each free download. You can also set this manually.', 'neonmagic' ); ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

// Gallery
function neonmagic_meta_gallery( $post ) {
    wp_nonce_field( 'neonmagic_meta_gallery', 'nm_gallery_nonce' );

    $gallery_ids = get_post_meta( $post->ID, '_nm_gallery', true );
    $ids_array   = $gallery_ids ? array_filter( array_map( 'intval', explode( ',', $gallery_ids ) ) ) : [];
    ?>
    <div class="nm-meta-box">
        <p style="font-size:12px;color:#666;margin-bottom:8px;">
            <?php esc_html_e( 'Add up to 4 additional preview screenshots. The featured image is always the main preview.', 'neonmagic' ); ?>
        </p>

        <div class="nm-gallery-wrap" id="nm-gallery-wrap">
            <?php foreach ( $ids_array as $img_id ) :
                $thumb = wp_get_attachment_image_src( $img_id, 'thumbnail' );
                if ( ! $thumb ) continue;
                ?>
                <div class="nm-gallery-item" data-id="<?php echo esc_attr( $img_id ); ?>">
                    <img src="<?php echo esc_url( $thumb[0] ); ?>" alt="" />
                    <span class="nm-gallery-remove" data-id="<?php echo esc_attr( $img_id ); ?>" title="<?php esc_attr_e( 'Remove', 'neonmagic' ); ?>">&#x2715;</span>
                </div>
            <?php endforeach; ?>
        </div>

        <input type="hidden" id="nm_gallery" name="nm_gallery" value="<?php echo esc_attr( $gallery_ids ); ?>" />

        <button type="button" class="nm-add-btn" id="nm-gallery-add">
            <i class="dashicons dashicons-plus-alt2" style="vertical-align:middle;margin-right:4px;"></i>
            <?php esc_html_e( 'Add Images', 'neonmagic' ); ?>
        </button>
    </div>
    <?php
}

// Whats Includes
function neonmagic_meta_whats_included( $post ) {
    wp_nonce_field( 'neonmagic_meta_whats_included', 'nm_whats_included_nonce' );

    $value = get_post_meta( $post->ID, '_nm_whats_included', true );
    ?>
    <div class="nm-meta-box">
        <p style="font-size:12px;color:#666;margin-bottom:6px;">
            <?php esc_html_e( "Enter one item per line (e.g. '200+ Components'). These appear in the checklist on the single post page.", 'neonmagic' ); ?>
        </p>
        <textarea id="nm_whats_included"
                  name="nm_whats_included"
                  rows="8"
                  placeholder="200+ Components&#10;Dark &amp; Light Mode&#10;Figma Source File&#10;Documentation PDF"><?php echo esc_textarea( $value ); ?></textarea>
    </div>
    <?php
}

// Requirements
function neonmagic_meta_requirements( $post ) {
    wp_nonce_field( 'neonmagic_meta_requirements', 'nm_requirements_nonce' );

    $value = get_post_meta( $post->ID, '_nm_requirements', true );
    $lines = $value ? array_filter( array_map( 'trim', explode( "\n", $value ) ) ) : [];
    ?>
    <div class="nm-meta-box">
        <p style="font-size:12px;color:#666;margin-bottom:10px;">
            <?php esc_html_e( 'Add requirements. Select the icon type and enter the requirement text.', 'neonmagic' ); ?>
        </p>

        <div id="nm-req-list">
            <?php if ( $lines ) :
                foreach ( $lines as $line ) :
                    $parts = explode( '|', $line, 2 );
                    $type  = isset( $parts[0] ) ? trim( $parts[0] ) : 'check';
                    $text  = isset( $parts[1] ) ? trim( $parts[1] ) : trim( $line );
                    ?>
                    <div class="nm-req-row">
                        <select name="nm_req_type[]" style="width:90px;flex-shrink:0;border:2px solid #000;padding:4px;">
                            <option value="check"  <?php selected( $type, 'check'  ); ?>><?php esc_html_e( '✓ Check', 'neonmagic' ); ?></option>
                            <option value="info"   <?php selected( $type, 'info'   ); ?>><?php esc_html_e( 'ℹ Info',  'neonmagic' ); ?></option>
                            <option value="cross"  <?php selected( $type, 'cross'  ); ?>><?php esc_html_e( '✗ Cross', 'neonmagic' ); ?></option>
                        </select>
                        <input type="text" name="nm_req_text[]" value="<?php echo esc_attr( $text ); ?>" placeholder="<?php esc_attr_e( 'Requirement text...', 'neonmagic' ); ?>" />
                        <button type="button" class="nm-req-remove" title="<?php esc_attr_e( 'Remove', 'neonmagic' ); ?>">&#x2715;</button>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>

        <input type="hidden" id="nm_requirements" name="nm_requirements" value="<?php echo esc_attr( $value ); ?>" />

        <button type="button" class="nm-add-btn" id="nm-req-add" style="margin-top:8px;">
            <i class="dashicons dashicons-plus-alt2" style="vertical-align:middle;margin-right:4px;"></i>
            <?php esc_html_e( 'Add Requirement', 'neonmagic' ); ?>
        </button>
    </div>
    <?php
}

// Changelog
function neonmagic_meta_changelog( $post ) {
    wp_nonce_field( 'neonmagic_meta_changelog', 'nm_changelog_nonce' );

    $raw       = get_post_meta( $post->ID, '_nm_changelog', true );
    $changelog = $raw ? json_decode( $raw, true ) : [];
    if ( ! is_array( $changelog ) ) $changelog = [];

    $color_options = [
        'cyan'   => __( 'Cyan',   'neonmagic' ),
        'yellow' => __( 'Yellow', 'neonmagic' ),
        'pink'   => __( 'Pink',   'neonmagic' ),
        'white'  => __( 'White',  'neonmagic' ),
    ];
    ?>
    <div class="nm-meta-box">
        <p style="font-size:12px;color:#666;margin-bottom:10px;">
            <?php esc_html_e( 'Add version history entries. Changes are comma-separated within each entry.', 'neonmagic' ); ?>
        </p>

        <div id="nm-changelog-list">
            <?php foreach ( $changelog as $entry ) : ?>
                <div class="nm-changelog-entry">
                    <button type="button" class="nm-remove-entry" title="<?php esc_attr_e( 'Remove', 'neonmagic' ); ?>">&#x2715;</button>
                    <div class="nm-entry-row" style="flex-wrap:wrap;gap:8px;">
                        <div style="flex:0 0 120px;">
                            <label><?php esc_html_e( 'Version', 'neonmagic' ); ?></label>
                            <input type="text" name="nm_cl_version[]" value="<?php echo esc_attr( $entry['version'] ?? '' ); ?>" placeholder="v1.0.0" />
                        </div>
                        <div style="flex:0 0 150px;">
                            <label><?php esc_html_e( 'Date', 'neonmagic' ); ?></label>
                            <input type="text" name="nm_cl_date[]" value="<?php echo esc_attr( $entry['date'] ?? '' ); ?>" placeholder="Jan 01, 2024" />
                        </div>
                        <div style="flex:0 0 100px;">
                            <label><?php esc_html_e( 'Color', 'neonmagic' ); ?></label>
                            <select name="nm_cl_color[]" style="border:2px solid #000;padding:6px;width:100%;">
                                <?php foreach ( $color_options as $val => $label ) : ?>
                                    <option value="<?php echo esc_attr( $val ); ?>" <?php selected( ( $entry['color'] ?? 'cyan' ), $val ); ?>>
                                        <?php echo esc_html( $label ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="nm-entry-changes">
                        <label><?php esc_html_e( 'Changes (one per line)', 'neonmagic' ); ?></label>
                        <textarea name="nm_cl_changes[]" rows="4" placeholder="<?php esc_attr_e( "Added new components\nFixed bug\nImproved performance", 'neonmagic' ); ?>"><?php echo esc_textarea( implode( "\n", (array) ( $entry['changes'] ?? [] ) ) ); ?></textarea>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <input type="hidden" id="nm_changelog" name="nm_changelog" value="" />

        <button type="button" class="nm-add-btn" id="nm-changelog-add" style="margin-top:8px;">
            <i class="dashicons dashicons-plus-alt2" style="vertical-align:middle;margin-right:4px;"></i>
            <?php esc_html_e( 'Add Version Entry', 'neonmagic' ); ?>
        </button>

        <script type="text/template" id="nm-changelog-template">
            <div class="nm-changelog-entry">
                <button type="button" class="nm-remove-entry" title="<?php esc_attr_e( 'Remove', 'neonmagic' ); ?>">&#x2715;</button>
                <div class="nm-entry-row" style="flex-wrap:wrap;gap:8px;">
                    <div style="flex:0 0 120px;">
                        <label><?php esc_html_e( 'Version', 'neonmagic' ); ?></label>
                        <input type="text" name="nm_cl_version[]" value="" placeholder="v1.0.0" />
                    </div>
                    <div style="flex:0 0 150px;">
                        <label><?php esc_html_e( 'Date', 'neonmagic' ); ?></label>
                        <input type="text" name="nm_cl_date[]" value="" placeholder="Jan 01, 2024" />
                    </div>
                    <div style="flex:0 0 100px;">
                        <label><?php esc_html_e( 'Color', 'neonmagic' ); ?></label>
                        <select name="nm_cl_color[]" style="border:2px solid #000;padding:6px;width:100%;">
                            <?php foreach ( $color_options as $val => $label ) : ?>
                                <option value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $label ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="nm-entry-changes">
                    <label><?php esc_html_e( 'Changes (one per line)', 'neonmagic' ); ?></label>
                    <textarea name="nm_cl_changes[]" rows="4" placeholder="<?php esc_attr_e( "Added new components\nFixed bug\nImproved performance", 'neonmagic' ); ?>"></textarea>
                </div>
            </div>
        </script>
    </div>
    <?php
}

// Save Meta
function neonmagic_save_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( wp_is_post_revision( $post_id ) ) return;

    if ( isset( $_POST['nm_pricing_nonce'] ) && wp_verify_nonce( $_POST['nm_pricing_nonce'], 'neonmagic_meta_pricing' ) ) {
        $is_paid = isset( $_POST['nm_is_paid'] ) ? 1 : 0;
        update_post_meta( $post_id, '_nm_is_paid', $is_paid );
        update_post_meta( $post_id, '_nm_price',        sanitize_text_field( $_POST['nm_price']        ?? '' ) );
        update_post_meta( $post_id, '_nm_buy_url',      esc_url_raw( $_POST['nm_buy_url']              ?? '' ) );
        update_post_meta( $post_id, '_nm_download_url', esc_url_raw( $_POST['nm_download_url']         ?? '' ) );
    }

    if ( isset( $_POST['nm_file_info_nonce'] ) && wp_verify_nonce( $_POST['nm_file_info_nonce'], 'neonmagic_meta_file_info' ) ) {
        update_post_meta( $post_id, '_nm_file_type',      sanitize_text_field( $_POST['nm_file_type']      ?? '' ) );
        update_post_meta( $post_id, '_nm_file_size',      sanitize_text_field( $_POST['nm_file_size']      ?? '' ) );
        update_post_meta( $post_id, '_nm_compatibility',  sanitize_text_field( $_POST['nm_compatibility']  ?? '' ) );
        update_post_meta( $post_id, '_nm_version',        sanitize_text_field( $_POST['nm_version']        ?? '' ) );
        update_post_meta( $post_id, '_nm_license',        sanitize_text_field( $_POST['nm_license']        ?? '' ) );
        update_post_meta( $post_id, '_nm_attribution',    sanitize_text_field( $_POST['nm_attribution']    ?? '' ) );
        update_post_meta( $post_id, '_nm_download_count', absint( $_POST['nm_download_count']              ?? 0 ) );
    }

    if ( isset( $_POST['nm_gallery_nonce'] ) && wp_verify_nonce( $_POST['nm_gallery_nonce'], 'neonmagic_meta_gallery' ) ) {
        $gallery_raw = sanitize_text_field( $_POST['nm_gallery'] ?? '' );
        $ids = array_filter( array_map( 'intval', explode( ',', $gallery_raw ) ) );
        update_post_meta( $post_id, '_nm_gallery', implode( ',', $ids ) );
    }

    if ( isset( $_POST['nm_whats_included_nonce'] ) && wp_verify_nonce( $_POST['nm_whats_included_nonce'], 'neonmagic_meta_whats_included' ) ) {
        update_post_meta( $post_id, '_nm_whats_included', sanitize_textarea_field( $_POST['nm_whats_included'] ?? '' ) );
    }

    if ( isset( $_POST['nm_requirements_nonce'] ) && wp_verify_nonce( $_POST['nm_requirements_nonce'], 'neonmagic_meta_requirements' ) ) {
        $types = array_map( 'sanitize_text_field', (array) ( $_POST['nm_req_type'] ?? [] ) );
        $texts = array_map( 'sanitize_text_field', (array) ( $_POST['nm_req_text'] ?? [] ) );
        $lines = [];
        foreach ( $types as $i => $type ) {
            $text = $texts[ $i ] ?? '';
            if ( $text ) {
                $lines[] = $type . '|' . $text;
            }
        }
        update_post_meta( $post_id, '_nm_requirements', implode( "\n", $lines ) );
    }

    if ( isset( $_POST['nm_changelog_nonce'] ) && wp_verify_nonce( $_POST['nm_changelog_nonce'], 'neonmagic_meta_changelog' ) ) {
        $versions = array_map( 'sanitize_text_field', (array) ( $_POST['nm_cl_version'] ?? [] ) );
        $dates    = array_map( 'sanitize_text_field', (array) ( $_POST['nm_cl_date']    ?? [] ) );
        $colors   = array_map( 'sanitize_text_field', (array) ( $_POST['nm_cl_color']   ?? [] ) );
        $changes  = (array) ( $_POST['nm_cl_changes'] ?? [] );

        $allowed_colors = [ 'cyan', 'yellow', 'pink', 'white' ];
        $entries = [];

        foreach ( $versions as $i => $version ) {
            if ( ! $version ) continue;
            $color_val = in_array( $colors[ $i ] ?? '', $allowed_colors, true ) ? $colors[ $i ] : 'cyan';
            $change_lines = array_filter( array_map( 'sanitize_text_field',
                explode( "\n", $changes[ $i ] ?? '' )
            ) );
            $entries[] = [
                'version' => $version,
                'date'    => $dates[ $i ]  ?? '',
                'color'   => $color_val,
                'changes' => array_values( $change_lines ),
            ];
        }

        update_post_meta( $post_id, '_nm_changelog', wp_json_encode( $entries ) );
    }
}
add_action( 'save_post', 'neonmagic_save_meta' );
