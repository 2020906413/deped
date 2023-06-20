<?php 
include_once('../data/admin_session.php');//check if naay session otherwise e return sa login
include_once('../include/header.php'); ?>
<?php include_once('../include/banner.php'); ?>

  <nav class="navbar navbar-inverse" style="margin-top:-18px;">
    <div class="container-fluid">
     
      <ul class="nav navbar-nav">
        <li>
          <a href="index.php"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a>
        </li>
     
        <li class="active">
          <a href="item.php"><span class="glyphicon glyphicon-object-align-vertical"></span> Item
          </a>
        </li>
        
        <li>
          <a href="employee.php"><span class="glyphicon glyphicon-user"></span> Employee</a>
        </li>

        <li>
          <a href="position.php"><span class="glyphicon glyphicon-tasks"></span> Position</a>
        </li>

        <li>
          <a href="office.php"><span class="glyphicon glyphicon-home"></span> Office</a>
        </li>

        <li>
          <a href="request.php"><span class="glyphicon glyphicon-tags"></span> Request</a>
        </li>

        <li>
          <a href="report.php"><span class="glyphicon glyphicon-list-alt"></span> Report</a>
        </li>
      </ul>
       <ul class="nav navbar-nav navbar-right">
         <li class="dropdown">
            <a class="dropdown-toggle" id="admin-account" data-toggle="dropdown" href="#">
            </a>
            <ul class="dropdown-menu">
              <li><a href="#modal-changepass" data-toggle="modal">Change Password</a></li>
              <li><a href="../data/admin_logout.php">Logout</a></li>
            </ul>
          </li>
      </ul>
     </div>
    </nav>



<html>
<head>
    <title>Dashboard</title>
    <style>
        .chart-container {
            display: flex;
            justify-content: space-between;
            width: 30%;
            margin-bottom: 5px;
        }
        canvas {
            width: 100px;
            height: 100px;
        }
        #itemBarChart {
            width: 200px;
            height: 200px;
        }
        #itemAreaChart {
            width: 250px;
            height: 150px;
        }
        #itemDonutChart {
            width: 80px;
            height: 80px;
        }
        #itemPieChart {
            width: 80px;
            height: 80px;
        }

    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Dashboard</h1>
    <div class="chart-container">
        <canvas id="itemBarChart"></canvas>
        <canvas id="itemAreaChart"></canvas>

    </div>
    <div class="chart-container">
        <canvas id="itemDonutChart"></canvas>
        <canvas id="itemPieChart"></canvas>
    </div>
