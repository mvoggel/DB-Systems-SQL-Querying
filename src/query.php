<?php
require "database.php";
$con = get_connection();
if (!$con) {
    report_error(mysqli_error($con));
    die();
}

function report_error($msg) {
  echo '<div style="width: 100%; background: #f2dede; padding: 10px; border-radius: 5px">' . $msg . '</div>';
}
?>
<style> <?php 
require "styles.css";?></style>

<!DOCTYPE html>
<html>
<head>
  <title>Fall '23 COMP6120 Term Project</title>
</head>

<body>

<div>
      <h1 style="margin-bottom: 0;"> Final Term Project - COMP6120 Database Systems I </h1>
      <p style = "padding-left: 25px;"> Populating and Querying Databases with SQL - Matthew Voggel </p> 
      <p style = "padding-left: 25px;"> This site is designed with two main pages: one to list all tables within the database mzv0068, and another is to query from it. 
        To do so, just click the button below to take you to the appropriate page.
      </p> 
    </div>


    <div style="margin-top: 20px; padding-left: 25px;">
      <button onclick="location.href='index.php';">All Tables List</button>
      <button class="btn" onclick="location.href='query.php';">Query Database</button>
    </div>
    <br>
    <hr> 
    <br>

<h2>Query Database Here</h2>
<h4>Link to Term Project SQL queries: <a href ="https://webhome.auburn.edu/~mzv0068/sql.txt"> See query txt file </a> </h4>
<h4>Table Names: &nbsp; <?php echo implode(", ", $tables); ?></h4>
<div style="margin: 5px">
  <form method="POST" action="query.php">
    <textarea id="query" name="query" style="font-family: consolas; font-size: larger; width: 100%; height: 150px; border: 1px solid gainsboro; padding: 5px"><?= stripslashes($_POST["query"])?></textarea>
    <br />
    <input type="submit"/> <button type="button" onclick="document.getElementById('query').value = ''";>Clear</button>
  </form>
</div>

<div style="padding-top: 15px">

<?php
  if (isset($_POST["query"])) {
    $query = stripcslashes($_POST["query"]);
    $q = strtolower($query);
    $forbidden = array("drop", "delete", "update", "create", "alter");
    foreach($forbidden as $key) {
      if(strpos($q, $key) !== false) {
        report_error("DROP, DELETE, UPDATE, CREATE and ALTER statements are disallowed.");
        die();
      }
    }

    if ($query !== "") {
      $result = execute_query($con, $query);
      if ($result == false) {
        report_error(mysqli_error($con));
        die();
      }

      ?>
      <table class="bordered">
        <thead>
        <?php
        $numFields = mysqli_num_fields($result);

        echo "<tr>";
        for($i = 0; $i < $numFields; $i++) {
          $field = mysqli_fetch_field_direct($result, $i);
          echo "<th>" . $field->name . "</th>";
        }
        echo "</tr>";
        ?>
        </thead>

        <?php
        $rows = array();
        while($resultRow = mysqli_fetch_assoc($result)) {
          $rows[] = $resultRow;
        }
        foreach($rows as $row) {
          echo "<tr>";
          foreach($row as $col) {
            echo "<td>" . $col . "</td>";
          }
          echo "</tr>";
        }

        mysqli_free_result($result);
      }
      ?>
    </table>
  <?php
  }
?>
</div>

</body>
</html>
<?php mysqli_close($con); ?>
