    <?php
    include("includes/connection.php");
    $team_name = $_POST["team_name"];
    $competition = $_POST["competition"];
    $sql = 'SELECT * FROM teams WHERE team_name="'.$team_name.'"';
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $team_name_chinese = $row["team_chinese_name"];
        $nationality = $row["team_nationality"];
    }
    $sql = "SELECT country_chinese_name, country_continent FROM countries WHERE country_name=\"".$nationality."\"";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $nationality_chinese = $row["country_chinese_name"];
        $continent = $row["country_continent"];
    }
    if ($continent == "Europe") $continent_chinese = "欧洲";
    if ($continent == "Asia") $continent_chinese = "亚洲";
    if ($continent == "South America") $continent_chinese = "南美洲";
    if ($continent == "North America") $continent_chinese = "北美洲";
    if ($continent == "Africa") $continent_chinese = "非洲";
    if ($continent == "Oceania") $continent_chinese = "大洋洲";
    if ($continent == "Antarctica") $continent_chinese = "南极洲";

    echo '
            <div class="modal-header">
                <div class="row" style="width: 100%;">
                    <div class="col-lg-40 col-md-60 col-120">
                        <p style="font-size: 24px;"><b>'.$team_name_chinese.'</b> <img class="d-md-none" src="images/teams_small/'.$team_name.'.png" style="width: 24px; height: 24px;"></p>
                        <p style="font-size: 14px;"><b>英文名称：</b>'.$team_name.'</p>
                        <p style="font-size: 14px;"><b>国籍：</b>'.$nationality_chinese.' <img src="images/countries_small/'.$nationality.'.png" style="width: 14px; height: 14px;"></p>
                        <p style="font-size: 14px;"><b>大洲：</b>'.$continent_chinese.' <img src="images/continents_small/'.$continent.'.png" style="width: 14px; height: 14px;"></p>
                        <p style="font-size: 14px;"><b>类别：</b>'.(($nationality == $team_name)? "国家队" : "俱乐部").'</p>
                    </div>
                    <div class="col-lg-80 col-md-60 d-none d-md-block">
                        <img src="images/teams/'.$team_name.'.png" style="width: 200px; height: 200px;">
                    </div>
                </div>
                <span class="close" onclick="close_modal(\''.str_replace("'", "\'", $team_name).'_'.$competition.'\')">&times;</span>
            </div>';

    include("includes/group_rank.php");
    include("body.php");

    echo '
            <div class="modal-footer justify-content-center">
                <button class="col-lg-60 col-md-90 col-120" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; margin-top: 20px; margin-bottom: 20px; cursor: pointer;" onclick="open_url(\''.str_replace("'", "\'", $team_name).'\')">
                    查看“'.$team_name_chinese.'”的更多信息
                </button>
            </div>';
    ?>