</body>
</html>

    <?php
    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "deped";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL queries
    $sqlItemAmount = "SELECT item_name, item_amount FROM tbl_item";
    $sqlItemCondition = "SELECT con_id, COUNT(*) AS count FROM tbl_item GROUP BY con_id";
    $sqlItemCategory = "SELECT cat_id, COUNT(*) AS count FROM tbl_item GROUP BY cat_id";
    $sqlItemOffice = "SELECT item_id, COUNT(*) AS count FROM tbl_item GROUP BY item_id";

    // Execute the queries
    $resultItemAmount = $conn->query($sqlItemAmount);
    $resultItemCondition = $conn->query($sqlItemCondition);
    $resultItemCategory = $conn->query($sqlItemCategory);
    $resultItemOffice = $conn->query($sqlItemOffice);

    // Check if any rows are returned for Item Amount query
    if ($resultItemAmount->num_rows > 0) {
        // Output data of each row
        $chartDataAmount = [];
        while ($row = $resultItemAmount->fetch_assoc()) {
            // Collect data for the bar chart and area chart
            $chartDataAmount[] = [
                'label' => $row['item_name'],
                'value' => $row['item_amount'],
            ];
        }

        // Generate bar chart for Item Amount
        echo "<script>";
        echo "var ctxBar = document.getElementById('itemBarChart').getContext('2d');";
        echo "var chartDataBar = " . json_encode($chartDataAmount) . ";";
        echo "var labelsBar = chartDataBar.map(data => data.label);";
        echo "var valuesBar = chartDataBar.map(data => data.value);";
        echo "var chartBar = new Chart(ctxBar, {";
        echo "    type: 'bar',";
        echo "    data: {";
        echo "        labels: labelsBar,";
        echo "        datasets: [{";
        echo "            label: 'Item Amount',";
        echo "            data: valuesBar,";
        echo "            backgroundColor: 'rgba(54, 162, 235, 0.5)',";
        echo "            borderColor: 'rgba(54, 162, 235, 1)',";
        echo "            borderWidth: 1";
        echo "        }]";
        echo "    },";
        echo "    options: {";
        echo "        scales: {";
        echo "            y: {";
        echo "                beginAtZero: true";
        echo "            }";
        echo "        }";
        echo "    }";
        echo "});";
        echo "</script>";

        // Generate area chart for Item Amount
        echo "<script>";
        echo "var ctxArea = document.getElementById('itemAreaChart').getContext('2d');";
        echo "var chartDataArea = " . json_encode($chartDataAmount) . ";";
        echo "var labelsArea = chartDataArea.map(data => data.label);";
        echo "var valuesArea = chartDataArea.map(data => data.value);";
        echo "var chartArea = new Chart(ctxArea, {";
        echo "    type: 'line',";
        echo "    data: {";
        echo "        labels: labelsArea,";
        echo "        datasets: [{";
        echo "            label: 'Item Amount',";
        echo "            data: valuesArea,";
        echo "            backgroundColor: 'rgba(75, 192, 192, 0.5)',";
        echo "            borderColor: 'rgba(75, 192, 192, 1)',";
        echo "            borderWidth: 1";
        echo "        }]";
        echo "    },";
        echo "    options: {";
        echo "        scales: {";
        echo "            y: {";
        echo "                beginAtZero: true";
        echo "            }";
        echo "        }";
        echo "    }";
        echo "});";
        echo "</script>";
    } else {
        echo "<p>No results found for Item Amount.</p>";
    }

    // Check if any rows are returned for Item Condition query
    if ($resultItemCondition->num_rows > 0) {
        // Output data of each row
        $chartDataCondition = [];
        while ($row = $resultItemCondition->fetch_assoc()) {
            // Collect data for the donut chart
            $chartDataCondition[] = [
                'label' => $row['con_id'],
                'value' => $row['count'],
            ];
        }

        // Generate donut chart for Item Condition
        echo "<script>";
        echo "var ctxDonut = document.getElementById('itemDonutChart').getContext('2d');";
        echo "var chartDataDonut = " . json_encode($chartDataCondition) . ";";
        echo "var labelsDonut = chartDataDonut.map(data => data.label);";
        echo "var valuesDonut = chartDataDonut.map(data => data.value);";
        echo "var chartDonut = new Chart(ctxDonut, {";
        echo "    type: 'doughnut',";
        echo "    data: {";
        echo "        labels: labelsDonut,";
        echo "        datasets: [{";
        echo "            data: valuesDonut,";
        echo "            backgroundColor: [";
        echo "                'rgba(255, 99, 132, 0.5)',";
        echo "                'rgba(54, 162, 235, 0.5)',";
        echo "                'rgba(255, 206, 86, 0.5)',";
        echo "                'rgba(75, 192, 192, 0.5)',";
        echo "                'rgba(153, 102, 255, 0.5)',";
        echo "                'rgba(255, 159, 64, 0.5)'";
        echo "            ],";
        echo "            borderColor: [";
        echo "                'rgba(255, 99, 132, 1)',";
        echo "                'rgba(54, 162, 235, 1)',";
        echo "                'rgba(255, 206, 86, 1)',";
        echo "                'rgba(75, 192, 192, 1)',";
        echo "                'rgba(153, 102, 255, 1)',";
        echo "                'rgba(255, 159, 64, 1)'";
        echo "            ],";
        echo "            borderWidth: 1";
        echo "        }]";
        echo "    },";
        echo "    options: {";
        echo "        responsive: true";
        echo "    }";
        echo "});";
        echo "</script>";
    } else {
        echo "<p>No results found for Item Condition.</p>";
    }

    // Check if any rows are returned for Item Category query
    if ($resultItemCategory->num_rows > 0) {
        // Output data of each row
        $chartDataCategory = [];
        while ($row = $resultItemCategory->fetch_assoc()) {
            // Collect data for the pie chart
            $chartDataCategory[] = [
                'label' => $row['cat_id'],
                'value' => $row['count'],
            ];
        }

        // Generate pie chart for Item Category
        echo "<script>";
        echo "var ctxPie = document.getElementById('itemPieChart').getContext('2d');";
        echo "var chartDataPie = " . json_encode($chartDataCategory) . ";";
        echo "var labelsPie = chartDataPie.map(data => data.label);";
        echo "var valuesPie = chartDataPie.map(data => data.value);";
        echo "var chartPie = new Chart(ctxPie, {";
        echo "    type: 'pie',";
        echo "    data: {";
        echo "        labels: labelsPie,";
        echo "        datasets: [{";
        echo "            data: valuesPie,";
        echo "            backgroundColor: [";
        echo "                'rgba(255, 99, 132, 0.5)',";
        echo "                'rgba(54, 162, 235, 0.5)',";
        echo "                'rgba(255, 206, 86, 0.5)',";
        echo "                'rgba(75, 192, 192, 0.5)',";
        echo "                'rgba(153, 102, 255, 0.5)',";
        echo "                'rgba(255, 159, 64, 0.5)'";
        echo "            ],";
        echo "            borderColor: [";
        echo "                'rgba(255, 99, 132, 1)',";
        echo "                'rgba(54, 162, 235, 1)',";
        echo "                'rgba(255, 206, 86, 1)',";
        echo "                'rgba(75, 192, 192, 1)',";
        echo "                'rgba(153, 102, 255, 1)',";
        echo "                'rgba(255, 159, 64, 1)'";
        echo "            ],";
        echo "            borderWidth: 1";
        echo "        }]";
        echo "    },";
        echo "    options: {";
        echo "        responsive: true";
        echo "    }";
        echo "});";
        echo "</script>";
    } else {
        echo "<p>No results found for Item Category.</p>";
    }

    // Check if any rows are returned for Item Office query
    if ($resultItemOffice->num_rows > 0) {
        // Output data of each row
        $chartDataOffice = [];
        while ($row = $resultItemOffice->fetch_assoc()) {
            // Collect data for the bar chart
            $chartDataOffice[] = [
                'label' => $row['item_id'],
                'value' => $row['count'],
            ];
        }

        // Generate bar chart for Item Office
        echo "<script>";
        echo "var ctxBarOffice = document.getElementById('itemBarChartOffice').getContext('2d');";
        echo "var chartDataBarOffice = " . json_encode($chartDataOffice) . ";";
        echo "var labelsBarOffice = chartDataBarOffice.map(data => data.label);";
        echo "var valuesBarOffice = chartDataBarOffice.map(data => data.value);";
        echo "var chartBarOffice = new Chart(ctxBarOffice, {";
        echo "    type: 'bar',";
        echo "    data: {";
        echo "        labels: labelsBarOffice,";
        echo "        datasets: [{";
        echo "            label: 'Item Office',";
        echo "            data: valuesBarOffice,";
        echo "            backgroundColor: 'rgba(75, 192, 192, 0.5)',";
        echo "            borderColor: 'rgba(75, 192, 192, 1)',";
        echo "            borderWidth: 1";
        echo "        }]";
        echo "    },";
        echo "    options: {";
        echo "        scales: {";
        echo "            y: {";
        echo "                beginAtZero: true";
        echo "            }";
        echo "        }";
        echo "    }";
        echo "});";
        echo "</script>";
    } else {
        echo "<p>No results found for Item Office.</p>";
    }

    // Close the connection
    $conn->close();
    ?>

</body>

<?php require_once('../include/footer.php'); ?>
</html>


