<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 21/05/16
 * Time: 06:36
 */

namespace php_components_essentials;

/**
 * Note to myself.. will this ever be used?
 *
 * Interface Http
 * @package php_components_essentials
 */

interface Http
{
    public function allowed_request_methods_to_string   (); // 
    public function add_request_method                  (string $method, int $value = 1); //
    public function enable_request_method               (string $method); //
    public function disable_request_method              (string $method); //
    public function remove_request_method               (string $method); //
    public function is_request_method_allowed           (string $method); //
    public function is_request_method_known             (string $method); //

    public function content_types_to_string             (); // All the given content type to a string.
    public function is_content_type_allowed             (); // Check if given content type is allowed.
    public function get_detailed_content_type           (string $trivial = 'txt'); // PDF => application/pdf
}