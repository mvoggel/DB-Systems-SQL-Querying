<?php
require "database.php";
$con = get_connection();
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



  <h2><img src="Auburn_Tigers_logo.png" alt="Auburn Logo" style="width:40px;height:40px;"> &nbsp;List of All Tables in Database mzv0068</h2>
  <?php
  foreach($tables as $table_name) { ?>
    <h3>Table Name: &nbsp; <?= $table_name ?> </h3>
    <table class="bordered">
      <thead>
      <?php
      $query = "SELECT * FROM ". $table_name;
      $result = execute_query($con, $query);
      if(!$result) {
        die("One or more tables could not be loaded. Check your query: " . mysqli_error($con));
      }
      $num_fields = mysqli_num_fields($result);

      echo "<tr>";
      for($i = 0; $i < $num_fields; $i++) {
        $field = mysqli_fetch_field_direct($result, $i);
        echo "<th>" . $field->name . "</th>";
      }
      echo "</tr>";


      ?>
      </thead>

      <?php
      $rows = array();
      while($result_row = mysqli_fetch_assoc($result)) {
        $rows[] = $result_row;
      }
      foreach($rows as $row) {
        echo "<tr>";
        foreach($row as $col) {
          echo "<td>" . $col . "</td>";
        }
        echo "</tr>";
      }

      mysqli_free_result($result);

      ?>

    </table>

    <br><br>
  <?php
  }
  ?>
</body>
</html>
<?php mysqli_close($con); ?>