<?php include 'pre-header.php'; ?><!doctype html><html lang="es-ES" class="no-js"><head><title>Kmimos</title><meta charset="UTF-8"><?php 
	$HTML = '';	
	if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
		header('X-UA-Compatible: IE=edge,chrome=1');
	}
	if ( is_page() ){
		global $post;
		$descripcion = get_post_meta($post->ID, 'kmimos_descripcion', true);
		if( $descripcion != ""){
			$HTML .= "<meta name='description' content='{$descripcion}'>";
		}
	}
<<<<<<< HEAD
	$HTML .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">';
	wp_enqueue_style( 'style', getTema()."/style.css", array(), "1.0.0" );
=======

	$HTML .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">';

	wp_enqueue_style( 'style', getTema()."/style.css", array(), "1.0.0" );

>>>>>>> d91cc5de50f8bfa5b962648b4e40faa7989297dd
	wp_enqueue_style( 'generales_css', getTema()."/css/generales.css", array(), "1.0.0" );
	wp_enqueue_style( 'jquery.bxslider', getTema()."/css/jquery.bxslider.css", array(), "1.0.0" );
	wp_enqueue_style( 'bootstrap.min', getTema()."/css/bootstrap.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'kmimos_style', getTema()."/css/kmimos_style.css", array(), "1.0.0" );
<<<<<<< HEAD
	wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );
	wp_head(); 
=======

	wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );

	wp_head(); 

>>>>>>> d91cc5de50f8bfa5b962648b4e40faa7989297dd
	$HTML .= '
		<script type="text/javascript"> 
			var HOME = "'.getTema().'/"; 
			var RAIZ = "'.get_home_url().'/"; 
			var pines = []; 
		</script>
<<<<<<< HEAD
	</head>
	<body class="'.join( ' ', get_body_class( $class ) ).'" >';
	include_once("funciones.php");
	$MENU = get_menu_header();
=======

	</head>

	<body class="'.join( ' ', get_body_class( $class ) ).'" >';

	include_once("funciones.php");

	$MENU = get_menu_header();

>>>>>>> d91cc5de50f8bfa5b962648b4e40faa7989297dd
	if( !isset($MENU["head"]) ){
		$menus_normal = '
			<li><a id="login" style="padding-right: 15px">INICIAR SESIÓN</a></li>
			<li><a href="#" style="padding-left: 15px; border-left: 1px solid white;">REGISTRARME</a></li>
		';
		$menus_movil = '
			<li><a id="login_movil" class="km-nav-link hidden-sm hidden-md hidden-lg">INICIAR SESIÓN</a></li>
			<li><a href="#" class="km-nav-link hidden-sm hidden-md hidden-lg">REGISTRARME</a></li>
		';
	}else{
		$menus_normal = $MENU["head"].$MENU["body"].$MENU["footer"];
		$menus_movil = $MENU["head_movil"].$MENU["body"].$MENU["footer"];
	}
<<<<<<< HEAD
=======

>>>>>>> d91cc5de50f8bfa5b962648b4e40faa7989297dd
	$HTML .= '
		<nav class="navbar navbar-fixed-top bg-transparent">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<img src="'.getTema().'/images/new/km-navbar-mobile.svg" width="30">
					</button>
					<a class="navbar-brand" href="'.get_home_url().'"><img src="'.getTema().'/images/new/km-logos/km-logo.png" height="60"></a>
				</div>
				<ul class="hidden-xs nav-login">
					'.$menus_normal.'
				</ul>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="'.get_home_url().'/busqueda/" class="km-nav-link">BUSCAR CUIDADOR</a></li>
						<li><a href="km-cuidador.html" class="km-btn-primary hidden-xs">QUIERO SER CUIDADOR</a></li>
<<<<<<< HEAD
						'.$menus_movil.'
=======

						'.$menus_movil.'

>>>>>>> d91cc5de50f8bfa5b962648b4e40faa7989297dd
						<li><a href="'.get_home_url().'/busqueda/" class="km-nav-link hidden-sm hidden-md hidden-lg">BUSCAR CUIDADOR</a></li>
						<li><a href="km-cuidador.html" class="km-btn-primary hidden-sm hidden-md hidden-lg">QUIERO SER CUIDADOR</a></li>
					</ul>
				</div>
			</div>
		</nav>
	';
<<<<<<< HEAD
=======

>>>>>>> d91cc5de50f8bfa5b962648b4e40faa7989297dd
	if( !is_user_logged_in() ){
		$HTML .= "
			<div id='modal_login' class='modal_login'>
		        <div class='modal_container'>
		            <div class='modal_box'>
		                <img id='close_login' src='".getTema()."/images/closebl.png' />
<<<<<<< HEAD
=======

>>>>>>> d91cc5de50f8bfa5b962648b4e40faa7989297dd
						<form id='form_login'>
		                	<div class='form_box'>
			                	<input type='text' id='usuario' placeholder='Correo El&eacute;ctronico'>
			                	<input type='password' id='clave' placeholder='Contraseña'>
		                	</div>
		                	<div class='botones_box'>
		                		<input type='submit' value='Ingresar' style='display: none;'>
		                		<span id='login_submit'>Ingresar</span>
		                	</div>
		                </form>
<<<<<<< HEAD
=======

>>>>>>> d91cc5de50f8bfa5b962648b4e40faa7989297dd
						<form id='form_recuperar'>
		                	<div class='form_box'>
			                	<input type='text' id='usuario' placeholder='Correo El&eacute;ctronico'>
		                	</div>
		                	<div class='botones_box'>
		                		<span id='login_submit'>Recuperar</span>
		                	</div>
		                </form>
<<<<<<< HEAD
=======

>>>>>>> d91cc5de50f8bfa5b962648b4e40faa7989297dd
		            </div>
		        </div>
		    </div>
		";
	}
<<<<<<< HEAD
=======

>>>>>>> d91cc5de50f8bfa5b962648b4e40faa7989297dd
	echo comprimir_styles($HTML);