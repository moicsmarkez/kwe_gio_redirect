(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(function () {
        if ($('#kwe_gio_redirect_page_start').length) {
            var sleector_start = new Selectr('#kwe_gio_redirect_page_start', {
                placeholder: 'Pagina ',
                sortSelected: 'text'
            });
            
            
        }

        if ($('#kwe_gio_redirect_page_end').length) {
            var sleector_end = new Selectr('#kwe_gio_redirect_page_end', {
                placeholder: 'Pagina',
                sortSelected: 'text'
            });
        }
        
        if($('#kwe_gio_redirect_page_end').length && $('#kwe_gio_redirect_page_start').length){
            sleector_start.on('selectr.select', function (option) {
                
                if(option.value == sleector_end.getValue() && option.value != ''){
                    alert('Haz Seleccionado el mismo valor de la pagina destino');
                    sleector_start.setValue('');
                    return;
                }
            });
            
            sleector_end.on('selectr.select', function (option) {
                if(option.value == sleector_start.getValue() && option.value != ''){
                    alert('Haz Seleccionado el mismo valor de la pagina requerida');
                    sleector_end.setValue('');
                    return;
                }
            });
        }

        $('.caja_agregar_opciones').on('click', 'input[type="button"]', function () {
            let loading_overlay = '<div class="loading_redirect_overlay"><div class="lds-hourglass"></div></div>';
            let formualrio = $(this).closest("form");
            let padre_box = $(this).closest("div.caja_agregar_opciones");
            let datos = $(padre_box).find(':input').serializeArray();
            datos[datos.length] = {name: 'kwe_gio_redirect_key', value: $('input[name="kwe_gio_redirect_key"]').val()};
            console.log(datos);
            //HAY QUE VALIDAR ANTES 
            $.ajax({
                url: 'admin-ajax.php?action=agregar_redireccion',
                method: 'post',
                data: datos,
                beforeSend: function () {
                    $(padre_box).prepend(loading_overlay);
                },
                success: function (response) {
                    if (!response.success) {
                        alert(response.data + ', Recarga la pagina Por favor!');
                        location.reload();
                        return;
                    }

                    //ELIMINAR FILA DE VACIO EN TABLA
                    if ($('.empty_reg_gio_redirect_kwe').length) {
                        $('.empty_reg_gio_redirect_kwe').remove();
                    }

                    sleector_start.setValue('');
                    sleector_end.setValue('');

                    $(response.data).appendTo('.tabla_paginas_redirecciones > .divTable > .divTableBody').show(1000);
                    document.getElementById('form_gio_redirect').reset();
                    $(padre_box).find('div.loading_redirect_overlay').remove();
                },
            });
        });

        $('div#contener_gio_redirects').on('click', 'input.remove_redireccion', function () {
            let evnt_fila_padre = $(this).closest('.divTableRow');
            let loading_overlay = '<div class="table_loading_overlay"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>';
            let evnt_index_id = $(this).data('element-id');
            if (confirm('Estas seguro de eliminar?')) {
                $.ajax({
                    url: 'admin-ajax.php?action=eliminar_redireccion',
                    method: 'post',
                    data: {id_gio_redirect: evnt_index_id},
                    beforeSend: function () {
                        $('div.tabla_paginas_redirecciones').prepend(loading_overlay);
                    },
                    success: function (response) {
                        if (response.success) {
                            //$(evnt_padre).remove();
                            alert('Redireccion eliminada correctamente');
                            $('div.tabla_paginas_redirecciones> div.table_loading_overlay').remove();
                            $(evnt_fila_padre).remove();
                            if ($('div.divTableBody .divTableRow').length < 2) {
                                $('<div class="divTableRow empty_reg_gio_redirect_kwe"><div  style="padding: 15px;"><em style="color: #a5a5a5;">No hay registros de redirecciones</em></div></div>').appendTo('.tabla_paginas_redirecciones > .divTable > .divTableBody').show(1000);
                            }
                        }
                    }
                });
            }
        });


    });


})(jQuery);
