<?php

/*
Plugin Name: DVS API
Plugin URI: http://wordpress.org/plugins/dvs-api/
Description: This plugin for provide json api
Version: 1.1.0
Stable tag: 1.1.0
Author: Vladimir Drizheruk
Author URI: mailto:vladimir@drizheurk.com.ua
*/

class DvsAPI
{
    public function __construct()
    {
        add_filter('query_vars', array($this, 'add_query_vars'), 0);
        add_action('parse_request', array($this, 'sniff_requests'), 0);
        add_action('init', array($this, 'add_endpoint'), 0);
    }

    /** Add public query vars
     * @param array $vars List of current public query vars
     * @return array $vars
     */
    public function add_query_vars($vars)
    {
        $vars[] = '__api';
        $vars[] = 'g';
        $vars[] = 'gType';
        $vars[] = 'gTax';
        return $vars;
    }

    /** Add API Endpoint
     *  This is where the magic happens - brush up on your regex skillz
     * @return void
     */
    public function add_endpoint()
    {
        add_rewrite_rule('^api/(post)/(\w+)/tax/(\w+)\.json/?', 'index.php?__api=1&g=$matches[1]&gType=$matches[2]&gTax=$matches[3]', 'top');
        add_rewrite_rule('^api/(post|term)/(\w+)\.json/?', 'index.php?__api=1&g=$matches[1]&gType=$matches[2]', 'top');
        flush_rewrite_rules();
    }

    /**   Sniff Requests
     *    This is where we hijack all API requests
     *    If $_GET['__api'] is set, we kill WP and serve up pug bomb awesomeness
     * @return die if API request
     */
    public function sniff_requests()
    {
        global $wp;
        if (isset($wp->query_vars['__api'])) {
            $this->handle_request();
            exit;
        }
    }

    /** Handle Requests
     *    This is where we send off for an intense pug bomb package
     * @return void
     */
    protected function handle_request()
    {
        global $wp;
        $g = $wp->query_vars['g'];
        $gType = $wp->query_vars['gType'];
        $gTax = $wp->query_vars['gTax'] ? $wp->query_vars['gTax'] : '';

        switch ($g) {
            default:
                break;
            case 'post':
                $posts = $this->getPost($gType, $gTax);
                $this->send_response('200 OK', $posts);
                break;
            case 'term':
                $terms = $this->getTerm($gType);
                $this->send_response('200 OK', $terms);
                break;
        }
    }

    /**
     * @param string $gType
     * @param string $gTax
     * @return array
     */
    protected function getPost($gType = 'post', $gTax = '')
    {
        $posts = get_posts(['post_type' => $gType, 'post_status' => 'publish']);

        if (!empty($gTax)) {
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $terms = wp_get_post_terms($post->ID, $gTax, []);
                    $tax = 'term_' . $gTax;
                    $post->{$tax} = $terms;
                }
            }
        }

        return $posts;
    }

    /**
     * getTerm
     *
     * @param string $gType
     * @return array|WP_Error
     */
    protected function getTerm($gType = '')
    {
        $terms = get_terms($gType);
        if (empty($terms)) {
            return [];
        }

        if ('stores' == $gType) {
            if (function_exists('fl_get_store_image_url')) {
                foreach ($terms as $i => $term) {
                    $terms[$i]->store_image_url = fl_get_store_image_url($term->term_id, 'term_id', 150);
                }
            }
        }

        return $terms;
    }

    /** Response Handler
     *  This sends a JSON response to the browser
     * @param string $msg
     * @param string $data
     */
    protected function send_response($msg = '', $data = '')
    {
        $response['message'] = $msg;
        if ($data)
            $response['data'] = $data;
        header('content-type: application/json; charset=utf-8');
        echo json_encode($response) . "\n";
        exit;
    }
}

new DvsAPI();
