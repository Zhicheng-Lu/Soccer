<?php
include("includes/connection.php");
include("includes/group_rank.php");
include("includes/team_modal/get_result.php");
$tournament = $_GET["tournament"];
if ($tournament == "champions_league") {
    $tournament_chinese = "冠军杯";
}
elseif ($tournament == "union_associations") {
    $tournament_chinese = "联盟杯";
}
else {
    $tournament_chinese = "优胜者杯";
}
$competition = $_GET["competition"];
$stage = $_GET["stage"];
$round = isset($_GET["round"])? $_GET["round"]:"";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>世界足球冠军联赛 - <?php echo $competition.' '.$tournament_chinese;?></title>
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

<style type="text/css">
    @media screen and (max-width: 1200px) {
        #header {
            font-size: 12px;
        }
    }
    @media screen and (max-width: 992px) {
        #header {
            font-size: 16px;
        }
    }
</style>

<body>
    <!-- header -->
    <header class="site-header">
        <div class="nav-bar">
            <div class="container">
                <div class="row">
                    <div class="col-120 d-flex flex-wrap justify-content-between align-items-center">
                        <div class="site-branding d-flex align-items-center">
                           <a class="d-block" href="index.php" rel="home"><img class="d-block" src="images/logo-black.png" alt="logo" style="height: 80px;"></a>
                        </div><!-- .site-branding -->

                        <!-- navigation bar -->
                        <nav class="site-navigation d-flex justify-content-end align-items-center">
                            <ul class="d-flex flex-column flex-lg-row justify-content-lg-end align-items-center">
                                <?php
                                $stages = array();
                                if ($competition >= 15) {
                                    array_push($stages, array("stage"=>"participants", "round"=>"", "chinese"=>"名额分配"));
                                    array_push($stages, array("stage"=>"qualifications", "round"=>"", "chinese"=>"预选赛"));
                                }
                                array_push($stages, array("stage"=>"groups", "round"=>"", "chinese"=>"小组赛"));
                                if ($tournament == "champions_league") {
                                    array_push($stages, array("stage"=>"knockout", "round"=>"1_16", "chinese"=>"1/16 决赛"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"1_8", "chinese"=>"1/8 决赛"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"1_4", "chinese"=>"1/4 决赛"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"semi_final", "chinese"=>"半决赛"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"final", "chinese"=>"决赛"));
                                }
                                else if ($tournament == "union_associations") {
                                    array_push($stages, array("stage"=>"knockout", "round"=>"1_32", "chinese"=>"1/32 决赛"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"1_16", "chinese"=>"1/16 决赛"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"1_8", "chinese"=>"1/8 决赛"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"1_4", "chinese"=>"1/4 决赛"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"semi_final", "chinese"=>"半决赛"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"final", "chinese"=>"决赛"));
                                }
                                else {
                                    array_push($stages, array("stage"=>"knockout", "round"=>"122", "chinese"=>"122 强"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"70", "chinese"=>"70 强"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"48", "chinese"=>"48 强"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"24", "chinese"=>"24 强"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"12", "chinese"=>"12 强"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"6", "chinese"=>"6 强"));
                                    array_push($stages, array("stage"=>"knockout", "round"=>"finals", "chinese"=>"联合决赛"));
                                }

                                foreach ($stages as $_stage) {
                                    $class = "";
                                    if ($_stage["stage"] == $stage) {
                                        if ($_stage["stage"] == "knockout") {
                                            if ($_stage["round"] == $round) $class = "current-menu-item";
                                        }
                                        else $class = "current-menu-item";
                                    }
                                    echo '
                                <li class="'.$class.'"><a id="header" href="new_competition.php?tournament='.$tournament.'&competition='.$competition.'&stage='.$_stage["stage"].($_stage["round"]==""? "":'&round='.$_stage["round"]).'">'.$_stage["chinese"].'</a></li>';
                                }
                                ?>
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
    </header>
    
    <?php
    include('new_competition_tabs/'.$stage.'.php');
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