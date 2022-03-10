<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<?php
    include 'config.php';

    $sql = "SELECT DISTINCT category FROM websites";
    $catArray = [];
    $result = mysqli_query($con, $sql);
    while ($row = $result->fetch_assoc()) {
        $catArray[] = $row['category'];
    }
?>
    <body>
        <div class="container">
            <?php include 'navigation.php' ?>
            <div class="wrapper">
                <div class="contain">
                    <h1>Submit a Link</h1>
                    <p class="intro">Know a website that's full of wonder and mystery?</p>
                    <form method="POST" action="submit.php">
                        <label>Webpage Title</label> <input type="text" name="titleInput" id="titleInput" required><br>
                        <label>Webpage URL</label> <input type="url" name="urlInput" id="urlInput" required><br><span id="dupe">This item is a duplicate.</span>
                        <label>Webpage Description</label><textarea name="descrInput" id="descrInput" required></textarea><br>
                        <label>Webpage Category</label> <select name="categories" id="categories"><option></option></select>
                        <br><br>

                        <input type="text" id="honeypot" name="honeypot">
                        <input type="submit" id="submit" name="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
        <style>
            label {
                font-weight:bold;
                display:block;
            }
            form {
                margin-left:20px;
            }
            </style>
    </body>
<?php

 $stmt = $con->prepare("SELECT url FROM websites");
 $stmt->execute();
 $result = $stmt->get_result();

 $websites = [];
 while ($row = $result->fetch_assoc()) {
     $websites[] = $row['url'];
 }
 $stmt->close();
 ?>

 


<script>
console.log('test');
    var sitesArr = <?php echo json_encode($websites); ?>;
    var urlInput = document.getElementById('urlInput');
    var dupe = document.getElementById('dupe');
    var submitBtn = document.getElementById('submit');

    urlInput.addEventListener("change", checkIfDupe);
    

    function checkIfDupe() {
        var value = urlInput.value;
        var valueSlash = value.slice(0, -1);
        console.log(valueSlash);
        // check if array includes a value
    if (sitesArr.includes(value) || sitesArr.includes(valueSlash)) {
        dupe.style.display = "block";
        dupe.style.color = "red";
        dupe.style.fontWeight = "bold";
        submitBtn.disabled = true;
    } else {
        console.log('no dupe');
        dupe.style.display = "none";
        submitBtn.disabled = false;
    }
    }


    console.log(sitesArr);

    // this part grabs the array of unique categories from the PHP and passes it to JS
    var catArr = <?php echo json_encode($catArray); ?>;

    // this removes null values from the array
    const results = catArr.filter(element => {
    return element !== null;
    });
    console.log(results);
    // this dynamically creates a selectbox for all available categories
    for (let i = 0; i < results.length; i++) {
        $('#categories').append('<option value="' + results[i] + '" name="categories">' + results[i] + '</option>');
    }
    
    </script>
</html>