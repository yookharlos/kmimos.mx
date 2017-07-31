<?php
	$CONTENIDO = '
	<div class="kmisaldo">
		<strong>'.kmimos_saldo_titulo().':</strong> MXN $'.kmimos_get_kmisaldo().'
	</div>';

		// if( isset($_GET["fm"]) ){
		// 	global $wpdb;
		// 	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		// 		$wpdb->query( "DELETE FROM wp_posts WHERE ID = ".$cart_item["booking"]["_booking_id"] );
		// 		$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id = ".$cart_item["booking"]["_booking_id"] );
		// 	}
		// 	WC()->cart->empty_cart();
		// }

	global $wpdb;
	$sql = "SELECT * FROM $wpdb->posts WHERE post_type = 'wc_booking' AND post_author = {$user_id} AND post_status NOT LIKE '%cart%' ORDER BY id DESC";
	$reservas = $wpdb->get_results($sql);

	//CART
	$items = WC()->cart->get_cart();

	if(count($items) > 0){
		$CONTENIDO .= '
		<h1 class="theme_tite theme_table_title">Reservas Por Completar</h1>
		<table class="vlz_tabla table table-striped table-responsive">
			<tr>
				<th class="product-name">Servicio</th>
				<th class="product-service">Detalle</th>
				<th class="product-subtotal">Total</th>
				<th>Acciones</th>
			</tr>';

			foreach($items as $cart_item_key => $cart_item){
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				$CONTENIDO .= '
				<tr>
					<td class="product-name" data-title="Servicio">';
						if ( ! $_product->is_visible() ) {
							$CONTENIDO .= apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
						} else {
							$CONTENIDO .= apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $_product->get_title() ), $cart_item, $cart_item_key );
						} $CONTENIDO .= '
					</td>
					<td class="product-service" data-title="Servicio">'
						.WC()->cart->get_item_data( $cart_item );
						if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
							$CONTENIDO .= '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
						}
						$CONTENIDO .= '
					</td>
					<td class="product-subtotal" data-title="Total">
						'.apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ).'
					</td>
					<td style="text-align: right; width: 166px;">'.
						build_select(
							array(
								array(
									'text'=>'Ver',
									'value'=>esc_url( WC()->cart->get_cart_url())
								),
								array(
									'text'=>'Pagar',
									'value'=>esc_url( WC()->cart->get_checkout_url())
								),
								array(
									'text'=>'Eliminar',
									'value'=>esc_url( WC()->cart->get_remove_url($cart_item_key))
								)
							)).'
					</td>
				</tr>';
			}
			$CONTENIDO .= '
		</table>';
	}

	if( count($reservas) > 0 ){

		$booking_coming=array();
		$booking_coming['openpay_unpaid']=array();
		$booking_coming['openpay_unpaid']['title']='Reservas pendientes por pagar en tienda por conveniencia';
		$booking_coming['openpay_unpaid']['th']=array();
		$booking_coming['openpay_unpaid']['tr']=array();

		$booking_coming['pending']=array();
		$booking_coming['pending']['title']='Reservas en error en tarjetas de credito';
		$booking_coming['pending']['th']=array();
		$booking_coming['pending']['tr']=array();

		$booking_coming['no-confirmed']=array();
		$booking_coming['no-confirmed']['title']='Reservas Pendientes por Confirmar';
		$booking_coming['no-confirmed']['th']=array();
		$booking_coming['no-confirmed']['tr']=array();

		$booking_coming['confirmed']=array();
		$booking_coming['confirmed']['title']='Reservas Confirmadas';
		$booking_coming['confirmed']['th']=array();
		$booking_coming['confirmed']['tr']=array();

		$booking_coming['completed']=array();
		$booking_coming['completed']['title']='Reservas Completadas';
		$booking_coming['completed']['th']=array();
		$booking_coming['completed']['tr']=array();

		$booking_coming['cancelled']=array();
		$booking_coming['cancelled']['title']='Reservas Canceladas';
		$booking_coming['cancelled']['th']=array();
		$booking_coming['cancelled']['tr']=array();

		$booking_coming['modified']=array();
		$booking_coming['modified']['title']='Reservas Modificadas';
		$booking_coming['modified']['th']=array();
		$booking_coming['modified']['tr']=array();

		$booking_coming['other']=array();
		$booking_coming['other']['title']='Otras Reservas';
		$booking_coming['other']['th']=array();
		$booking_coming['other']['tr']=array();

		//PENDIENTE POR PAGO EN TIENDA DE CONVENINCIA
		if($reservas > 0){
			foreach($reservas as $key => $reserva){

				$_metas_reserva=get_post_meta($reserva->ID);
				$_metas=get_post_meta($reserva->post_parent);

				$pedido = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = ".$_metas_reserva['_booking_product_id'][0]);

				$orden_status = $wpdb->get_var("SELECT post_status FROM $wpdb->posts WHERE ID = ".$reserva->post_parent);

				$creada =  strtotime( $reserva->post_date );
				$inicia =  strtotime( $_metas_reserva['_booking_start'][0] );

				$penalizar = ( ($inicia-$creada) <= 172800 ) ? "SI" : "NO";

				//RESERVAS PENDIENTES POR ERROR DE PAGOS DE TARJETAS
				if($orden_status=='wc-pending') {

				}else if($orden_status == 'wc-on-hold' && $_metas['_payment_method'][0] == 'openpay_stores'){

					$pdf = array();

					$options='<a class="theme_btn" href="'.$_metas['_openpay_pdf'][0].'">Ver PDF</a>';
					$options.='<a class="theme_btn" href="'.get_home_url().'/perfil-usuario/historial/ver/'.$reserva->post_parent.'">Ver</a>';
					$options.='<a class="theme_btn cancelled" href="'.get_home_url().'/wp-content/plugins/kmimos/orden.php?o='.$reserva->post_parent.'&s=0">Cancelar</a>';

					$options_args=array();
					if($_metas['_openpay_pdf'][0] != ''){
						$options_args[]=array(
							'text'=>'Ver PDF',
							'value'=>$_metas['_openpay_pdf'][0]
						);
					}

					$options_args[]=array(
						'text'=>'Ver',
						'value'=>get_home_url().'/perfil-usuario/historial/ver/'.$reserva->post_parent
					);

					// Modificacion Ángel Veloz
					$options_args[]=array(
						'text'=>'Modificar',
						'class' => 'modified',
						'value' => md5($reserva->ID)."_".md5($user_id)."_".md5($pedido->ID)
					);

					$options_args[]= array(
						'text'=>'Cancelar',
						'class'=>'cancelled action_confirmed',
						'value'=>get_home_url().'/wp-content/plugins/kmimos/orden.php?o='.$reserva->post_parent.'&s=0&action=noaction&show=noshow'
					);

					$options=build_select($options_args);

					$booking_th=array();
					$booking_th[]=array('class'=>'','data'=>'Reserva');
					$booking_th[]=array('class'=>'','data'=>'Servicio');
					//$booking_th[]=array('class'=>'','data'=>'ESTATUS');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Inicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Finb');
					$booking_th[]=array('class'=>'','data'=>'Acciones');
					$booking_coming['openpay_unpaid']['th']=$booking_th;

					$booking_td=array();
					$booking_td[]=array('class'=>'','data'=>$reserva->ID);
					$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
					//$booking_td[]=array('class'=>'','data'=>$reserva->post_status);
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
					$booking_td[]=array('class'=>'','data'=>$options);
					$booking_coming['openpay_unpaid']['tr'][]=$booking_td;

					//RESERVAS CONFIRMADAS
				}else if($reserva->post_status=='confirmed' && strtotime($_metas_reserva['_booking_end'][0])>time()){
					
					$options='<a class="theme_btn" href="'.get_home_url().'/perfil-usuario/historial/ver/'.$reserva->post_parent.'">Ver</a>';
					$options.='<a class="theme_btn cancelled" href="'.get_home_url().'/wp-content/plugins/kmimos/orden.php?o='.$reserva->post_parent.'&s=0">Cancelar</a>';
					
					// Modificacion Ángel Veloz
					$options = build_select(
						array(
							array(
								'text'=>'Ver',
								'value'=>get_home_url().'/perfil-usuario/historial/ver/'.$reserva->post_parent
							),
							array(
								'text'=>'Modificar',
								'class' => 'modified',
								'value' => md5($reserva->ID)."_".md5($user_id)."_".md5($pedido->ID)
							)
						)
					);

					$booking_th=array();
					$booking_th[]=array('class'=>'','data'=>'Reserva');
					$booking_th[]=array('class'=>'','data'=>'Servicio');
					//$booking_th[]=array('class'=>'','data'=>'ESTATUS');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Inicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Fin');
					$booking_th[]=array('class'=>'','data'=>'Acciones');
					$booking_coming['confirmed']['th']=$booking_th;

					$booking_td=array();
					$booking_td[]=array('class'=>'','data'=>$reserva->ID);
					$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
					//$booking_td[]=array('class'=>'','data'=>$reserva->post_status);
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
					$booking_td[]=array('class'=>'','data'=>$options);
					$booking_coming['confirmed']['tr'][]=$booking_td;



					//RESERVAS COMPLETADAS
				}else if($reserva->post_status=='complete' || ($reserva->post_status=='confirmed' && strtotime($_metas_reserva['_booking_end'][0])<time())){

					$options='<a class="theme_btn" href="'.get_home_url().'/perfil-usuario/historial/ver/'.$reserva->post_parent.'">Ver</a>';
					$options='<a class="theme_btn" href="'.get_home_url().'/valorar-cuidador/?id='.$reserva->ID.'">Valorar</a>';
					$options.='<a class="theme_btn cancelled" href="'.get_home_url().'/wp-content/plugins/kmimos/orden.php?o='.$reserva->post_parent.'&s=0">Cancelar</a>';
					$options=build_select(
						array(
							array(
								'text'=>'Ver',
								'value'=>get_home_url().'/perfil-usuario/historial/ver/'.$reserva->post_parent
							),
							array(
								'text'=>'Valorar',
								'value'=>get_home_url().'/valorar-cuidador/?id='.$reserva->ID
							)
						));

					$booking_th=array();
					$booking_th[]=array('class'=>'','data'=>'Reservas');
					$booking_th[]=array('class'=>'','data'=>'Servicio');
					//$booking_th[]=array('class'=>'','data'=>'ESTATUS');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Inicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Fin');
					$booking_th[]=array('class'=>'','data'=>'Acciones');
					$booking_coming['completed']['th']=$booking_th;

					$booking_td=array();
					$booking_td[]=array('class'=>'','data'=>$reserva->ID);
					$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
					//$booking_td[]=array('class'=>'','data'=>$reserva->post_status);
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
					$booking_td[]=array('class'=>'','data'=>$options);
					$booking_coming['completed']['tr'][]=$booking_td;


					//RESERVAS CANCELADAS
				}else if($reserva->post_status=='cancelled' || $reserva->post_status=='wc_cancelled'){

					$options = build_select(
						array(
							array(
								'text'=>'Ver',
								'value'=>get_home_url().'/perfil-usuario/historial/ver/'.$reserva->post_parent
							)
						));

					$booking_th=array();
					$booking_th[]=array('class'=>'','data'=>'Reserva');
					$booking_th[]=array('class'=>'','data'=>'Servicio');
					//$booking_th[]=array('class'=>'','data'=>'ESTATUS');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Inicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Fin');
					$booking_th[]=array('class'=>'','data'=>'Acciones');
					$booking_coming['cancelled']['th']=$booking_th;

					$booking_td=array();
					$booking_td[]=array('class'=>'','data'=>$reserva->ID);
					$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
					//$booking_td[]=array('class'=>'','data'=>$reserva->post_status);
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
					$booking_td[]=array('class'=>'','data'=>$options);
					$booking_coming['cancelled']['tr'][]=$booking_td;


				//RESERVAS PNDIENTES POR CONFIRMAR
				}else if($reserva->post_status=='modified'){
		
					// Modificacion Ángel Veloz
					$options = build_select(
						array(
							array(
								'text'=>'Ver',
								'value'=>get_home_url().'/perfil-usuario/historial/ver/'.$reserva->post_parent
							)
						));

					$booking_th=array();
					$booking_th[]=array('class'=>'','data'=>'Reserva');
					$booking_th[]=array('class'=>'','data'=>'Servicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Inicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Fin');
					$booking_th[]=array('class'=>'','data'=>'Acciones');
					$booking_coming['modified']['th']=$booking_th;

					$booking_td=array();
					$booking_td[]=array('class'=>'','data'=>$reserva->ID);
					$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
					$booking_td[]=array('class'=>'','data'=>$options);
					$booking_coming['modified']['tr'][]=$booking_td;

				}else if($reserva->post_status!='confirmed'){

					// Modificacion Ángel Veloz
					$options=build_select(
						array(
							array(
								'text'=>'Ver',
								'value'=>get_home_url().'/perfil-usuario/historial/ver/'.$reserva->post_parent
							),
							array(
								'text'  => 'Modificar',
								'class' => 'modified',
								'value' => md5($reserva->ID)."_".md5($user_id)."_".md5($pedido->ID)
							),
							array(
								'text'=>'Cancelar',
								'class'=>'cancelled action_confirmed',
								'value'=>get_home_url().'/wp-content/plugins/kmimos/orden.php?o='.$reserva->post_parent.'&s=0'
							)
						));

					$booking_th=array();
					$booking_th[]=array('class'=>'','data'=>'RESERVA');
					$booking_th[]=array('class'=>'','data'=>'SERVICIO');
					//$booking_th[]=array('class'=>'','data'=>'ESTATUS');
					$booking_th[]=array('class'=>'td_responsive','data'=>'INICIO');
					$booking_th[]=array('class'=>'td_responsive','data'=>'FIN');
					$booking_th[]=array('class'=>'','data'=>'ACCIONES');
					$booking_coming['no-confirmed']['th']=$booking_th;

					$booking_td=array();
					$booking_td[]=array('class'=>'','data'=>$reserva->ID);
					$booking_td[]=array('class'=>'','data'=>'<a href="'.get_home_url().'/producto/'.$pedido->post_name.'" target="_blank" >'.$pedido->post_title.'</a>');
					//$booking_td[]=array('class'=>'','data'=>$reserva->post_status);
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
					$booking_td[]=array('class'=>'','data'=>$options);
					$booking_coming['no-confirmed']['tr'][]=$booking_td;

				}else{

					$options = build_select(
						array(
							array(
								'text'=>'Ver',
								'value'=>get_home_url().'/perfil-usuario/historial/ver/'.$reserva->post_parent
							)
						));

					$booking_th=array();
					$booking_th[]=array('class'=>'','data'=>'Reserva');
					$booking_th[]=array('class'=>'','data'=>'Servicio');
					$booking_th[]=array('class'=>'','data'=>'Estatus');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Inicio');
					$booking_th[]=array('class'=>'td_responsive','data'=>'Fin');
					$booking_th[]=array('class'=>'','data'=>'Acciones');
					$booking_coming['other']['th']=$booking_th;

					$booking_td=array();
					$booking_td[]=array('class'=>'','data'=>$reserva->ID);
					$booking_td[]=array('class'=>'','data'=>$reserva->post_status);
					$booking_td[]=array('class'=>'','data'=>$reserva->post_status);
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_start'][0]));
					$booking_td[]=array('class'=>'td_responsive','data'=>date_boooking($_metas_reserva['_booking_end'][0]));
					$booking_td[]=array('class'=>'','data'=>$options);
					$booking_coming['other']['tr'][]=$booking_td;

				}
			}
		}
		

		//BUILD TABLE
		$CONTENIDO .= '<h1 style="margin: 0px; padding: 0px;">Mi Historial de Reservas</h1><hr style="margin: 5px 0px 10px;">'.build_table($booking_coming);

	}else{
		$CONTENIDO .= "<h1 style='line-height: normal;'>Usted aún no tiene reservas.</h1><hr>";
	}

?>