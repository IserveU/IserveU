<!doctype html>
<html ng-app="iserveu">
    <head>
        <title>IserveU</title>
        <meta name="viewport" content="initial-scale=1" />  
        <link rel="stylesheet" href="css/dependencies.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon shortcut" type="image/png" href="/img/symbol.png" />        
    </head>
    <body>
        <div ui-view="body" layout="row" ng-controller="AppController"></div>

      <!--  <div role="main" ui-view="login" layout="row" class="fullscreen" layout-align="center center" layout-fill></div> -->


        
    </body>        
        <script src="/js/dependencies.js"></script>        
    <script src="/js/iserveu-app.js"></script> 
        <script>
            angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
        </script>  
</html> 
