<!doctype html>
<html class="fixed">
    <head>
        <!-- Basic -->
        <meta charset="UTF-8">

        <title><?=strtoupper($args['header'])?>| Admin Zone</title>
        <base href="<?=ENV?>" target="_blank">
        <meta name="keywords" content="HTML5 Admin Template" />
        <meta name="description" content="AdminZone - Professional CRM Panel">
        <meta name="author" content="okler.net">

        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <!-- Web Fonts  -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

        <!-- Vendor CSS -->
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="assets/vendor/animate/animate.compat.css">
        <link rel="stylesheet" href="assets/vendor/font-awesome/css/all.min.css" />
        <link rel="stylesheet" href="assets/vendor/boxicons/css/boxicons.min.css" />
        <link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
        <link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />


        <?php 
        if($args['css']) {
            echo $args['css'];
        }
        ?>

        <!-- Theme CSS -->
        <link rel="stylesheet" href="assets/css/theme.css" />

        <!-- Skin CSS -->
        <link rel="stylesheet" href="assets/css/skins/default.css" />

        <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="assets/css/custom.css">

        <!-- Head Libs -->
        <script src="assets/vendor/modernizr/modernizr.js"></script>
        <script src="assets/vendor/jquery/jquery.js"></script>

    </head>
    <body>
        <section class="body">

            <!-- start: header -->
            <header class="header">
                <div class="logo-container">
                    <a href="/main" class="logo" target = "_self">
                        <img src="assets/img/adminZone.png" width="100" style="margin-top: -5px;" alt="Porto Admin" />
                    </a>
                    <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
                        <i class="fas fa-bars" aria-label="Toggle sidebar"></i>

                    </div>


                </div>
                <div class="header-right" style="margin-top: 5px;">
                    <div id="userbox" class="userbox">
                        <a href="#" data-bs-toggle="dropdown">
                            <div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
                                <span class="name"><?=$_SESSION['user']?></span>
                            </div>

                            <i class="fa custom-caret"></i>
                        </a>

                        <div class="dropdown-menu">
                            <ul class="list-unstyled mb-2">
                                <li class="divider"></li>
                                <li>
                                    <a role="menuitem" tabindex="-1" href="login/signOut" target="_SELF"><i class="bx bx-power-off"></i> Logout</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


                <!-- start: search & user box -->
                
                <!-- end: search & user box -->
            </header>
            <!-- end: header -->

            <div class="inner-wrapper" style="background-color: #e7e7e7;">
                <!-- start: sidebar -->
                <aside id="sidebar-left" class="sidebar-left">

                    <div class="sidebar-header">
                        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
                            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
                        </div>
                    </div>

                    <div class="nano">
                        <div class="nano-content">
                            <nav id="menu" class="nav-main" role="navigation">
                                <!-- <img src="assets/img/adminZone.png" width="120px" style="margin-left: 30px;" /> -->
                                <ul class="nav nav-main">
                                    <li class=" <?= ($args['active'] == "home") ? "nav-active" : ""; ?>">
                                        <a class="nav-link" href="main" target = "_self">
                                            <i class="bx bx-home-alt" aria-hidden="true"></i>
                                            <span>Anasayfa</span>
                                        </a>                        
                                    </li>
                                    
                                    <li class=" <?= ($args['active'] == "siteSettings") ? "nav-active" : ""; ?>">
                                        <a class="nav-link" href="setting" target = "_self">
                                            <i class="bx bx-cog" aria-hidden="true"></i>
                                            <span>Site Ayarları</span>
                                        </a>                        
                                    </li>
                                </ul>
                                <div class="col-lg-12" style="position: absolute; bottom: 15px; left: 10px;">
                                    <img src="assets/img/newmore-logo-beyaz.png" style="width:90px;">
                                    <br>
                                    <a href="https://www.newmore.com.tr">Newmore Reklam ve Yazılım Şirketi</a>
                                    
                                </div>
                                

                            </nav>
                        </div>

                        <script>
                            // Maintain Scroll Position
                            if (typeof localStorage !== 'undefined') {
                                if (localStorage.getItem('sidebar-left-position') !== null) {
                                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');

                                    sidebarLeft.scrollTop = initialPosition;
                                }
                            }
                        </script>
                    </div>
                </aside>