        <div class="sliders">
            <img src="images/sliders/<?php echo $_GET["tab"]; ?>.jpg" style="width: 100%;">
        </div>
    </header><!-- .site-header -->

    <style type="text/css">
        .hamburger-menu span {
            background: white;
        }
        @media screen and (min-width: 992px) {
            .site-navigation ul li a {
                color: white;
            }
        }
    </style>

    <?php
    $tournament = $_GET["tab"];
    $champions = array();
    $sql = 'SELECT C.competition, C.team1, T.team_chinese_name, T.team_nationality FROM '.$tournament.' AS C LEFT JOIN teams AS T ON C.team1=T.team_name WHERE C.round="champion" ORDER BY C.competition ASC';
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        array_push($champions, array("competition"=>$row["competition"], "team_name"=>$row["team1"], "chinese_name"=>$row["team_chinese_name"]));
    }
    ?>
    <!-- all the champions for the current competition -->
    <div class="section">
        <div class="container">
            <div class="col-120">
                <div class="row">
                    <?php
                    foreach ($champions as $champion) {
                        echo '
                    <div class="col-xxl-30 col-xl-30 col-lg-40 col-md-60 col-120" style="margin-bottom: 20px;">
                        <div style="width: 90px; display: inline-block;">
                            <a href="competition.php?tournament='.$tournament.'&competition='.$champion["competition"].'&stage='.($champion["competition"]<=14? "groups":"participants").'" target="_blank" style="text-decoration: underline; color: blue;">第 '.$champion["competition"].' 届：</a>
                        </div>
                        <a href="javascript:void(0)" style="color: black;" onclick="open_modal(\''.str_replace("'", "\'", $champion["team_name"]).'\', '.$champion["competition"].')">
                            <img class="badge-small" src="images/teams_small/'.$champion["team_name"].'.png">
                            '.$champion["chinese_name"].'
                        </a>
                    </div>';
                    }

                    // new competition
                    echo '
                    <div class="col-xxl-30 col-xl-30 col-lg-40 col-md-60 col-120" style="margin-bottom: 20px;">
                        <div style="width: 90px; display: inline-block;">
                            <a href="new_competition.php?tournament='.$tournament.'&competition='.($champion["competition"] + 1).'&stage=participants" target="_blank" style="text-decoration: underline; color: blue;">第 '.($champion["competition"] + 1).' 届：</a>
                        </div>
                        ？？？
                    </div>';
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    include("includes/team_modal/modal.php");
    ?>