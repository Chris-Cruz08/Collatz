<!DOCTYPE html>
<html>
<head>
    <title>Collatz Conjecture Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"], input[type="submit"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <?php

    function collatz($n) {
        $maxValue = $n;
        $iterations = 0;

        while ($n != 1) {
            if ($n % 2 == 0) {
                $n /= 2;
            } else {
                $n = 3 * $n + 1;
            }
            $maxValue = max($maxValue, $n);
            $iterations++;
        }

        return array('maxValue' => $maxValue, 'iterations' => $iterations);
    }

    // Test with a single number
    $singleNumber = isset($_POST['single_number']) ? $_POST['single_number'] : null;
    if ($singleNumber !== null) {
        $result = collatz($singleNumber);

        echo "<h2>First test the program with a single number $singleNumber:</h2>\n";
        echo "<table>\n";
        echo "<tr><th>Collatz sequence</th><td>";
        $n = $singleNumber;
        $sequence = [];
        while ($n != 1) {
            $sequence[] = $n;
            if ($n % 2 == 0) {
                $n /= 2;
            } else {
                $n = 3 * $n + 1;
            }
        }
        $sequence[] = 1;
        echo implode(' -> ', $sequence) . "</td></tr>\n";
        echo "<tr><th>Max value</th><td>" . $result['maxValue'] . "</td></tr>\n";
        echo "<tr><th>Iterations</th><td>" . $result['iterations'] . "</td></tr>\n";
        echo "</table>\n";
    }

    // Function to calculate values for a range of numbers
    function calculateRange($start, $finish) {
        $results = array();

        for ($i = $start; $i <= $finish; $i++) {
            $result = collatz($i);
            $results[] = array('number' => $i, 'maxValue' => $result['maxValue'], 'iterations' => $result['iterations']);
        }

        return $results;
    }

    // HTML form handling
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $start = $_POST['start'];
        $finish = $_POST['finish'];

        $rangeResults = calculateRange($start, $finish);

        // Finding numbers with max and min iterations
        $minIterations = PHP_INT_MAX;
        $maxIterations = PHP_INT_MIN;
        $minIterationsNumber = null;
        $maxIterationsNumber = null;

        foreach ($rangeResults as $result) {
            if ($result['iterations'] < $minIterations) {
                $minIterations = $result['iterations'];
                $minIterationsNumber = $result;
            }
            if ($result['iterations'] > $maxIterations) {
                $maxIterations = $result['iterations'];
                $maxIterationsNumber = $result;
            }
        }

        echo "<h2>Numbers with max and min iterations:</h2>\n";
        echo "<p>Start: " . $start . "</p>\n";
        echo "<p>Finish: " . $finish . "</p>\n";

        echo "<table>\n";
        echo "<tr><th>Max Iterations</th><th></th></tr>\n";
        echo "<tr><td>Max value:</td><td>" . $maxIterationsNumber['maxValue'] . "</td></tr>\n";
        echo "<tr><td>Iterations:</td><td>" . $maxIterationsNumber['iterations'] . "</td></tr>\n";
        echo "</table>\n";

        echo "<table>\n";
        echo "<tr><th>Min Iterations</th><th></th></tr>\n";
        echo "<tr><td>Max value:</td><td>" . $minIterationsNumber['maxValue'] . "</td></tr>\n";
        echo "<tr><td>Iterations:</td><td>" . $minIterationsNumber['iterations'] . "</td></tr>\n";
        echo "</table>\n";
    }
    ?>

    <h2>Calculate Collatz Conjecture for a Range of Numbers</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Test with a single number: <input type="text" name="single_number"><br><br>
        Start: <input type="text" name="start"><br><br>
        Finish: <input type="text" name="finish"><br><br>
        <input type="submit" name="submit" value="Calculate">
    </form>
</div>

</body>
</html>
