<div class="wrap">
    <h2>Settings</h2>

    <?php if( isset($_GET['settings-updated']) ) { ?>
        <div id="message" class="updated">
            <p><strong><?php _e('Settings saved.') ?></strong></p>
        </div>
    <?php } ?>

    <form method="post" action="options.php">
        <?php settings_fields( 'cb-settings' ); do_settings_sections( 'cb-settings' ); ?>
        <div id="tabs">
            <ul>
                <li><a href="#main">API</a></li>
                <li><a href="#pages">Pages</a></li>
                <li><a href="#styles">Styles</a></li>
            </ul>

            <table class="form-table" id="main">
                <tr valign="top">
                    <th scope="row"><label for="cb_cb_username">Email</label></th>
                    <td><input type="text" name="cb_cb_username" id="cb_cb_username" class="regular-text" value="<?php echo get_option('cb_cb_username'); ?>" />
                        <p class="description">Your email which you used for login in ClassByte</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="cb_cb_api">API Key</label></th>
                    <td><input type="text" autocomplete="off" name="cb_cb_api" id="cb_cb_api" class="regular-text" value="<?php echo get_option('cb_cb_api'); ?>" />
                    <p class="description">Your API key provided in ClassByte Admin</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="cb_cb_api_url">API URL</label></th>
                    <td><input type="text" autocomplete="off" name="cb_cb_api_url" id="cb_cb_api_url" class="regular-text" value="<?php echo get_option('cb_cb_api_url'); ?>" />
                    <p class="description">Classbyte API URL E.g: <i>http://example.com/api/</i><br><strong>Trailing slash "/" not required</strong></p>
                    </td>
                </tr>
            </table>

            <table class="form-table" id="pages">
                <?php $pages = get_option('cb_post_page_ids');
                if (!empty($pages) && is_array($pages)) {
                    echo '<tr valign="top"><td colspan="2"><strong>Note:</strong><p class="description">These are the pages automatically created on activating the plugin. When you deactivate the plugin, the pages will go into Trash automatically. When you delete the plugin, the pages will be deleted permanently</p></td></tr>';
                    foreach ($pages as $page) :
                ?>
                <tr valign="top">
                    <th scope="row"><label><?php echo get_the_title($page); ?></label></th>
                    <td><a href="<?php echo get_permalink($page); ?>" target="_blank"><?php echo get_permalink($page); ?></a></td>
                </tr>
                <?php endforeach; } else { ?>
                <tr valign="top">
                    <td>No activity.</td>
                </tr>
                <?php } ?>
            </table>

            <table class="form-table" id="styles">
                <tr>
                    <th valign="top"><label for="cb_accordion_tab">Accordion tab</label></th>
                    <td>
                        <div class="cb-colorpicker"><div></div></div>
                        <input type="hidden" id="cb_accordion_tab" class="colorpicker-val-swap" value="<?php echo get_option('cb_accordion_tab'); ?>" name="cb_accordion_tab">
                    </td>
                </tr>
                <tr>
                    <th valign="top"><label for="cb_circle_steps">Circle Steps</label></th>
                    <td>
                        <div class="cb-colorpicker"><div></div></div>
                        <input type="hidden" id="cb_circle_steps" class="colorpicker-val-swap" value="<?php echo get_option('cb_circle_steps'); ?>" name="cb_circle_steps">
                    </td>
                </tr>
                <tr>
                    <th valign="top"><label for="cb_circle_active_steps">Circle Active Steps</label></th>
                    <td>
                        <div class="cb-colorpicker"><div></div></div>
                        <input type="hidden" id="cb_circle_active_steps" class="colorpicker-val-swap" value="<?php echo get_option('cb_circle_active_steps'); ?>" name="cb_circle_active_steps">
                    </td>
                </tr>
                <tr>
                    <th valign="top"><label for="cb_circle_straight_line">Circle Straight Line</label></th>
                    <td>
                        <div class="cb-colorpicker"><div></div></div>
                        <input type="hidden" id="cb_circle_straight_line" class="colorpicker-val-swap" value="<?php echo get_option('cb_circle_straight_line'); ?>" name="cb_circle_straight_line">
                    </td>
                </tr>
                <tr>
                    <th valign="top"><label for="cb_button_color">Button</label></th>
                    <td>
                        <div class="cb-colorpicker"><div></div></div>
                        <input type="hidden" id="cb_button_color" class="colorpicker-val-swap" value="<?php echo get_option('cb_button_color'); ?>" name="cb_button_color">
                    </td>
                </tr>
                <tr>
                    <th valign="top"><label for="cb_button_hover_color">Button Hover</label></th>
                    <td>
                        <div class="cb-colorpicker"><div></div></div>
                        <input type="hidden" id="cb_button_hover_color" class="colorpicker-val-swap" value="<?php echo get_option('cb_button_hover_color'); ?>" name="cb_button_hover_color">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="cb_custom_css">Custom CSS</label></th>
                    <td>
                        <textarea name="cb_custom_css" cols="50" rows="15" id="cb_custom_css"><?php echo get_option('cb_custom_css'); ?></textarea>
                        <p class="description">Above styles will be applied to every posts/pages created by this plugin.</p>
                    </td>
                </tr>
            </table>
        </div>
        <?php submit_button(); ?>
    </form>
</div>
