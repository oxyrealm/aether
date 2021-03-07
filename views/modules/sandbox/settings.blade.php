<h2>Sandbox</h2>

<form action="options.php" method="post">
    {{ settings_fields( $module_id ) }}
    {{ do_settings_sections( $module_id ) }}

    <table>
        <tr valign="top">
            <th scope="row"><label for="{{ "{$module_id}_enabled" }}">{{ __( 'Enable Sandbox Mode', 'aether' ) }}</label></th>
            <td>
                <input type="checkbox" id="{{ "{$module_id}_enabled" }}" name="{{ "{$module_id}_enabled" }}" value="true" {{ checked( get_option( "{$module_id}_enabled" ), "true" ) }}>
            </td>
        </tr>
    </table>

    {{ submit_button( __('Save Settings', 'aether') ) }}
</form>

<h2>Sandbox Mode</h2>

<h3>Access magiclink</h3>
<div>
    <input type="text"  value="{{ add_query_arg( $module_id, $secret, site_url() ) }}" style="width: 50%;">
</div>
<span>re-enable the sandbox feature to reset magiclink</span>
