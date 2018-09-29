<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       facebook.com/moicsmarkez
 * @since      1.0.0
 *
 * @package    Kwe_Gio_Redirect
 * @subpackage Kwe_Gio_Redirect/admin/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<h1>Gio Redirect (Estados de Union Europea)</h1>
<p>Re dirige el trafico de clientes provenientes (por ip) de pasises de (Europa) la Union Europea.</p>
<p>Los usuarios administradores no seran redirigidos ante el filto de paises europeos.</p>

<div id="contener_gio_redirects">
    <form id="form_gio_redirect">
        <div class="caja_agregar_opciones">
            <span> 
                <select name="kwe_gio_redirect_page_start" id="kwe_gio_redirect_page_start" required="true" >
                    <option value="">Pagina</option>
                    <?php
                    $pages = get_pages();
                    foreach ($pages as $pagg) {
                        $option = '<option value="' . $pagg->ID . '">';
                        $option .= $pagg->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                    ?>
                </select>
                <span style="padding: 0px 20px;vertical-align: super;text-transform: uppercase;font-weight: 700;">Redirige hacia:</span>
                <select name="kwe_gio_redirect_page_end" id="kwe_gio_redirect_page_end" required="true" >
                    <option value="">Pagina</option>
                    <?php
                    $pages = get_pages();
                    foreach ($pages as $pagg) {
                        $option = '<option value="' . $pagg->ID . '">';
                        $option .= $pagg->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                    ?>
                </select>
            </span>
            <input style="outline: none !important;" type="button" value="Agregar" class="primary-button" />
        </div>
        <?php wp_nonce_field('asdfo349asdfn91234d', 'kwe_gio_redirect_key', true, true); ?>
    </form>
    <div class="tabla_paginas_redirecciones">
        <div class="divTable">
            <div class="divTableBody">
                <div class="divTableRow">
                    <div class="divTableHead">Nro. de redirecciones</div>
                    <div class="divTableHead">Pagina Inicio</div>
                    <div class="divTableHead">Pagina Destino</div>
                    <div class="divTableHead">Opciones</div>
                </div>
                <?php
                $redirecciones_guardadas = get_option('kwe_gio_redirect_readys', array());
                if (!$redirecciones_guardadas) {
                    echo '<div class="divTableRow empty_reg_gio_redirect_kwe"><div  style="padding: 15px;"><em style="color: #a5a5a5;">No hay registros ningún redireccion</em></div></div>';
                } else if ($redirecciones_guardadas) {
                    foreach ($redirecciones_guardadas as $redireccion_k => $redireccion_v) {
                        ?>
                        <div class="divTableRow" >
                            <div class="divTableCell"><?php echo $redireccion_v['redirecciones'] ?></div>
                            <div class="divTableCell"><a href="<?php echo get_page_link($redireccion_v['PagIniId']); ?>" target="_blank" ><?php echo $redireccion_v['PagIniTtl'] ?></a></div>
                            <div class="divTableCell"><a href="<?php echo get_page_link($redireccion_v['PagFniId']); ?>" target="_blank" ><?php echo $redireccion_v['PagFniTtl'] ?></a></div>
                            <div class="divTableCell"><input class="remove_redireccion" data-element-id="<?php echo $redireccion_v['indexId']; ?>"  type="button" value="Eliminar"/></div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>