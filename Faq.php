<!DOCTYPE html>
 <!-- this is the freequently asked questions page. Here, I have tried to use Accordion jquery function to fold and unfold the sections -->
<html>

<head>
<meta charset="utf-8">
  <title>Faq's</title>
   <script src="<?php echo BASE; ?> /includes/js/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="includes/styles/faq_style.css"/>

    <script>
    //function to execute the accordion style
    $(function()  {
      $('#accordion').accordion();
    } )  ;

    </script>

</head>

<body>

  <div id="header">

        <h1>Community Connect</h1>

        <?php include('includes/navigation.inc.php'); ?>
</div>

<div class="content leftmenu">
        <div class="colright">
                <div class="col1">
                        <!-- Left Column start -->
                        <?php include('includes/left_column.inc.php'); ?>
                        <!-- Left Column end -->
                </div>
<div class="col2">
        <?php 
        if(isset($msg))
            {
                    echo '<div class="success" >'.$msg.'</div>';
            } elseif (isset($err))
            {
                echo '<div class="error">'.$err.'</div>';
            }
?>
    <div class="dashboard_sub_section">  
        <?php include('includes/subnavigation.inc.php'); ?>
     </div>
    
<h2>All questions about this website</h2>

                 <div id = "accordion">
                        <h3>Section 1:</h3>
                        <div class = "acc">
                         <p class = "para">
                           Details for the section 1 goes here... <br/>
                           jdgfbkdjfnknhkgfnhkfgn             <br/>
                           fgnhkfjgnnnnnnnnnnnnnnnnnn         <br/>
                           g nkjnkfnglkfmhlfgmh           <br/>
                           ngkmnlgmhnlfkgnhlfk          <br/>
                           nggmlfkmlfmlnkgm           <br/>
                           nlgkmnlmlg              <br/>
                         </p>
                        </div>
                        <h3>Section 2:</h3>
                        <div>
                          <p class = "para">
                             Details for the section 2 goes here... <br/>
                             jdgfbkdjfnknhkgfnhkfgn             <br/>
                             fgnhkfjgnnnnnnnnnnnnnnnnnn         <br/>
                             g nkjnkfnglkfmhlfgmh           <br/>
                             ngkmnlgmhnlfkgnhlfk          <br/>
                             nggmlfkmlfmlnkgm           <br/>
                             nlgkmnlmlg              <br/>
                         </p>
                        </div>
                           <h3>Section 3:</h3>
                        <div>
                          <p class = "para">
                             Details for the section1 3goes here... <br/>
                             jdgfbkdjfnknhkgfnhkfgn             <br/>
                             fgnhkfjgnnnnnnnnnnnnnnnnnn         <br/>
                             g nkjnkfnglkfmhlfgmh           <br/>
                             ngkmnlgmhnlfkgnhlfk          <br/>
                             nggmlfkmlfmlnkgm           <br/>
                             nlgkmnlmlg              <br/>
                         </p>
                        </div>
                           <h3>Section4:</h3>
                        <div>
                          <p class = "para">
                             Details for the section 4 goes here... <br/>
                             jdgfbkdjfnknhkgfnhkfgn             <br/>
                             fgnhkfjgnnnnnnnnnnnnnnnnnn         <br/>
                             g nkjnkfnglkfmhlfgmh           <br/>
                             ngkmnlgmhnlfkgnhlfk          <br/>
                             nggmlfkmlfmlnkgm           <br/>
                             nlgkmnlmlg              <br/>
                         </p>
                  </div>
             </div>
        </div>
    </div>
</div>
<?php include('includes/footer.inc.php'); ?>
</body>
</html>