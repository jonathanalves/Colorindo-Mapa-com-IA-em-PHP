<html lang="pt-BR" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
    
    <head>	
        <title>Mapa do Brasil - Divisão Por Estados</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <!-- STYLE -->
        <link rel="stylesheet" href="ui/css/style.css" type="text/css" media="screen" />

        <script src="ui/scripts/jquery/jquery-last.min.js"></script>
        <script src="ui/scripts/blockUI/jquery.blockUI.js"></script>
        <script src="ui/scripts/map/jquery.maphilight.js"></script>        
        
    </head>

    <body>

        <div id="content">
            <div class="inner">

                <h1 id="header">Mapa do Brasil - Divisão Por Estados</h1>

                <div class="one column_last widget">

                    <h2>Informe a regra escolhida</h2>
                    
                    <br />
                    
                    <a href="#" data-regra="1" class="button button-primary ">Regra 1</a>
                    
                    <a href="#" data-regra="2" class="button button-primary ">Regra 2</a>

                </div>

                <div class="ajaxmsg one column_last widget" style="display:none"></div>

            </div>

        </div> <!--// End inner -->
        </div> <!--// End content -->

        <!-- JAVASCRIPTS  -->
        <script>

            jQuery.noConflict();

            // Use jQuery via jQuery(...)
            jQuery(document).ready(function($){
		                
                $('.button').click( function(e){
						
                    e.preventDefault();
						
                    $.blockUI({
                        message: jQuery('#blockUI'),
                        fadeIn: 500
                    });
                    
                    regra = $(this).attr('data-regra');
										
                    $.ajax({
                        url         : 'acao.php',
                        async       : true,
                        cache       : false,
                        type        : 'post',
                        data        : {regra : regra},
                        dataType    : 'html',
                        timeout     : 20000,
                        beforeSend : function(){
                            $('.ajaxmsg').fadeOut('fast').html('');
                        },
                        success : function(retorno){						
                            $('.ajaxmsg').html(retorno).fadeIn('fast');
                            
                            $('.map').maphilight();

                            function selectAll(){
                                $.each($('#mapabrasil area'), function(){
                                    var data = $(this).mouseout().data('maphilight') || {};
                                    data.alwaysOn = true;
                                    $(this).data('maphilight', data).trigger('alwaysOn.maphilight');
                                });
                            }

                            selectAll();
                            
                        },
                        error : function(){
                            $('.ajaxmsg').html('<p class="simple-error">Erro ao efetuar requisição</p>').fadeIn('fast');
                        }

                    });
						
                });
					
            });
						
            //**************************************************************************************************
            //  BLOCKUI

            // unblock when ajax activity stops
            jQuery(document).ajaxStop( jQuery.unblockUI );

        </script>
        <!-- FIM JAVASCRIPTS  -->

        <div id="blockUI" style="display:none; padding: 10px;">
            <h1 style="border: medium none; font-size: 20px;"><img src="ui/images/loadder/busy.gif" /> Estamos efetuando sua requisição...</h1>
        </div>

    </body>
</html>