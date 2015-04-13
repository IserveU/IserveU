<!doctype html>
<html>
    <head>
        <title>IserveU</title>
        <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body ng-app="iserveu">

        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-logo" href="#"><img src="img/iserveu-logo.png"/></a>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="col-sm-3 well" ng-controller="sidebarController as sidebar">
                <h3>Motions</h3>
                <p ng-repeat="motion in sidebar.motions"><a ui-sref="motion({motionId: motion.id})">{{motion.title}}</a></p>
            </div>
            <div class="col-sm-9">
                <div ui-view></div>
            </div>
        </div>


        <a href="/auth/login">Login</a> | <a href="/auth/logout">Logout</a>

    </body>

    <!-- Application Dependencies -->
    <script type="text/javascript" src="bower_components/angular/angular.js"></script>
    <script type="text/javascript" src="bower_components/jquery/dist/jquery.js"></script>
    <script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.js"></script>
    <script type="text/javascript" src="bower_components/angular-bootstrap/ui-bootstrap.js"></script>
    <script type="text/javascript" src="bower_components/angular-bootstrap/ui-bootstrap-tpls.js"></script>
    <script type="text/javascript" src="bower_components/angular-resource/angular-resource.js"></script>
    <script type="text/javascript" src="bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
    <script type="text/javascript" src="bower_components/angular-sanitize/angular-sanitize.min.js"></script>
    <script type="text/javascript" src="bower_components/moment/moment.js"></script>

    <!-- Application Scripts -->
    <script type="text/javascript" src="app/app.js"></script>
    <script type="text/javascript" src="app/components/sidebar/sidebarController.js"></script>
    <script type="text/javascript" src="app/components/motion/motionController.js"></script>
    <script type="text/javascript" src="app/components/motion/motionService.js"></script>
    
</html>