<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       facebook.com/moicsmarkez
 * @since      1.0.0
 *
 * @package    Kwe_Gio_Redirect
 * @subpackage Kwe_Gio_Redirect/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Kwe_Gio_Redirect
 * @subpackage Kwe_Gio_Redirect/public
 * @author     MoicsMarkez <moicsmarkez@yahoo.com>
 */
class Kwe_Gio_Redirect_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *  
     * @since    1.0.0
     */
    public function redireccion_UE() { 
        if (!is_admin()) {
            $ch = curl_init('http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $result = unserialize($result);
            $countryEu = $result['geoplugin_inEU'];
            $redirecciones_registradas = get_option('kwe_gio_redirect_readys', array());

            if ($countryEu > 0 && ($redirecciones_registradas || count($redirecciones_registradas)) ) {
                foreach ($redirecciones_registradas as $redirect_k => $redirect_v) {
                    if (get_the_ID() == $redirect_v['PagIniId']) {
                        if (wp_redirect(get_page_link($redirect_v['PagFniId']), 301))
                            $redirecciones_registradas[$redirect_k]['redirecciones'] = $redirecciones_registradas[$redirect_k]['redirecciones'] + 1;
                        update_option('kwe_gio_redirect_readys', $redirecciones_registradas, false);
                        exit();
                        exit();
                    }
                }
            }
        }
    }

}
