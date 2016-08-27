<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="initial-scale=1.0">    <!-- So that mobile webkit will display zoomed in -->
  <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->
  <title><?php echo Setting::get('site.name');?></title>
  <style type="text/css">

  /* Resets:see reset.css for details */
  body{background-color:#ffffff;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;}
  body{color:#505054;font-family:Helvetica,Arial,sans-serif;margin:0;padding:0;}
  table{border-spacing:0;border-collapse:collapse;border-spacing:0;}
  table td{border-collapse:collapse;}
  p{margin-bottom:1em;}
  h1.site-title { font-size: 30px;}
  .body-content img{max-width:100%;}

  /* Constrain email width for small screens */
  @media screen and (max-width:600px) {
    table[class="container"] { width:100%!important; }
  }

  /* Give content more room on mobile */
  @media screen and (max-width:480px) {
   table[class="container"] { width:100%!important; }
   div[class="body-content"]img { width:100%; }
   h1[class="site-title"] { font-size:25px!important; }
 }

 /* Styles for forcing columns to rows */
 @media only screen and (max-width:600px) {

  /* force container columns to (horizontal) blocks */
  table[class="container"] { width:100%!important; }
  h1[class="site-title"] { font-size:22px!important; }
}

</style>
</head>
<body style="margin:0; padding:0;background-color:#ffffff;" bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <!-- 100% wrapper -->
  <table style="background-color:#ffffff;" border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
    <tr>
      <td align="center" valign="top">

        <!-- ### 600PX CONTAINER ### -->
        <table class="container" cellpadding="0" cellspacing="0" border="0" width="600" style="background-color:#f3f3f3;" bgcolor="#f3f3f3;">
          <tr>
            <td width="200" style="font-family:Helvetica,Arial,sans-serif;text-align:left;background-color:#<?php echo Setting::get('theme.primary.400');?>;border-top:15px solid #<?php echo Setting::get('theme.primary.400');?>;" valign="middle" height="100" ><a style="color:#ffffff;font-weight:bold;text-decoration:none;" href="<?php echo Setting::get('email.footer.website');?>"><h1 class="site-title" style="margin:0;font-size:30px;padding:60px 30px 30px 30px;color:#ffffff;">
              <img width="200" src="<?=asset('/themes/'.Setting::get('themename','default').'/logo/logo_allwhite.png')?>">
            </h1></a></td>
            <td width="50" align="left" valign="top" style="background-color:#f3f3f3;border-top:15px solid #<?php echo Setting::get('theme.primary.400');?>;" bgcolor="#<?php echo Setting::get('theme.primary.400');?>"></td>
            <td align="left" valign="top" style="background-color:#f3f3f3;border-top:15px solid #323232;" bgcolor="#<?php echo Setting::get('theme.primary.400');?>"><h1>{{isset($title)?$title:""}}</h1></td>
          </tr>
          <tr>
            <td colspan="3" style="font-family:Helvetica,Arial,sans-serif;padding:40px;font-size:16px;line-height:2;background-color:#f3f3f3;" bgcolor="#f6f6f6" class="body-content">



            @yield('content')



            </td>
          </tr>
          <tr>
            <td colspan="2" width="230" height="15" style="background: #<?php echo Setting::get('theme.accent.400');?>;"></td>
            <td height="15" style="background: #323232;"></td>
          </tr>
        <tr>
            <td colspan="3" style="font-family:Helvetica,Arial,sans-serif;padding:20px;line-height:1.5;font-size: 13px;background-color:#323232;color:#ffffff;">
             <p>
               <?php echo Setting::get('site.name');?> &middot; <?php echo Setting::get('email.footer.slogan');?>
               <br/>

               <a style="color:#ffffff;" href="<?php echo Setting::get('email.footer.twitter'); ?>">Twitter</a> or <a style="color:#ffffff;" href="<?php echo Setting::get('email.footer.facebook');?>">Facebook</a>.
            
             </p>
           </td>
         </tr>
       </table>

     </td>
   </tr>
 </table>
 <!--/100% wrapper-->


</body>
</html>
