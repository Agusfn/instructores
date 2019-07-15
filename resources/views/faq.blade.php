@extends('layouts.main')

@section('title', 'Preguntas frecuentes')

@section('content')
<style type="text/css">
    .mm-slideout {
        border-bottom: 1px solid #ededed!important;
    background-color: #fff !important;
    
    color: black !important;
}
   .mm-slideout p{
    
    color: black !important;
}
 .mm-slideout   ul > li span > a {
    color: #444;   
}

.mm-slideout   ul > li span > a:hover {
    color: #fc5b62;   
}

.hamburger-inner, .hamburger-inner::after, .hamburger-inner::before {
    width: 30px;
    height: 4px;
    background-color: #333;
    border-radius: 0;
    position: absolute;
    transition-property: transform;
    transition-duration: .15s;
    transition-timing-function: ease;
}

#logo p{ color: black!important }
    header.header.sticky #logo p{
     color: black!important;
}
}

</style>
        <br/>
        <br/>
        <div class="container margin_60_35"></div>
        <div class="container margin_60_35">
            <div class="row">
                <aside class="col-lg-3" id="sidebar">
                        <div class="box_style_cat" id="faq_box">
                            <ul id="cat_nav">
                                <li><a href="#payment" class="active"><i class="icon_document_alt"></i>Pagos</a></li>
                                <li><a href="#tips"><i class="icon_document_alt"></i>Reservas</a></li>
                                <li><a href="#reccomendations"><i class="icon_document_alt"></i>Los instructores</a></li>
                                <li><a href="#terms"><i class="icon_document_alt"></i>Términos y condiciones</a></li>
                                <li><a href="#private"><i class="icon_document_alt"></i>Privacidad</a></li>
                                
                            </ul>
                        </div>
                        <!--/sticky -->
                </aside>
                <!--/aside -->
                
                <div class="col-lg-9" id="faq">
                    <h4 class="nomargin_top">Pagos</h4>
                    <div role="tablist" class="add_bottom_45 accordion_2" id="payment">
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseOne_payment" aria-expanded="true"><i class="indicator ti-plus"></i>Formas de pago</a>
                                </h5>
                            </div>

                            <div id="collapseOne_payment" class="collapse" role="tabpanel" data-parent="#payment">
                                <div class="card-body">
                                    <p>Trabajamos con <strong>Mercado Pago</strong>, lo que nos permite aceptar las siguientes formas de pago:</p>
                                    <p> - Tarjetas de crédito en hasta 12 cuotas.</p>
                                    <p> - Tarjetas de débito.</p>
                                    <p> - En efectivo en puntos de pago.</strong></p>
                                    <p> - Con saldo pre-cargado en tu cuenta de Mercado Pago.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseTwo_payment" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Montos mínimos y máximos
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseTwo_payment" class="collapse" role="tabpanel" data-parent="#payment">
                                <div class="card-body">
                                    <h6>Tarjeta de crédito</h6>
                                    <p> Mastercard, American Express, Naranja, Argencard, Cabal, Cencosud, Nativa Mastercard, Tarjeta Shopping.</p>
                                    <p> - Monto mínimo: $ 1,00</p>
                                    <p> - Monto máximo: $ 250.000,00</p>
                                    <p> Visa</p>
                                    <p> - Monto mínimo: $ 2,00</p>
                                    <p> - Monto máximo: $ 250.000,00</p>

                                    <h6>Rapipago, Cobro Express y Red Link</h6>                             
                                    <p> - Monto mínimo: $ 1,00</p>
                                    <p> - Monto máximo: $ 60.000,00</p>

                                    <h6>Provincia Pagos</h6>    
                                    <p> - Monto mínimo: $ 2,00</p>
                                    <p> - Monto máximo: $ 29.999,00</p>

                                    <h6>Pago Fácil</h6>
                                    <p> - Monto mínimo: $ 10,00</p>
                                    <p> - Monto máximo: $ 60.000,00</p>

                                    <h6>Carga Virtual</h6>
                                    <p> - Monto mínimo: $ 2,00</p>
                                    <p> - Monto máximo: $ 5.000,00</p>  

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseThree_payment" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Comisiones por servicio.
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseThree_payment" class="collapse" role="tabpanel" data-parent="#payment">
                                <div class="card-body">
                                    
                                    <p>Aplica un 5% del total de la reserva efectuada en concepto de tarifa del servicio y mantenimiento de la plataforma.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                    </div>
                    <!-- /accordion payment -->
                    
                    <h4 class="nomargin_top">Reservas</h4>
                    <div role="tablist" class="add_bottom_45 accordion_2" id="tips">
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseOne_tips" aria-expanded="true"><i class="indicator ti-plus"></i>Cómo reservar</a>
                                </h5>
                            </div>

                            <div id="collapseOne_tips" class="collapse" role="tabpanel" data-parent="#tips">
                                <div class="card-body">
                                    <p>Cuando realizas una búsqueda, ingresas las fechas en que quieres tomar las clases y la cantidad de personas que serán, para calcular la tarifa. El sistema buscará a los instructores disponibles y mejor calificados. La tarifa de cada instructor puede varíar en razón de la demanda y la oferta.</p>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseTwo_tips" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Solicitud de la reserva</a>
                                </h5>
                            </div>
                            <div id="collapseTwo_tips" class="collapse" role="tabpanel" data-parent="#tips">
                                <div class="card-body">
                                    <p>Cuando hayas encontrado al instructor que más se adapte a tu perfil, deberás hacerle una solicitud de reserva. Esta etapa sirve para coordinar entre las partes y confirmar que el instructor es el adecuado para tus necesidades.
                                    </p>
                                    <p>Los instructores tienen hasta 24hs para confirmar tu reserva, nosotros te notificaremos.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseThree_tips" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Cancelación de la reserva</a>
                                </h5>
                            </div>
                            <div id="collapseThree_tips" class="collapse" role="tabpanel" data-parent="#tips">
                                <div class="card-body">
                                    <p>Puedes cancelar la reserva en cualquier momento si aún no está confirmada. Si la reserva se confirmó e hiciste el pago sólo se reembolsará la mitad del dinero. Ten en cuenta que cancelar una clase ya reservada implica una pérdida para el instructor sobre otras potenciales reservas y además conlleva gastos administrativos.
                                    </p>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                    </div>
                    <!-- /accordion suggestions -->
                    
                    <h4 class="nomargin_top">Los instructores</h4>
                    <div role="tablist" class="add_bottom_45 accordion_2" id="reccomendations">
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseOne_reccomendations" aria-expanded="true"><i class="indicator ti-plus"></i>Certificación profesional</a>
                                </h5>
                            </div>

                            <div id="collapseOne_reccomendations" class="collapse" role="tabpanel" data-parent="#reccomendations">
                                <div class="card-body">
                                    <p>Todos los instructores de snowboard y ski anunciados en la plataforma cuentan con una certificación que los habilita como profesionales en la materia. Puedes encontrarlos por niveles, de 3 a 5. Al ser independientes las tarifas las proponen ellos. 
                                   </p>                                   
                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseTwo_reccomendations" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sistema de reputación</a>
                                </h5>
                            </div>
                            <div id="collapseTwo_reccomendations" class="collapse" role="tabpanel" data-parent="#reccomendations">
                                <div class="card-body">
                                    <p>Es nuestra intención crear un sistema de confianza en el cual, una vez que se haya llevado a cabo la clase, se te solicitará brindar una puntuacíon por estrellas y un comentario si lo deseas para que otras personas cuenten con tu experiencia para contratar con mayor tranquilidad. 
                                    </p>                       
                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                    
                    </div>
                    <!-- /accordion Reccomendations -->
                    
                    <h4 class="nomargin_top">Términos y condiciones</h4>
                    <div role="tablist" class="add_bottom_45 accordion_2" id="terms">
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseOne_terms" aria-expanded="true"><i class="indicator ti-plus"></i>Información general</a>
                                </h5>
                            </div>

                            <div id="collapseOne_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Este sitio web es operado por Instructores. En todo el sitio, los términos “nosotros”, “nos” y “nuestro” se refieren a Instructores. Instructores ofrece este sitio web, incluyendo toda la información, herramientas y servicios disponibles para tí en este sitio. El usuario está condicionado a la aceptación de todos los términos, condiciones, políticas y notificaciones aquí establecidos.
                                    </p>

                                    <p>Al visitar nuestro sitio y/o realizar una reserva, participas en nuestro “Servicio” y aceptas los siguientes términos y condiciones, incluídos todos los términos y condiciones adicionales y las políticas a las que se hace referencia en el presente documento y/o disponible a través de hipervínculos. Estas Condiciones de Servicio se aplican a todos los usuarios del sitio, incluyendo sin limitación a usuarios que sean navegadores, proveedores, clientes, comerciantes, y/o colaboradores de contenido.
                                    </p>

                                    <p>Por favor, lee estos Términos de Servicio cuidadosamente antes de acceder o utilizar nuestro sitio web. Al acceder o utilizar cualquier parte del sitio, estás aceptando los Términos de Servicio. Si no estás de acuerdo con todos los Términos y Condiciones de Servicio, entonces no deberías acceder a la página web o usar cualquiera de los servicios. Si los Términos de Servicio son considerados una oferta, la aceptación está expresamente limitada a estos Términos de Servicio.
                                    </p>

                                    <p>Cualquier función nueva o herramienta que se añadan a la tienda actual, también estarán sujetas a los Términos de Servicio. Puedes revisar la versión actualizada de los Términos de Servicio, en cualquier momento en esta página. Nos reservamos el derecho de actualizar, cambiar o reemplazar cualquier parte de los Términos de Servicio mediante la publicación de actualizaciones y/o cambios en nuestro sitio web. Es tu responsabilidad chequear esta página periódicamente para verificar cambios. Tu uso continuo o el acceso al sitio web después de la publicación de cualquier cambio constituye la aceptación de dichos cambios.
                                    </p>

                                    <p>MercadoPago nos proporciona la pasarela de pago en línea, que nos permite venderte nuestros productos y servicios</p>


                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseTwo_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 1 – Términos del servicio
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseTwo_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Al utilizar este sitio, declaras que tienes al menos la mayoría de edad en tu estado de residencia, o que tienes la mayoría de edad en tu estado de residencia y que nos has dado tu consentimiento para permitir que cualquiera de tus dependientes menores de edad utilice este sitio.
                                    </p>

                                    <p>No puedes usar nuestros productos con ningún propósito ilegal o no autorizado. Tampoco puedes, en el uso del Servicio, violar cualquier ley, ordenanza, decreto, etc., en tu jurisdicción (incluyendo pero no limitado a las leyes de derecho de autor).
                                    </p>

                                    <p>No debes transmitir gusanos, virus o cualquier código de naturaleza destructiva.</p>

                                    <p>El incumplimiento o violación de cualquiera de estos Términos darán lugar al cierre inmediato de tu cuenta y se te negará la posibilidad de operar en esta web.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseThree_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 2 – Condiciones generales
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseThree_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Nos reservamos el derecho de rechazar la prestación de servicio a cualquier persona, por cualquier motivo y en cualquier momento.
                                    </p>

                                    <p>Entiendes que tu contenido (sin incluir la información de tus tarjetas de crédito), puede ser transferida sin encriptar e involucrar (a) transmisiones a través de varias redes; y (b) cambios para ajustarse o adaptarse a los requisitos técnicos de conexión de redes o dispositivos. La información de tarjetas de crédito está siempre encriptada durante la transferencia a través de las redes.
                                    </p>

                                    <p>Estás de acuerdo con no reproducir, duplicar, copiar, vender, revender o explotar cualquier parte del Servicio, usp del Servicio, o acceso al Servicio o cualquier contacto en el sitio web a través del cual se presta el servicio, sin el expreso permiso por escrito de nuestra parte.
                                    </p>

                                    <p>Los títulos utilizados en este acuerdo se incluyen sólo por conveniencia y no limita o afecta a estos Términos.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseFour_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 3- Exactitud, exhaustividad y actualidad de la información
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseFour_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>No nos hacemos responsables si la información disponible en este sitio no es exacta, completa o actual. El material en este sitio es provisto sólo para información general y no debe confiarse en ella o utilizarse como la única base para la toma de decisiones sin consultar primeramente, información más precisa, completa u oportuna. Cualquier dependencia del material de este sitio es bajo su propio riesgo.
                                    </p>

                                    <p>Este sitio puede contener cierta información histórica. La información histórica, no es necesariamente actual y es provista únicamente para tu referencia. Nos reservamos el derecho de modificar los contenidos de este sitio en cualquier momento, pero no tenemos obligación de actualizar cualquier información en nuestro sitio. Aceptas que es tu responsabilidad monitorear los cambios en nuestro sitio.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseFive_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 4 – Modificaciones del servicio y precios
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseFive_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Los precios exhibidos a través de nuestra plataforma están sujetos a cambio sin previo aviso.
                                    </p>

                                    <p>Nos reservamos el derecho de modificar o discontinuar el Servicio (o cualquier parte del contenido) en cualquier momento sin previo aviso.
                                    </p>

                                    <p>No seremos responsables ante tí o alguna tercera parte por cualquier modificación, cambio de precio, suspensión o discontinuidad del Servicio.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                            <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseSix_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 5 – Productos y servicios (Si aplicable)
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseSix_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Ciertos productos o servicios pueden estar exhibidos exclusivamente en línea a través del sitio web. Estos productos o servicios pueden tener cantidades limitadas y estar sujetas a devolución o cambio de acuerdo a nuestra política de devolución solamente.
                                    </p>

                                    <p>Hemos hecho el esfuerzo de mostrar los colores y las imágenes de nuestros productos, en la tienda, con la mayor precisión de colores posible. No podemos garantizar que el monitor de tu computadora muestre los colores de manera exacta.
                                    </p>

                                    <p>Nos reservamos el derecho, pero no estamos obligados, de limitar las potenciales reservas o servicios a cualquier persona, región geográfica o jurisdicción. Podemos ejercer este derecho de acuerdo a cada caso en particular. Nos reservamos el derecho de limitar las cantidades de los productos o servicios que exhibimos. Todas las descripciones de las ofertas de productos o precios de las ofertas de productos están sujetos a cambios en cualquier momento sin previo aviso, a nuestra sola discreción. Nos reservamos el derecho de discontinuar cualquier oferta en cualquier momento. Cualquier oferta de producto o servicio hecho en este sitio es nulo si el mismo se encuentra prohibido por el ordenamiento jurídico vigente.
                                    </p>

                                    <p>No garantizamos que la calidad de los productos, servicios, información u otro material comprado u obtenido por tí cumpla con tus expectativas, o que cualquier error en el Servicio será corregido.-.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                            <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseSeven_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 6 – exactitud de facturación e información de cuenta
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseSeven_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Nos reservamos el derecho de rechazar cualquier pedido que realice a través del sitio. Podemos, a nuestra discreción, limitar o cancelar las cantidades compradas por persona o por grupo de personas. Estas restricciones pueden incluir pedidos realizados por o bajo la misma cuenta de cliente, la misma tarjeta de crédito, y/o pedidos que utilizan la misma facturación y/o dirección de envío.
                                    </p>

                                    <p>En el caso de que hagamos un cambio o cancelemos una orden, podemos intentar notificarte poniéndonos en contacto vía correo electrónico y/o dirección de facturación / número de teléfono proporcionado en el momento que se hizo el pedido. Nos reservamos el derecho de limitar o prohibir las órdenes que, a nuestro juicio, parecen ser colocado por los concesionarios, revendedores o distribuidores.
                                    </p>

                                    <p>Te comprometes a proporcionar información actual, completa y precisa para todas las reservas efectuadas a través de este sitio. Te comprometes a actualizar rápidamente tu cuenta y otra información, incluyendo tu dirección de correo electrónico y números de tarjetas de crédito y fechas de vencimiento, para que podamos completar tus transacciones y contactarte cuando sea necesario.
                                    </p>

                                    <p>Para más detalles, por favor revisa nuestra Política de Devoluciones.</p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                            <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseEight_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 7 – Herramientas opcionales
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseEight_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Es posible que te proporcionemos acceso a herramientas de terceros a los cuales no monitoreamos y sobre los que no tenemos acceso ni control alguno.
                                    </p>

                                    <p>Reconoces y aceptas que proporcionamos acceso a este tipo de herramientas "tal cual" y "según disponibilidad" sin garantías, representaciones o condiciones de ningún tipo y sin ningún respaldo. No tendremos responsabilidad alguna derivada de o relacionada con tu uso de herramientas proporcionadas por terceras partes.
                                    </p>

                                    <p>Cualquier uso que hagas de las herramientas opcionales que se ofrecen a través del sitio resulta bajo tu propio riesgo y discreción y debes asegurarte de estar familiarizado y aprobar los términos bajo los cuales estas herramientas son proporcionadas por el o los proveedores de terceros.
                                    </p>

                                    <p>También es posible que, en el futuro, te ofrezcamos nuevos servicios y/o características a través del sitio web (incluyendo el lanzamiento de nuevas herramientas y recursos). Estas nuevas características y/o servicios también estarán sujetos a estos Términos de Servicio.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                            <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseNine_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 8 – Enlaces de terceras partes
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseNine_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Cierto contenido, productos y servicios disponibles en este sitio pueden incluir material de terceras partes.
                                    </p>

                                    <p>Enlaces de terceras partes en este sitio pueden direccionarte a sitios web de terceras partes que no están relacionadas a Instructores. No nos responsabilizamos de examinar o evaluar el contenido o exactitud de los mismos y no garantizamos ni tendremos ninguna obligación o responsabilidad por cualquier material de terceros o sitios web, o de cualquier material, productos o servicios de terceros.
                                    </p>

                                    <p>No nos hacemos responsables de cualquier daño o daños relacionados con la adquisición o utilización de bienes, servicios, recursos, contenidos, o cualquier otra transacción realizadas en conexión con sitios web de terceros. Por favor revisa cuidadosamente las políticas y prácticas de terceros y asegúrate de entenderlas antes de participar en cualquier transacción. Quejas, reclamos, inquietudes o preguntas con respecto a productos de terceros deben ser dirigidas a la tercera parte.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                            <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseTen_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 9 – Comentarios de usuarios, captación y otros envíos
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseTen_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Si, a pedido nuestro, envías ciertas presentaciones específicas (por ejemplo la participación en concursos) o sin un pedido de nuestra parte envías ideas creativas, sugerencias, proposiciones, planes, u otros materiales, ya sea en línea, por email, por correo postal, o de otra manera (colectivamente, 'comentarios'), aceptas que podamos, en cualquier momento, sin restricción, editar, copiar, publicar, distribuir, traducir o utilizar por cualquier medio comentarios que nos hayas enviado. No tenemos ni tendremos ninguna obligación (1) de mantener ningún comentario confidencialmente; (2) de pagar compensación por comentarios; o (3) de responder a comentarios.
                                    </p>

                                    <p>Nosotros podemos, pero no tenemos obligación de, monitorear, editar o remover contenido que consideremos sea ilegítimo, ofensivo, amenazante, calumnioso, difamatorio, pornográfico, obsceno u objetable o viole la propiedad intelectual de cualquiera de las partes o los Términos de Servicio.
                                    </p>

                                    <p>Aceptas que tus comentarios no violarán los derechos de terceras partes, incluyendo derechos de autor, marca, privacidad, personalidad u otros derechos personales o de propiedad. Asimismo, aceptas que tus comentarios no contienen material difamatorio o ilegal, abusivo u obsceno, o contienen virus informáticos u otro malware que pudiera, de alguna manera, afectar el funcionamiento del Servicio o de cualquier sitio web relacionado. No puedes utilizar una dirección de correo electrónico falsa, usar otra identidad que no sea la verdadera, o engañar a terceras partes o a nosotros en cuanto al origen de tus comentarios. Tu eres el único responsable por los comentarios que haces y su precisión. No nos hacemos responsables y no asumimos ninguna obligación con respecto a los comentarios publicados por ti o cualquier tercera parte.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                                <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseEleven_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 10 - Información personal
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseEleven_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Tu presentación de información personal a través del sitio se rige por nuestra Política de Privacidad. Ver nuestra Política de Privacidad.</p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseTwelve_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 11 – Errores, inexactitudes y omisiones
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseTwelve_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Eventualmente podría haber información en nuestro sitio o en el Servicio que contiene errores tipográficos, inexactitudes u omisiones involuntarias que puedan estar relacionadas con las descripciones de productos, precios, promociones, ofertas, gastos de envío del producto, el tiempo de tránsito y la disponibilidad. Nos reservamos el derecho de corregir los errores, inexactitudes u omisiones y de cambiar o actualizar la información o cancelar pedidos si alguna información en el Servicio o en cualquier sitio web relacionado es inexacta en cualquier momento sin previo aviso (incluso después de que hayas enviado tu orden).
                                    </p>

                                    <p>No asumimos ninguna obligación de actualizar, corregir o aclarar la información en el Servicio o en cualquier sitio web relacionado, incluyendo, sin limitación, la información de precios, excepto cuando sea requerido por la ley. Ninguna especificación actualizada o fecha de actualización aplicada en el Servicio o en cualquier sitio web relacionado, debe ser tomada para indicar que toda la información en el Servicio o en cualquier sitio web relacionado ha sido modificado o actualizado.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                                <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseThirteen_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 12 – Usos prohibidos
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseThirteen_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>En adición a otras prohibiciones como se establece en los Términos de Servicio, queda terminantemente prohibido el uso del sitio o su contenido: (a) para cualquier propósito ilegal; (b) para pedirle a otros que realicen o participen en actos ilícitos; (c) para violar cualquier regulación legal, sean leyes internacionales, nacionales, provinciales, decretos, ordenanzas municipales, o cualquier otra; (d) para infringir o violar el derecho de propiedad intelectual de Instructores o de terceras partes; (e) para acosar, abusar, insultar, dañar, difamar, calumniar, desprestigiar, intimidar o discriminar por razones de género, orientación sexual, religión, etnia, raza, edad, nacionalidad o discapacidad; (f) para presentar información falsa o engañosa; (g) para cargar o transmitir virus o cualquier otro tipo de código malicioso que sea o pueda ser utilizado en cualquier forma que pueda comprometer la funcionalidad o el funcionamiento del Servicio o de cualquier sitio web relacionado, otros sitios o Internet; (h) para recopilar o rastrear información personal de terceros; (i) para generar spam, phish, pharm, pretext, spider, crawl, or scrape; (j) para cualquier propósito obsceno o inmoral; o (k) para interferir con o burlar los elementos de seguridad del Servicio o cualquier sitio web relacionado, así como de otros sitios o Internet. Nos reservamos el derecho de suspender el uso del Servicio o de cualquier sitio web relacionado por violar cualquiera de los ítems  descriptos previamente, o cuando de los mismos actos quede de manifiesto una conducta inapropiada o desleal.
                                    </p>


                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                                <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseFourteen_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 13 – Exclusión de garantías: Limitación de responsabilidad
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseFourteen_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>No garantizamos ni aseguramos que el uso de nuestro servicio será ininterrumpido, puntual, seguro o libre de errores.
                                    </p>

                                    <p>No garantizamos que los resultados que se puedan obtener del uso del servicio serán exactos o confiables.
                                    </p>

                                    <p>Aceptas que eventualmente podemos quitar el servicio por períodos de tiempo indefinidos o cancelar el servicio en cualquier momento sin previo aviso.
                                    </p>

                                    <p>Aceptas expresamente que el uso de, o la posibilidad de utilizar, el servicio es bajo tu propio riesgo. El servicio y todos los productos y servicios proporcionados a través del servicio son (salvo lo expresamente manifestado por nosotros) proporcionados "tal cual" y "según esté disponible" para su uso, sin ningún tipo de representación, garantías o condiciones de ningún tipo, ya sea expresa o implícita, incluidas todas las garantías o condiciones implícitas de comercialización, calidad comercializable, la aptitud para un propósito particular, durabilidad, título y no infracción.
                                    </p>

                                    <p>En ningún caso Instructores, nuestros directores, funcionarios, empleados, afiliados, agentes, contratistas, internos, proveedores, prestadores de servicios o licenciantes serán responsables por cualquier daño, pérdida, reclamo, o daños directos, indirectos, incidentales, punitivos, especiales o consecuentes de cualquier tipo, incluyendo, sin limitación, pérdida de beneficios, pérdida de ingresos, pérdida de ahorros, pérdida de datos, costos de reemplazo, o cualquier daño similar, ya sea basado en contrato, agravio (incluyendo negligencia), responsabilidad estricta o de otra manera, como consecuencia del uso de cualquiera de los servicios o productos adquiridos mediante el servicio, o por cualquier otro reclamo relacionado de alguna manera con el uso del servicio o cualquier producto, incluyendo pero no limitado, a cualquier error u omisión en cualquier contenido, o cualquier pérdida o daño de cualquier tipo incurridos como resultados de la utilización del servicio o cualquier contenido (o producto) publicado, transmitido, o que se pongan a disposición a través del servicio, incluso si se avisa de su posibilidad. Debido a que algunos estados o jurisdicciones no permiten la exclusión o la limitación de responsabilidad por daños consecuenciales o incidentales, en tales estados o jurisdicciones, nuestra responsabilidad se limitará en la medida máxima permitida por la ley.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseFifteen_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 14 – Indemnización
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseFifteen_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Aceptas indemnizar, defender y mantener indemne INSTRUCTORES y nuestras matrices, subsidiarias, afiliados, socios, funcionarios, directores, agentes, contratistas, concesionarios, proveedores de servicios, subcontratistas, proveedores, internos y empleados, de cualquier reclamo o demanda, incluyendo honorarios razonables de abogados, hechos por cualquier tercero a causa o como resultado de tu incumplimiento de las Condiciones de Servicio o de los documentos que incorporan como referencia, o la violación de cualquier ley o de los derechos de un tercero.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseSixteen_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 15 – Divisibilidad
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseSixteen_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>En el caso de que se determine que cualquier disposición de estas Condiciones de Servicio sea ilegal, nula o inejecutable, dicha disposición será, no obstante, efectiva a obtener la máxima medida permitida por la ley aplicable, y la parte no exigible se considerará separada de estos Términos de Servicio, dicha determinación no afectará la validez de aplicabilidad de las demás disposiciones restantes. 
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseSeventeen_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 16 – Rescisión
                                </h5>
                            </div>
                            <div id="collapseSeventeen_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Las obligaciones y responsabilidades de las partes que hayan incurrido con anterioridad a la fecha de terminación sobrevivirán a la terminación de este acuerdo a todos los efectos.</p>

                                    <p>Estas Condiciones de servicio son efectivos a menos que y hasta que sea terminado por ti o nosotros. Puedes terminar estos Términos de Servicio en cualquier momento por avisarnos -mediante notificación fehaciente por cualquier medio- que ya no deseas utilizar nuestros servicios, o cuando dejes de usar nuestro sitio.</p>

                                    <p>Si a nuestro juicio, fallas, o se sospecha que haz fallado, en el cumplimiento de cualquier término o disposición de estas Condiciones de Servicio, también podemos terminar este acuerdo en cualquier momento sin previo aviso, y seguirás siendo responsable de todos los montos adeudados hasta incluida la fecha de terminación; y/o en consecuencia podemos negarte el acceso a nuestros servicios (o cualquier parte del mismo).
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseEighteen_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 17 – Acuerdo completo
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseEighteen_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Nuestra falla para ejercer o hacer valer cualquier derecho o disposición de estas Condiciones de Servicio no constituirá una renuncia a tal derecho o disposición.
                                    </p>

                                    <p>Estas Condiciones del servicio y las políticas o reglas de operación publicadas por nosotros en este sitio o con respecto al servicio constituyen el acuerdo completo y el entendimiento entre tú y nosotros y rigen el uso del Servicio y reemplaza cualquier acuerdo, comunicaciones y propuestas anteriores o contemporáneas, ya sea oral o escrita, entre tú y nosotros (incluyendo, pero no limitado a, cualquier versión previa de los Términos de Servicio).
                                    </p>

                                    <p>Cualquier ambigüedad en la interpretación de estas Condiciones del servicio no se interpretarán en contra de INSTRUCTORES.
                                    </p>


                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseTwo_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 18 - Ley
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseTwo_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Estas Condiciones del servicio y cualquier acuerdo aparte en el que te proporcionemos servicios se regirán e interpretarán de conformidad con las leyes de la República Argentina y subsidiariamente con las leyes de la Provincia de Río Negro, República Argentina.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseNineteen_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 19 – Cambios en los Términos de Servicio
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseNineteen_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Puedes revisar la versión más actualizada de los Términos de Servicio en cualquier momento en esta página.
                                    </p>

                                    <p>Nos reservamos el derecho, a nuestra sola discreción, de actualizar, modificar o reemplazar cualquier parte de estas Condiciones del servicio mediante la publicación de las actualizaciones y los cambios en nuestro sitio web. Es tu responsabilidad revisar nuestro sitio web periódicamente para verificar los eventuales cambios. El uso continuo del acceso a nuestro sitio Web o del Servicio después de la publicación de cualquier cambio en estas Condiciones de servicio implica la aceptación de dichos cambios.
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseTwenty_terms" aria-expanded="false">
                                        <i class="indicator ti-plus"></i>Sección 20 - Información de contacto
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseTwenty_terms" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Puede contactarnos a través del correo dispuesto para tal fin: info@instructores.com.ar.</p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->



                    </div>


                    <!-- /accordion Terms -->
                    
                        <h4 class="nomargin_top">Declaración de privacidad</h4>
                    <div role="tablist" class="add_bottom_45 accordion_2" id="private">
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseOne_private" aria-expanded="true"><i class="indicator ti-plus"></i>Sección 1 - ¿Qué hacemos con tu información?</a>
                                </h5>
                            </div>

                            <div id="collapseOne_private" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Cuando compras algo de nuestra plataforma, como parte del proceso de compra venta, nosotros recolectamos la información personal que nos das tales como nombre, dirección y correo electrónico.</p>

                                    <p>Cuando navegas en nuestra plataforma, también recibimos de manera automática la dirección de protocolo de internet de tu computadora (IP) con el fin de proporcionarnos información que nos ayuda a conocer acerca de su navegador y sistema operativo.</p>

                                    <p>Por favor, lee estos Términos de Servicio cuidadosamente antes de acceder o utilizar nuestro sitio web. Al acceder o utilizar cualquier parte del sitio, estás aceptando los Términos de Servicio. Si no estás de acuerdo con todos los términos y condiciones de este acuerdo, entonces no deberías acceder a la página web o usar cualquiera de los servicios. Si las Términos de Servicio son considerados una oferta, la aceptación está expresamente limitada a estos Términos de Servicio.</p>

                                    <p>Email marketing (si aplicara): Con tu permiso, podremos enviarte correos electrónicos acerca de nuestra tienda, nuevos productos y otras actualizaciones.</p>

                                


                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapse2_private" aria-expanded="true"><i class="indicator ti-plus"></i>Sección 2 - Consentimiento</a>
                                </h5>
                            </div>

                            <div id="collapse2_private" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Cómo obtienen mi consentimiento?</p>

                                    <p>Cuando nos provees tu información personal para completar una transacción, verificar tu tarjeta de crédito, crear una orden,  concertar un envío o hacer una devolución, implicamos que aceptas la recolección y uso por esa razón específica solamente.</p>

                                    <p>Si te pedimos tu información personal por una razón secundaria, como marketing, te pediremos directamente tu expreso consentimiento, o te daremos la oportunidad de negarte.</p>

                                    <p>¿Cómo puedo anular mi consentimiento?¿Cómo puedo anular mi consentimiento?</p>

                                    <p>Si luego de haber aceptado cambias de opinión, puedes anular tu consentimiento para que te contactemos, por la recolección, uso o divulgación de tu información, en cualquier momento, contactándonos a info@instructores.</p>

                                


                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                            <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapse3_private" aria-expanded="true"><i class="indicator ti-plus"></i>Sección 3 - Divulgación</a>
                                </h5>
                            </div>

                            <div id="collapse3_private" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Podemos divulgar tu información personal si se nos requiere por ley o si violas nuestros Términos de Servicio.</p>

                                

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                            <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapse4_private" aria-expanded="true"><i class="indicator ti-plus"></i>Sección 4 - Pasarelas de pago</a>
                                </h5>
                            </div>

                            <div id="collapse4_private" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Nuestra pasarela de pago se encuentra alojada en Mercadopago. Ellos nos proporcionan la plataforma de comercio electrónico en línea que nos permite venderte nuestros productos y servicios.</p>

                                    <p>Todas las pasarelas de pago directo se adhieren a los estándares establecidos por PCI-DSS como lo indicado por el Consejo de Normas de Seguridad de PCI, que es un esfuerzo conjunto de marcas como Visa, MasterCard, American Express y Discover.</p>

                                    <p>Los requisitos del PCI-DSS ayudan a garantizar el manejo seguro de la información de tarjetas de crédito de las tiendas y sus proveedores de servicios.</p>

                                    <p>Para una visión más clara, es posible que también desees leer las Condiciones de servicio de Mercadopago o Declaración de privacidad.</p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                                <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapse5_private" aria-expanded="true"><i class="indicator ti-plus"></i>Sección 5 - Servicios de terceras partes</a>
                                </h5>
                            </div>

                            <div id="collapse5_private" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>En general, los proveedores de terceras partes utilizados por nosotros solo recopilarán, usarán y divulgarán tu información en la medida que sea necesaria para que les permita desempeñar los servicios que nos proveen.</p>

                                    <p>Sin embargo, algunos proveedores de servicios de terceros, tales como pasarelas de pago y otros procesadores de transacciones de pago, tienen sus propias políticas de privacidad con respecto a la información que estamos obligados a proporcionarles para las transacciones relacionadas con las compras.</p>

                                    <p>Para estos proveedores, te recomendamos que leas las políticas de privacidad para que puedas entender la manera en que tu información personal será manejada.</p>

                                    <p>En particular, recuerda que algunos proveedores pueden estar ubicados o contar con instalaciones que se encuentran en una jurisdicción diferente a ti o nosotros.  Así que si deseas proceder con una transacción que involucra los servicios de un proveedor a terceros, tu información puede estar sujeta a las leyes de la jurisdicción (jurisdicciones) en que se encuentra el proveedor de servicios o sus instalaciones.</p>

                                    <p>A modo de ejemplo, si te encuentras en Canadá y tu transacción es procesada por una pasarela de pago con sede en Estados Unidos, entonces tu información personal utilizada para completar la transacción puede ser sujeto de divulgación en virtud de la legislación de Estados Unidos, incluyendo la Ley Patriota.</p>

                                    <p>Una vez que abandonas el sitio web de nuestra tienda o te rediriges a un sitio o aplicación de terceros, ya no estás siendo regulados por la presente Política de Privacidad o los Términos de Servicio de nuestra página web.</p>

                                    <p>Enlaces</p>

                                    <p>Cuando haces clic en enlaces de nuestra tienda, puede que seas redirigido fuera de nuestro sitio.  No somos responsables por las prácticas de privacidad de otros sitios y te recomendamos leer sus normas de privacidad.</p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                                    <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapse6_private" aria-expanded="true"><i class="indicator ti-plus"></i>Sección 6 - Seguridad</a>
                                </h5>
                            </div>

                            <div id="collapse6_private" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Para proteger tu información personal, tomamos precauciones razonables y seguimos las mejores prácticas de la industria para asegurarnos de que no haya pérdida de manera inapropiada, mal uso, acceso, divulgación, alteración o destrucción de la misma.</p>

                                    <p>SI nos proporcionas la información de tu tarjeta de crédito, dicha información es encriptada mediante la tecnología Secure Socket Layer (SSL) y se almacena con un cifrado AES-256.  Aunque ningún método de transmisión a través de Internet o de almacenamiento electrónico es 100% seguro, seguimos todos los requisitos de PCI-DSS e implementamos normas adicionales aceptadas por la industria.</p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="collapse8_private" aria-expanded="true"><i class="indicator ti-plus"></i>Sección 7 - Edad de consentimiento</a>
                                </h5>
                            </div>

                            <div id="collapse8_private" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Al utilizar este sitio, declaras que tienes al menos la mayoría de edad en tu estado o provincia de residencia, o que tienes la mayoría de edad en tu estado o provincia de residencia y que nos has dado tu consentimiento para permitir que cualquiera de tus dependientes menores use este sitio.</p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                                                    <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapse9_private" aria-expanded="true"><i class="indicator ti-plus"></i>Sección 8 - Cambios a esta política de privacidad</a>
                                </h5>
                            </div>

                            <div id="collapse9_private" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Nos reservamos el derecho de modificar esta política de privacidad en cualquier momento, así que por favor revísela frecuentemente.  Cambios y aclaraciones entrarán en vigencia inmediatamente después de su publicación en el sitio web.  Si hacemos cambios materiales a esta política, notificaremos aquí que ha sido actualizada, por lo que cual estás enterado de qué información recopilamos, cómo y bajo qué circunstancias, si las hubiere, la utilizamos y/o divulgamos.</p>

                                    <p>Si nuestra plataforma es adquirida o fusionada con otra empresa, tu información puede ser transferida a los nuevos propietarios, para que podamos seguir vendiéndote productos.</p>

                                </div>
                            </div>
                        </div>
                        <!-- /card -->
                        <div class="card">
                            <div class="card-header" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapse10_private" aria-expanded="true"><i class="indicator ti-plus"></i>Preguntas e información de contacto</a>
                                </h5>
                            </div>

                            <div id="collapse10_private" class="collapse" role="tabpanel" data-parent="#terms">
                                <div class="card-body">
                                    <p>Si quieres  acceder, corregir, enmendar o borrar cualquier información personal que poseamos sobre ti, registrar una queja, o simplemente quieres más información contacta a nuestro Oficial de Cumplimiento de Privacidad info@instructores.com.ar.</p>


                                </div>
                            </div>
                        </div>
                        <!-- /card -->

                    </div>


                    <!-- /accordion Terms -->
                    </div>
                    <!-- /accordion Booking -->
                
                </div>
                <!-- /col -->

            </div>
            <!-- /row -->
        </div>
        <!--/container-->

@endsection



@section('custom-js')


@endsection