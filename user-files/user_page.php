<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
include '../user-files-functions/user_page_functions.php';
$activePage = basename($_SERVER['PHP_SELF'], ".php");
$user_data = check_login($con);
$name =  $_SESSION['name'];
$account_id =  $user_data['account_id'];

$emp_id = get_empID($account_id);
$monthly_eligibility = eligibility_checker_month($emp_id);
$annual_eligibility = eligibility_checker_year($emp_id);

$chart_ATT = get_ATT($emp_id);
$chart_QA = get_QA($emp_id);
$chart_CPH = get_CPH($emp_id);

$emp_name = $user_data['name'];
$month = date('F');
$year = date('Y');
$rank = check_rank($emp_name, $month, $year);
$goals = get_goals($emp_id);
$da_count = get_count_da($emp_id);

$today_perf = performance_today($emp_id);
$arr = explode("||", $today_perf);
$QA_today = $arr[0];
$CPH_today = $arr[1];
?>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"> </script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <title> User Page</title>
  <style>
    .desc {
      font-size: 15px;
    }

    svg {
      width: auto;
      height: auto;
    }
  </style>
</head>
<?php include_once '../includes/user_navbar.php' ?>

<body>

  <section class="m-3">
    <div class="container-fluid pt-3">
      <div class="row">
        <div class="col-lg-3">
          <div class="card box mb-3 shadow-sm rounded bg-white profile-box text-center" style="min-height:35rem;">
            <?php
            $query = "SELECT * FROM accounts WHERE account_id='$account_id'";
            $result = mysqli_query($con, $query);

            if ($result) {
              if (mysqli_num_rows($result)) {
                while ($row = mysqli_fetch_array($result)) {
                  $image = $row['picture'];
                }
              }
            }
            ?>
            <div class="p-5">
              <img src="../img/<?php echo $image; ?>" class="img-fluid" style="height: 180px; width: 180px;" alt="Responsive image" />
            </div>
            <h5 class="font-weight-bold text-dark mb-1 mt-0"> <?php echo $name ?> </h5>
            <div class="p-2 border-top border-bottom d-flex flex-row justify-content-center">
              <p class="h5 mb-0 font-weight-bold text-gray-800"> 100 </p>
              <p class="text-muted mb-0 mr-2 ml-2"> <code> ATT </code> </p>
              <p class="h5 mb-0 font-weight-bold text-gray-800"> <?php echo $QA_today ?> </p>
              <p class="text-muted mb-0 mr-2 ml-2"> <code> QA </code> </p>
              <p class="h5 mb-0 font-weight-bold text-gray-800"> <?php echo $CPH_today ?> </p>
              <p class="text-muted mb-0 ml-2"> <code> CPH </code> </p>
            </div>
            <div class="p-3">
              <div class="d-flex align-items-top mb-2">
                <p class="mb-0 text-muted">Rank</p>
                <p class="font-weight-bold text-dark mb-0 mt-0 ml-auto"> # <?php echo $rank ?> </p>
              </div>
              <div class="d-flex align-items-top mb-2">
                <p class="mb-0 text-muted"> DA </p>
                <p class="font-weight-bold text-dark mb-0 mt-0 ml-auto"> <?php echo $da_count ?> </p>
              </div>
              <div class="d-flex align-items-top">
                <p class="mb-0 text-muted"> Goals </p>
                <p class="font-weight-bold text-dark mb-0 mt-0 ml-auto"> <?php echo $goals ?> </p>
              </div>

            </div>
          </div>
        </div>
        <div class="col-lg-9" style="height:35rem;">
          <div class="card" style="height:35rem;">
            <div class="card-header bg-transparent">
              <h6 class="m-0 font-weight-bold text-primary"> Performance <code> (daily score) </code> </h6>
            </div>
            <div class="card-body">
              <canvas id="performance_chart" height="106rem"></canvas>
              <script>
                new Chart(document.getElementById("performance_chart"), {
                  type: 'line',
                  data: {
                    labels: [<?php chart_get_days() ?>],
                    datasets: [{
                      data: [<?php chart_daily_score_QA($emp_id) ?>],
                      label: "QA",
                      borderColor: "#3e95cd",
                      fill: false
                    }, {
                      data: [<?php chart_daily_score_CPH($emp_id) ?>],
                      label: "CPH",
                      borderColor: "#8e5ea2",
                      fill: false
                    }, {
                      data: [<?php chart_daily_score_ATT($emp_id) ?>],
                      label: "ATT",
                      borderColor: "#3cba9f",
                      fill: false
                    }, {
                      data: [],
                      label: "PERF",
                      borderColor: "#e8c3b9",
                      fill: false
                    }]
                  },
                  options: {
                    title: {
                      display: true,
                      text: 'Performance Per Day Chart',
                      responsive: true
                    }

                  }
                });
              </script>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- <div class="col-lg-3" style="height: 23.5rem;">
          <div class="card" style="height: 23.5rem;">
            <div class="card-header bg-transparent">
              <h6 class="m-0 font-weight-bold text-primary"> Performance <code> (daily score) </code> </h6>
            </div>
            <div class="card-body">
              <div class="chart-container" style="height: 23.5rem;">
                <canvas id="doughnut-chart" height="200rem"></canvas>
                <script>
                  new Chart(document.getElementById("doughnut-chart"), {
                    type: 'doughnut',
                    data: {
                      labels: ["QA", "CPH", "ATT"],
                      datasets: [{
                        backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f"],
                        data: [2478, 5267, 734]
                      }]
                    },
                    options: {
                      responsive: true
                    }
                  });
                </script>
              </div>
            </div>
          </div>
        </div> -->
        <div class="col-lg-4" style="height: 23.5rem;">
          <div class="card" style="height: 23.5rem;">
            <div class="card-header bg-transparent">
              <h6 class="m-0 font-weight-bold text-primary"> Reward Eligibility <code> (daily score) </code> </h6>
            </div>
            <div class="card-body">
              <p class="desc mb-0 text-muted mb-2"><span class="text-primary  me-1"> <strong> Rewards Eligibility </strong> <br> This Month:
                  <?php echo $monthly_eligibility ?> <br> This Year: <?php echo $annual_eligibility ?> </span>
              </p>

              <div class="accordion mt-4" id="accordionExample">
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Rewards Eligibility
                    </button>
                  </h2>
                  <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                      <p class="text1"> <strong> Achieved </strong> - An employee with a performance comment of 'Achieved' has a performance score between 2 to 3.49. An employee with this performance status is eligible for the rewards set by the manager. </p>
                      <p class="text1"> <strong> Surpassed </strong> - An employee with a performance comment of 'Surpassed' has a performance score of 3.50 and higher. An employee with this performance status is eligible for more than the rewards set by the manager. </p>
                    </div>
                  </div>
                </div>
              </div>

            </div>

          </div>
        </div>
        <div class="col-lg-5">
          <div class="card" style="height: 23.5rem;">
            <div class="card-body">

              <div class="svg-container">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 866.81006 443.0676" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <path d="M828.33492,244.95227l-2.71537,21.6217-6.73908,53.70394h-135.38272c1.05718-12.03676,2.50247-29.36207,4.04646-48.06508,.15218-1.86793,.31188-3.74756,.46397-5.63886,3.87162-47.01432,8.10823-100.21632,8.10823-100.21632l17.79852,3.31574,34.53207,6.42112,14.07147,2.61514,37.68114,7.01655c18.78727,3.49074,31.7102,30.69301,28.13532,59.22608Z" fill="#f2f2f2" />
                  <polygon points="725.93018 79.35764 742.57875 108.4915 710.5803 131.16867 696.93018 95.35764 725.93018 79.35764" fill="#ffb6b6" />
                  <circle cx="704.73531" cy="65.67545" r="41.24406" fill="#ffb6b6" />
                  <path d="M701.77389,74.8959c-3.27492-4.9842-1.98953-7.59113-4.73126-9.42283-.00005-.00005-1.10331-.7371-8.99943-1.15733h0c-1.76783-12.72387-3.9747-14.26689-3.97475-14.26694-2.54344-1.77835-6.31806-1.20052-8.66632-.11286-4.96648,2.3004-4.39069,7.26947-7.73471,8.22036-4.61947,1.31356-13.02411-6.09155-13.85211-14.56892-.67539-6.91516,3.77981-13.63146,5.54692-13.27617,1.75207,.35229,5.94686-5.00298,8.2118-4.92038,1.54677,.05641,7.86668-2.69052,9.56681-2.47576,1.85668,.23458,7.97849,3.42925,8.23359,2.69936,1.77276-5.07201,1.25174-6.47929,2.75595-7.93028,2.66541-2.57115,7.02574-.78067,13.86612,.18057,14.07429,1.97776,16.5307-2.66296,23.74347,.48255,3.64355,1.58896,6.69229,5.8742,12.64108,14.3798,8.31932,11.89498,12.47896,17.84248,12.50565,24.77939,.02721,7.0744-3.3985,6.62532-4.90327,17.09838-1.67502,11.65826,1.88393,16.98908-2.00412,20.77661-3.00495,2.92729-9.11284,3.62215-12.32198,1.05303-5.49439-4.39856,1.69602-15.0123-4.38413-22.76671-3.73224-4.75997-11.23802-6.87793-14.67632-4.52502-3.88767,2.66043-1.74599,10.46929-4.47987,11.20979-2.3411,.63413-5.77242-4.58808-6.34311-5.45663Z" fill="#2f2e41" />
                  <path d="M797.8501,237.33762c-1.15039,19.66992-5.71973,25.87988-7.32031,49.10986-.34961,5.1001-.55957,11.02002-.55957,18.08008,0,1.7998,.00977,3.54004,.00977,5.24023,0,3.61963-.06934,7.04004-.49023,10.50977-.31934,2.77002-.86914,5.56006-1.7793,8.5h-88.83008c-.00977-.02002-.00977-.04004-.02051-.06006-.51953-2.25977-.84961-4.41992-1.09961-6.48975-.08008-.66016-.15039-1.31006-.20996-1.9502-.08008-.75-.15039-1.48975-.21973-2.21973-.27051-2.91016-.54004-5.66016-1.18066-8.29004-.85938-3.63037-2.41992-7.05029-5.62012-10.3501-1.30957-1.3501-2.61914-2.3999-3.89941-3.31982-.25-.17041-.49023-.34033-.74023-.51025-3.54004-2.43018-6.88965-4.18018-9.79004-9.14014-.0498-.06982-.08984-.1499-.12988-.21973-.5-.87012-1.7998-3.15039-2.74023-6.18994-.99023-3.16016-1.58984-7.13037-.50977-11.18994,2.57031-9.62012,12.08984-10.58008,19.87012-20.30029,.83984-1.0498,1.66992-2.20996,2.45996-3.5,3.51953-5.72021,4.46973-10.75977,5.58984-16.75,.17969-.97021,.33984-1.93018,.46973-2.85986,.10059-.7002,.19043-1.39014,.26074-2.07031,.61914-5.70996,.31934-10.62988-.41016-15.02979-2.37988-14.41016-9.40039-23.33008-4.44043-36.26025,1.56055-4.05957,3.77051-7.10986,6-9.71973,4.71973-5.53027,9.57031-9.12012,8.73047-16.25977-.85059-7.2002-6.31055-8.19043-6.91992-14.48047-.31055-3.17969,.72949-6.52979,2.67969-9.81982v-.00977c6.24023-10.59033,21.82031-20.34033,31.71973-20.03027,1.83008,.05029,3.4502,.45996,4.7998,1.25,4.89062,2.87988,2.85059,9.16992,10.34082,17.99023,5.56934,6.5498,11.86914,9.16992,14.25977,10.40967,9.46973,4.94043,17.16992,19.51025,22.39941,38.14014,.04004,.14014,.08008,.27979,.12012,.43018,3.23047,11.66992,5.51074,24.91992,6.64062,38.35986,.47949,5.68994,.75977,11.41016,.81934,17.06982,.05078,4.52002-.0498,8.44043-.25977,11.93018Z" fill="#6c63ff" />
                  <g>
                    <polygon points="731.33057 304.72776 729.52002 309.7678 728.38037 312.92747 725.73975 320.27756 722.68018 328.77756 703.01025 328.77756 706.31006 320.27756 707.84033 316.3176 710.38037 309.7678 714.64014 298.7678 731.33057 304.72776" fill="#ffb6b6" />
                    <path d="M790.84033,167.82737c-.0498,.65039-.12012,1.37012-.19043,2.15039-.7793,7.93994-2.62012,22.1499-5.30957,38.35986-1.41992,8.5498-3.08008,17.66016-4.9502,26.70996-4.01953,19.52979-9.01953,38.76025-14.66992,51.3999-1.00977,2.28027-2.0498,4.34033-3.11035,6.16016-.80957,1.39014-1.66016,2.75977-2.5498,4.10986-2.87988,4.43018-6.11035,8.63037-9.45996,12.55029-.12988,.16992-.28027,.33008-.42969,.5-.54004,.63965-1.10059,1.27002-1.66016,1.8999-2.74023,3.08984-5.54004,5.96973-8.25,8.60986-3.2998,3.22021-6.48047,6.07031-9.33008,8.5h-31.94043l-.12988-.06006-4.59961-2.04004s1.30957-1.54004,3.5-4.44971c.4502-.59033,.92969-1.24023,1.4502-1.9502,.63965-.87012,1.33008-1.83008,2.06934-2.87012,1.54004-2.17969,3.29004-4.73975,5.16992-7.63965,3.64062-5.61035,7.79004-12.51025,11.98047-20.5,.48047-.93018,.96973-1.87012,1.45996-2.82031,.00977-.02979,.03027-.06006,.04004-.08984,.83008-1.62012,1.65039-3.29004,2.46973-4.99023,6.9502-14.45996,8.94043-26.45996,9.51074-30.34961,.7998-5.5,1.90918-13.19043,1.60938-21.65039-.2002-5.57959-1.00977-11.47998-2.93945-17.33984v-.00977c-.40039-1.22998-.85059-2.45996-1.36035-3.68018-.91016-2.18994-1.99023-4.3501-3.27051-6.47998-5.64941-9.37988-10.23926-9.77002-13.88965-19.99023-1.08008-3.02979-3.53027-9.86963-2.78027-17.08984v-.00977c.09082-.89014,.24023-1.79004,.44043-2.69043v-.00977c.0498-.25,.12012-.50977,.19043-.75977,.56934-2.20996,1.50977-4.40039,2.93945-6.48047,2.79004-4.03955,6.59961-6.43994,10.00977-7.86963l.01074-.01025c3.93945-1.63965,7.33984-1.98975,8-2.0498,14.60938-1.2998,25.93945,10.97998,32.12988,17.68994,2.38965,2.58984,4.58008,4.76025,6.58008,6.52979,15.56934,13.90039,19.83984,5.02002,20.94922,.43018,.16992-.70996,.26074-1.31982,.31055-1.72021Z" fill="#6c63ff" />
                    <path d="M790.89014,167.30784c0,.0498-.00977,.21973-.0498,.5,.01953-.15039,.03027-.30029,.04004-.44043,0-.02979,0-.0498,.00977-.05957Z" fill="#6c63ff" />
                  </g>
                  <path d="M711.47021,378.3176H175.42041c-4.98047,0-9.04004,4.0498-9.04004,9.03027,0,4.98975,4.05957,9.03955,9.04004,9.03955H711.47021c4.97949,0,9.03027-4.0498,9.03027-9.03955,0-4.98047-4.05078-9.03027-9.03027-9.03027Z" fill="#e6e6e6" />
                  <path d="M592.51025,424.99778H294.37012c-4.97998,0-9.02979,4.0498-9.02979,9.02979s4.0498,9.04004,9.02979,9.04004h298.14014c4.97998,0,9.04004-4.06006,9.04004-9.04004s-4.06006-9.02979-9.04004-9.02979Z" fill="#e6e6e6" />
                  <polygon points="235.06941 81.27476 268.85306 67.05703 283.34352 96.76248 247.84188 111.25294 235.06941 81.27476" fill="#ffb6b6" />
                  <polygon points="235.06941 81.27476 268.85306 67.05703 283.34352 96.76248 247.84188 111.25294 235.06941 81.27476" opacity=".1" />
                  <path d="M239.87213,69.95512l33.32807,38.39973s-23.18474,73.17685-46.36949,79.69756-65.93162,38.39973-65.93162,38.39973l-53.61472-34.77712,26.08284-38.39973s63.03352-86.21827,79.69756-86.21827c16.66403,0,26.80736,2.89809,26.80736,2.89809Z" fill="#6c63ff" />
                  <path d="M194.84299,320.2779H117.26107l-4.90504-6.55697-20.02583-26.7711-4.60796-6.15845s-17.38856-31.87902-13.76594-60.85995c3.62262-28.98093,41.29783-35.50164,41.29783-35.50164h1.55049l46.12313,40.52979-2.75318,37.71872,14.60637,24.27153,20.06205,33.32807Z" fill="#2f2e41" />
                  <polygon points="133.14986 320.2779 112.35603 320.2779 112.35603 289.48566 133.14986 320.2779" fill="#2f2e41" />
                  <path d="M581.64421,273.53165l-2.58651,13.41818-6.41927,33.32807h-128.95789c1.00701-7.46988,2.38371-18.22178,3.85442-29.82865,.14496-1.15922,.29708-2.32569,.44195-3.49942,3.68789-29.17657,7.72344-62.19314,7.72344-62.19314l16.95386,2.05771,32.89328,3.98488,13.40368,1.62293,35.89291,4.35439c17.89569,2.16632,30.20533,19.04774,26.80011,36.75505Z" fill="#f2f2f2" />
                  <path d="M764.14438,320.2779h-69.94541c1.06503-1.25341,1.66635-1.97793,1.66635-1.97793l20.96775-3.46324,43.20324-7.12937s1.97068,5.09342,4.10808,12.57055Z" fill="#2f2e41" />
                  <polygon points="467.45495 95.76626 497.87302 138.78423 444.81392 140.36878 439.11853 99.94198 467.45495 95.76626" fill="#a0616a" />
                  <polygon points="467.45495 95.76626 497.87302 138.78423 444.81392 140.36878 439.11853 99.94198 467.45495 95.76626" opacity=".1" />
                  <path id="uuid-ae755ff6-bb09-410e-86b1-7d1b81a37005-89" d="M317.44601,184.89638c-3.90299-11.25913-12.8761-18.37174-20.04107-15.88668-7.16497,2.48507-9.80758,13.62542-5.90169,24.88804,1.49853,4.52036,4.05781,8.61612,7.46374,11.94459l17.13959,47.49655,22.1173-8.51819-19.24476-45.91882c.61482-4.72396,.08913-9.52647-1.53311-14.0055Z" fill="#a0616a" />
                  <path d="M523.34731,162.97667l-.04173,43.84082-4.07898,16.19317-8.39004,33.30631-6.10768,30.63286-4.55003,22.82248-1.62293,8.15089c-1.22449,.81871-2.44173,1.60117-3.65888,2.3547h-105.97603c-6.02079-3.61536-9.38979-6.42652-9.38979-6.42652,0,0,1.66639-1.56499,3.57189-4.07907,3.21689-4.2457,7.10757-11.2011,4.72391-17.84501-.49992-1.39112-.63033-3.08648-.47821-4.97747,.97088-12.47627,14.05577-33.57447,14.05577-33.57447l-2.70971-31.31387-1.18823-13.72243,8.37769-66.9101,27.30728-15.87424,4.71449-.45614,46.53611-2.51407,19.23434,1.10845,.87664,.23915,18.79409,39.04457Z" fill="#ccc" />
                  <g>
                    <polygon points="525.41397 320.2779 502.22923 320.2779 501.20763 309.77232 501.18587 309.56218 507.75009 309.11297 524.82716 307.9465 525.12415 314.23531 525.35604 319.06075 525.41397 320.2779" fill="#ffb6b6" />
                    <path d="M531.12143,173.57645l9.73934,57.14315-7.948,56.23024-1.23891,8.75221-2.18807,15.45405-.67376,4.75291,.02176,.51438,.16663,3.85451h-27.53188l-1.01444-10.50559-.08685-.94192-2.07221-21.51831-.03617-.36226-4.39782-45.63046,8.62176-32.98031,.63767-2.42722v-.00716l3.75119-56.46939-2.90535-25.66256-.02892-.23915c.05793,.03626,.1087,.07252,.16663,.1087,.15221,.09419,.30433,.18838,.4492,.28983,16.92485,10.73748,27.01025,29.57509,26.56821,49.64435Z" fill="#ccc" />
                  </g>
                  <path d="M415.74779,152.37881l-10.58745-10.95064s-22.66445,10.21757-23.79416,15.88619c-1.12971,5.66862-41.14649,88.92555-41.14649,88.92555l-17.40981-46.49871-22.66197,15.13029s18.1839,72.19186,35.18976,75.58099,74.00173-85.44603,74.00173-85.44603l6.40838-52.62764Z" fill="#ccc" />
                  <path d="M845.64014,286.44749H21.16016c-11.66992,0-21.16016,9.5-21.16016,21.16992s9.49023,21.16016,21.16016,21.16016H845.64014c11.66992,0,21.16992-9.48975,21.16992-21.16016s-9.5-21.16992-21.16992-21.16992Z" fill="#e6e6e6" />
                  <path d="M518.52213,221.93974l-1.60291,74.80242c-.15517,7.24107-6.06962,13.03015-13.31236,13.03015h-137.60256c-7.07149,0-12.90955-5.52763-13.29556-12.58857l-4.08931-74.80242c-.41702-7.62814,5.65604-14.04226,13.29556-14.04226h143.29478c7.46556,0,13.4723,6.13683,13.31236,13.60068Z" fill="#3f3d56" />
                  <circle cx="296.38479" cy="66.40952" r="32.92181" fill="#ffb6b6" />
                  <polygon points="241.32117 152.55077 244.94379 191.67503 269.57758 290.93471 248.29569 298.39388 212.34024 190.22598 207.37412 146.75458 241.32117 152.55077" fill="#ffb6b6" />
                  <path d="M231.52213,221.93974l-1.60291,74.80242c-.15517,7.24107-6.06962,13.03015-13.31236,13.03015H79.0043c-7.07149,0-12.90955-5.52763-13.29556-12.58857l-4.08931-74.80242c-.41702-7.62814,5.65604-14.04226,13.29556-14.04226H218.20977c7.46556,0,13.4723,6.13683,13.31236,13.60068Z" fill="#3f3d56" />
                  <ellipse cx="273.50003" cy="298.7779" rx="26" ry="11.5" fill="#ffb6b6" />
                  <path d="M190.24228,158.53237l14.85273-79.15844s41.29783-5.79619,42.74687,7.96976-1.03544,81.04198-1.03544,81.04198l-56.56416-9.85329Z" fill="#6c63ff" />
                  <path d="M283.09522,71.58597s20.36536-7.26838,28.95593-11.17911c8.59056-3.91073,25.65708,23.36637,26.03673,8.96042,.37965-14.40595-5.51755-20.44717-5.51755-20.44717,0,0-1.4726-31.51507-33.2233-27.57494,0,0,6.98375-15.76734-15.94076-18.20547-22.92451-2.43813-58.58934,52.92097-55.39531,61.19409,3.19403,8.27312,7.02295,15.11285-2.03888,23.95865-9.06183,8.8458-26.03033,58.76917-13.11998,72.20319,12.91035,13.43402,13.7575,19.26357,4.91188,27.62122-8.84562,8.35765-29.75425,26.53032-7.14719,29.68515,22.60706,3.15483-2.06828,.79851,14.7856-14.0263,16.85389-14.82481,41.44742-14.21933,32.26457-38.00455-9.18284-23.78522,6.77112-57.86925,4.77485-63.03995-1.99627-5.1707,4.4726-38.00496,4.4726-38.00496,0,0,15.05504-2.21189,16.1808,6.85974Z" fill="#2f2e41" />
                  <g>
                    <circle cx="451.19104" cy="75.49881" r="35.83777" fill="#a0616a" />
                    <path d="M454.05121,27.31743c-1.037,.60534-2.42604-.31053-2.75855-1.46432-.33251-1.1538,.05482-2.37882,.43892-3.51648l1.93363-5.72716c1.37137-4.06183,2.82687-8.26559,5.79693-11.35717,4.48283-4.66624,11.6071-5.85331,18.02037-4.99332,8.2359,1.1044,16.36189,5.56405,20.19135,12.93866,3.82945,7.37462,2.19911,17.66485-4.72046,22.26586,9.8619,11.30338,13.2996,23.9006,12.7568,38.89155-.5428,14.99095-16.87927,28.78752-27.53282,39.34806-2.37913-1.4422-4.54204-8.20095-3.23373-10.65626,1.3083-2.45531-.5662-5.29986,1.05409-7.56146,1.62029-2.2616,2.97587,1.33954,1.33779-.90922-1.03366-1.41901,3.00067-4.68353,1.46421-5.53283-7.43161-4.10794-9.9033-13.37111-14.57083-20.46464-5.62991-8.55611-15.26549-14.35039-25.46249-15.31167-5.61717-.52953-11.55014,.42955-16.15432,3.69062-4.60418,3.26106-7.5849,9.08853-6.51724,14.62867-2.76507-2.80756-4.14147-6.92204-3.62233-10.82826,.51914-3.90621,2.92243-7.51837,6.32483-9.50626-2.06881-6.84142-.29652-14.71105,4.50527-20.00518,4.80179-5.29413,24.28048-4.39244,31.29074-2.99911l-.54217-.93008Z" fill="#2f2e41" />
                    <path d="M455.13987,53.47472c9.28487,1.0023,15.9888,9.04506,21.64971,16.47254,3.26282,4.28103,6.68059,9.00871,6.59868,14.39076-.08282,5.4414-3.72095,10.10859-5.45967,15.26538-2.84199,8.42894-.07214,18.45744,6.69308,24.23289-6.68479,1.26852-13.91123-3.74373-15.06525-10.44924-1.34341-7.80603,4.57394-15.34005,3.87313-23.22978-.61741-6.95094-6.09506-12.30052-10.75187-17.49772-4.65681-5.19721-9.03036-12.09377-6.88799-18.73508l-.64983-.44975Z" fill="#2f2e41" />
                  </g>
                  <g>
                    <path id="uuid-aec20554-f6ec-4c5b-9d97-717cd04239bf-90" d="M593.94738,290.97732c-9.19677,5.23093-13.95249,14.21631-10.62254,20.06865,3.32996,5.85234,13.48327,6.3548,22.68262,1.12078,3.70344-2.04037,6.86441-4.93798,9.21841-8.4504l38.6872-22.65576-11.01865-17.92815-36.96889,24.23796c-4.22349,.22844-8.33082,1.46525-11.97815,3.60692Z" fill="#ffb6b6" />
                    <path d="M724.9347,124.84081c-1.15613-.45818-11.30029-4.29973-20.56133,.77222-10.63836,5.8263-12.28141,19.12643-12.85772,23.79133-1.33125,10.77601,2.59074,13.19133,3.41866,24.10068,1.54899,20.4102,5.63336-2.8739-.88939,6.44247-2.2578,3.22481-25.31723,53.19804-38.03446,62.98648-24.73342,19.03731-40.43943,32.44418-40.43943,32.44418l17.93045,19.47744s28.33331-7.54935,53.37617-23.81018c25.68825-16.67995,38.53244-25.0199,48.46938-41.33509,.85574-1.40497,21.33113-36.17595,10.33482-74.6244-2.50819-8.76995-7.10385-24.83851-20.74714-30.24513Z" fill="#6c63ff" />
                  </g>
                  <path d="M817.52213,221.93974l-1.60291,74.80242c-.15517,7.24107-6.06962,13.03015-13.31236,13.03015h-137.60256c-7.07149,0-12.90955-5.52763-13.29556-12.58857l-4.08931-74.80242c-.41702-7.62814,5.65604-14.04226,13.29556-14.04226h143.29478c7.46556,0,13.4723,6.13683,13.31236,13.60068Z" fill="#3f3d56" />
                </svg>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  </div>
  </div>


</body>

<?php include '../includes/script.php' ?>

</html>
