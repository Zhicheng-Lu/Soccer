<!DOCTYPE html>
<html lang="en">
<head>
    <title>世界足球冠军联赛</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="images/ball.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/swiper.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="js/custom.js"></script>
    <link rel="stylesheet" href="css/mycss.css">
    <link rel="stylesheet" href="css/grid.css">
</head>

<?php
include("includes/connection.php");
include("includes/group_rank.php");
include("includes/team_modal/get_result.php");
if (!isset($_GET["tab"])) $color = "black";
else if ($_GET["tab"] == "champions_league" || $_GET["tab"] == "union_associations" || $_GET["tab"] == "winners_cup") $color = "white";
else $color = "black";
?>
<body>
    <!-- header -->
    <header class="site-header">
        <div class="nav-bar">
            <div class="container">
                <div class="row">
                    <div class="col-120 d-flex flex-wrap justify-content-between align-items-center">
                        <div class="site-branding d-flex align-items-center">
                           <a class="d-block" href="index.php" rel="home"><img class="d-block" src="images/logo-<?php echo $color; ?>.png" alt="logo" style="height: 80px;"></a>
                        </div><!-- .site-branding -->

                        <!-- navigation bar -->
                        <nav class="site-navigation d-flex justify-content-end align-items-center">
                            <ul class="d-flex flex-column flex-lg-row justify-content-lg-end align-items-center">
                                <li class="<?php if (!isset($_GET["tab"])) echo "current-menu-item";?>"><a href="index.php">首页</a></li>
                                <li class="<?php if ($_GET["tab"]=="champions_league") echo "current-menu-item";?>"><a href="index.php?tab=champions_league">冠军杯</a></li>
                                <li class="<?php if ($_GET["tab"]=="union_associations") echo "current-menu-item";?>"><a href="index.php?tab=union_associations">联盟杯</a></li>
                                <li class="<?php if ($_GET["tab"]=="winners_cup") echo "current-menu-item";?>"><a href="index.php?tab=winners_cup">优胜者杯</a></li>
                                <li class="<?php if ($_GET["tab"]=="team") echo "current-menu-item";?>"><a href="index.php?tab=team">球队主页面</a></li>
                                <li class="<?php if ($_GET["tab"]=="history") echo "current-menu-item";?>"><a href="index.php?tab=history">历史交锋记录</a></li>
                                <li class="<?php if ($_GET["tab"]=="rank") echo "current-menu-item";?>"><a href="index.php?tab=rank">排名</a></li>
                            </ul>
                        </nav><!-- .site-navigation -->

                        <div class="hamburger-menu d-lg-none">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div><!-- .hamburger-menu -->
                    </div><!-- .col -->
                </div><!-- .row -->
            </div><!-- .container -->
        </div><!-- .nav-bar -->
    
    <?php
    if (!isset($_GET["tab"])) include("index_tabs/home.php");
    else if ($_GET["tab"] == "team" & !isset($_GET["team_name"])) include("index_tabs/team_general.php");
    else if ($_GET["tab"] == "team" & isset($_GET["team_name"])) include("index_tabs/team_specific.php");
    else if ($_GET["tab"] == "history") {
        if (!isset($_GET["continent1"]) & !isset($_GET["country1"]) & !isset($_GET["team1"])) {
            include("index_tabs/history_general.php");
        }
        else {
            include("index_tabs/history_specific.php");
        }
    }
    else if ($_GET["tab"] == "rank") include("index_tabs/rank.php");
    else include("index_tabs/competition.php");
    ?>

    <script type='text/javascript' src='js/jquery.js'></script>
    <script type='text/javascript' src='js/jquery.collapsible.min.js'></script>
    <script type='text/javascript' src='js/swiper.min.js'></script>
    <script type='text/javascript' src='js/jquery.countdown.min.js'></script>
    <script type='text/javascript' src='js/circle-progress.min.js'></script>
    <script type='text/javascript' src='js/jquery.countTo.min.js'></script>
    <script type='text/javascript' src='js/jquery.barfiller.js'></script>
    <script type='text/javascript' src='js/custom.js'></script>
</body>
</html>