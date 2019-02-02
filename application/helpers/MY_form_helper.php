<?php
/**
 * @package     daycarepro app
 * @copyright   2018 A&M Digital Technologies
 * @author      John Muchiri
 * @link        https://amdtllc.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
/**
 * Textarea field
 *
 * @param    mixed  $data
 * @param    string $value
 * @param    mixed  $extra
 *
 * @return    string
 */
function form_textarea($data = '', $value = '', $extra = '')
{
    $defaults = array(
        'name' => is_array($data) ? '' : $data
    );

    if(!is_array($data) OR !isset($data['value'])) {
        $val = $value;
    } else {
        $val = $data['value'];
        unset($data['value']); // textareas don't use the value attribute
    }

    return '<textarea '._parse_form_attributes($data, $defaults)._attributes_to_string($extra).'>'
        .html_escape($val)
        ."</textarea>\n";
}

/**
 * Email Input Field
 *
 * @param    mixed
 * @param    string
 * @param    mixed
 *
 * @return    string
 */
function form_email($data = '', $value = '', $extra = '')
{
    $defaults = array(
        'type' => 'email',
        'name' => is_array($data) ? '' : $data,
        'value' => $value
    );

    return '<input '._parse_form_attributes($data, $defaults)._attributes_to_string($extra)." />\n";
}

/**
 * Date Input Field
 *
 * @param    mixed
 * @param    string
 * @param    mixed
 *
 * @return    string
 */
function form_date($data = '', $value = '', $extra = '')
{
    $defaults = array(
        'type' => 'date',
        'name' => is_array($data) ? '' : $data,
        'value' => $value
    );

    return '<input '._parse_form_attributes($data, $defaults)._attributes_to_string($extra)." />\n";
}

/**
 * Time Input Field
 *
 * @param    mixed
 * @param    string
 * @param    mixed
 *
 * @return    string
 */
function form_time($data = '', $value = '', $extra = '')
{
    $defaults = array(
        'type' => 'time',
        'name' => is_array($data) ? '' : $data,
        'value' => $value
    );

    return '<input '._parse_form_attributes($data, $defaults)._attributes_to_string($extra)." />\n";
}

/**
 * Form Value
 *
 * Grabs a value from the POST array for the specified field so you can
 * re-populate an input field or textarea. If Form Validation
 * is active it retrieves the info from the validation class
 *
 * @param    string $field       Field name
 * @param    string $default     Default value
 * @param    bool   $html_escape Whether to escape HTML special characters or not
 *
 * @return    string
 */
function set_value($field, $default = '', $html_escape = TRUE)
{
    $CI =& get_instance();

    $value = (isset($CI->form_validation) && is_object($CI->form_validation) && $CI->form_validation->has_rule($field))
        ? $CI->form_validation->set_value($field, $default)
        : $CI->input->post($field, FALSE);

    //check session for that value

    $sessionField = $CI->session->flashdata($field);

    if(isset($sessionField) && $default == '') {
        $value = $sessionField;
    }

    isset($value) OR $value = $default;
    return ($html_escape) ? html_escape($value) : $value;
}

/**
 * Form Declaration
 *
 * Creates the opening portion of the form.
 *
 * @param    string    the URI segments of the form destination
 * @param    array     a key/value pair of attributes
 * @param    array     a key/value pair hidden data
 *
 * @return    string
 */
function form_open($action = '', $attributes = array(), $hidden = array())
{

    if(!is('admin')) {
        if(is_array($attributes)
            && !empty($attributes)
            && session('company_demo_mode') == 1
            && array_key_exists('demo', $attributes))
            return ''; //disable form in demo
    }
    $CI =& get_instance();

    // If no action is provided then set to the current url
    if(!$action) {
        $action = $CI->config->site_url($CI->uri->uri_string());
    } // If an action is not a full URL then turn it into one
    elseif(strpos($action, '://') === FALSE) {
        $action = $CI->config->site_url($action);
    }

    $attributes = _attributes_to_string($attributes);

    if(stripos($attributes, 'method=') === FALSE) {
        $attributes .= ' method="post"';
    }

    if(stripos($attributes, 'accept-charset=') === FALSE) {
        $attributes .= ' accept-charset="'.strtolower(config_item('charset')).'"';
    }

    $form = '<form action="'.$action.'"'.$attributes.">\n";

    if(is_array($hidden)) {
        foreach ($hidden as $name => $value) {
            $form .= '<input type="hidden" name="'.$name.'" value="'.html_escape($value).'" />'."\n";
        }
    }

    // Add CSRF field if enabled, but leave it out for GET requests and requests to external websites
    if($CI->config->item('csrf_protection') === TRUE && strpos($action, $CI->config->base_url()) !== FALSE && !stripos($form, 'method="get"')) {
        // Prepend/append random-length "white noise" around the CSRF
        // token input, as a form of protection against BREACH attacks
        if(FALSE !== ($noise = $CI->security->get_random_bytes(1))) {
            list(, $noise) = unpack('c', $noise);
        } else {
            $noise = mt_rand(-128, 127);
        }

        // Prepend if $noise has a negative value, append if positive, do nothing for zero
        $prepend = $append = '';
        if($noise < 0) {
            $prepend = str_repeat(" ", abs($noise));
        } elseif($noise > 0) {
            $append = str_repeat(" ", $noise);
        }

        $form .= sprintf(
            '%s<input type="hidden" name="%s" value="%s" />%s%s',
            $prepend,
            $CI->security->get_csrf_token_name(),
            $CI->security->get_csrf_hash(),
            $append,
            "\n"
        );
    }

    return $form;
}

/**
 * Form Close Tag
 *
 * @param    string
 *
 * @return    string
 */
function form_close($extra = '')
{
    if($extra == "demo") {
        $extra = '';
        if(session('company_demo_mode') == 1 &&  !is('admin'))
            return ''; //disable form in demo mode
    }

    return '</form>'.$extra;
}

?>