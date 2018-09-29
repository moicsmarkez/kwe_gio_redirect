<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       facebook.com/moicsmarkez
 * @since      1.0.0
 *
 * @package    Kwe_Gio_Redirect
 * @subpackage Kwe_Gio_Redirect/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kwe_Gio_Redirect
 * @subpackage Kwe_Gio_Redirect/admin
 * @author     MoicsMarkez <moicsmarkez@yahoo.com>
 */
class Kwe_Gio_Redirect_Admin {

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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Kwe_Gio_Redirect_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Kwe_Gio_Redirect_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/kwe-gio-redirect-admin.css', array(), $this->version, 'all');
        wp_enqueue_style('selectr', plugin_dir_url(__FILE__) . 'css/selectr.min.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Kwe_Gio_Redirect_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Kwe_Gio_Redirect_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/kwe-gio-redirect-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script('selectr', plugin_dir_url(__FILE__) . 'js/selectr.min.js', array(), $this->version, true);
    }

    public function menu_admin() {
        add_menu_page('GioRedirect', 'GioRedirect', 'manage_options', 'kwe_geographic_redirect', array($this, 'gioRedirect_setting_page'), 'dashicons-location-alt', 10);
        //add_submenu_page('active_campaign_tracking_events_settings', 'Active Campaign Tracking Events', 'Configuración', 'manage_options', 'active_campaign_tracking_events', array($this, 'pagina_opciones'));
    }

    public function gioRedirect_setting_page() {
        include_once( plugin_dir_path(__FILE__) . 'partials/kwe-gio-redirect-admin-display.php' );
    }

    public function agregar_redireccion() {
        if (!isset($_POST['kwe_gio_redirect_key']) || !wp_verify_nonce($_POST['kwe_gio_redirect_key'], 'asdfo349asdfn91234d')) {
            wp_send_json_error('Procedimiento invalido, por favor refresca la pagina! -1');
            wp_die();
        }
        $rediccion_agr = array();
        
        if (!isset($_POST['kwe_gio_redirect_page_start']) && !($_POST['kwe_gio_redirect_page_start'] != '' || !empty($_POST['kwe_gio_redirect_page_start']))) {
            wp_send_json_error('Procedimiento invalido, por favor refresca la pagina! -3');
            wp_die();
        }
        if (!isset($_POST['kwe_gio_redirect_page_end']) && !($_POST['kwe_gio_redirect_page_end'] != '' || !empty($_POST['kwe_gio_redirect_page_end']))) {
            wp_send_json_error('Procedimiento invalido, por favor refresca la pagina! -3');
            wp_die();
        }
        
        if( $_POST['kwe_gio_redirect_page_end'] == $_POST['kwe_gio_redirect_page_start'] ){
            wp_send_json_error('Procedimiento invalido, por favor refresca la pagina! -4 (Valores iguales!)');
            wp_die(); 
        }
        
        $redirecciones_registradas = get_option('kwe_gio_redirect_readys', array());
        $cmpl = false;
        
        if ($redirecciones_registradas && count($redirecciones_registradas)) {
             foreach ($redirecciones_registradas as $redireccion_k => $redireccion_v) {
                 if( $redireccion_v['PagIniId'] == $_POST['kwe_gio_redirect_page_start'] && $redireccion_v['PagFniId'] == $_POST['kwe_gio_redirect_page_end']  ) { 
                    wp_send_json_error('Disculpa la demora, detectamos que ya hay una combinación de estas paginas en las redirecciones. Esto puede generar bucles indeseables. No pudimos guardar el registro');
                    wp_die();
                 }else if( $redireccion_v['PagIniId'] == $_POST['kwe_gio_redirect_page_end'] && $redireccion_v['PagFniId'] == $_POST['kwe_gio_redirect_page_start']  ) { 
                    wp_send_json_error('Disculpa la demora, detectamos que ya hay una combinación de estas paginas en las redirecciones. Esto puede generar bucles indeseables. No pudimos guardar el registro');
                    wp_die();
                 }
             }
        }
        
        
        if ($redirecciones_registradas && count($redirecciones_registradas)) {
            $rediccion_agr = [
                'indexId' => 'kwe_gio_redirect_' . rand(0, 1000) . '_' . $_POST['kwe_gio_redirect_page_start'],
                'PagIniId' => $_POST['kwe_gio_redirect_page_start'],
                'PagIniTtl' => get_the_title(intval($_POST['kwe_gio_redirect_page_start'])),
                'PagFniId' => $_POST['kwe_gio_redirect_page_end'],
                'PagFniTtl' => get_the_title(intval($_POST['kwe_gio_redirect_page_end'])),
                'redirecciones' => 0,
            ];
            array_push($redirecciones_registradas, $rediccion_agr);
            update_option('kwe_gio_redirect_readys', $redirecciones_registradas, false);
            $cmpl = true;
        } else {
            $rediccion_agr []= [
                'indexId' => 'kwe_gio_redirect_' . rand(0, 1000) . '_' . $_POST['kwe_gio_redirect_page_start'],
                'PagIniId' => $_POST['kwe_gio_redirect_page_start'],
                'PagIniTtl' => get_the_title(intval($_POST['kwe_gio_redirect_page_start'])),
                'PagFniId' => $_POST['kwe_gio_redirect_page_end'],
                'PagFniTtl' => get_the_title(intval($_POST['kwe_gio_redirect_page_end'])),
                'redirecciones' => 0
            ];
            update_option('kwe_gio_redirect_readys', $rediccion_agr, false);
            $rediccion_agr = $rediccion_agr[0];
            $cmpl = true;
        }

        if ($cmpl) {
            ob_start();
            ?>
                <div class="divTableRow" style="display: none;">
                    <div class="divTableCell"><?php echo $rediccion_agr['redirecciones'] ?></div>
                    <div class="divTableCell"><a href="<?php echo get_page_link($rediccion_agr['PagIniId']); ?>" target="_blank" ><?php echo $rediccion_agr['PagIniTtl'] ?></a></div>
                    <div class="divTableCell"><a href="<?php echo get_page_link($rediccion_agr['PagFniId']); ?>" target="_blank" ><?php echo $rediccion_agr['PagFniTtl'] ?></a></div>
                    <div class="divTableCell"><input class="remove_redireccion" data-element-id="<?php echo $rediccion_agr['indexId'];?>" type="button" value="Eliminar"/></div>
                </div>
            <?php
            wp_send_json_success(ob_get_clean());
            wp_die();
        }

        wp_send_json_error('Procedimiento invalido, por favor refresca la pagina! -2');
        wp_die();
    }
    
    public function remove_kwe_gio_redirect(){
        $redirecciones_registradas = get_option('kwe_gio_redirect_readys', array());

        if (isset($_POST['id_gio_redirect']) && $_POST['id_gio_redirect'] != '') {
            foreach ($redirecciones_registradas as $opcion_key => $opcion_valor) {
                if ($opcion_valor['indexId'] === $_POST['id_gio_redirect']) {
                    unset($redirecciones_registradas[$opcion_key]);
                    update_option('kwe_gio_redirect_readys', $redirecciones_registradas, false);
                    wp_send_json_success('eliminado_exito');
                    wp_die();
                }
            }
        }

        wp_send_json_error('Procedimiento invalido, por favor refresca la pagina! -2');
        wp_die();
    }

}
