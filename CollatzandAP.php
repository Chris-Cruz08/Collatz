<?php

class ArithmeticProgression {
    public static function generateAP($start, $finish, $difference) {
        $result = array();
        for ($i = $start; $i <= $finish; $i += $difference) {
            $result[] = $i;
        }
        return $result;
    }
}

class CollatzCalculator {
    public static function collatz($n) {
        $maxValue = $n;
        $iterations = 0;
        $sequence = [];

        while ($n != 1) {
            $sequence[] = $n;
            if ($n % 2 == 0) {
                $n /= 2;
            } else {
                $n = 3 * $n + 1;
            }
            $maxValue = max($maxValue, $n);
            $iterations++;
        }
        $sequence[] = 1;

        return array('maxValue' => $maxValue, 'iterations' => $iterations, 'sequence' => $sequence);
    }

    public static function calculateRange($start, $finish) {
        $results = array();

        for ($i = $start; $i <= $finish; $i++) {
            $result = self::collatz($i);
            $results[] = array('number' => $i, 'maxValue' => $result['maxValue'], 'iterations' => $result['iterations'], 'sequence' => $result['sequence']);
        }

        return $results;
    }
}

// HTML form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form for arithmetic progression is submitted
    if (isset($_POST['arithmetic_progression'])) {
        // Calculate arithmetic progression
        $start = $_POST['start'];
        $finish = $_POST['finish'];
        $difference = $_POST['difference'];

        $apSequence = ArithmeticProgression::generateAP($start, $finish, $difference);

        echo "<h2>Arithmetic Progression:</h2>\n";
        echo "<table style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>\n";
        echo "<tr><th style='border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;'>Number</th></tr>\n";
        foreach ($apSequence as $number) {
            echo "<tr><td style='border: 1px solid #ddd; padding: 8px; text-align: left;'>$number</td></tr>\n";
        }
        echo "</table>\n";
    }
    // Check if the form for Collatz conjecture is submitted
    elseif (isset($_POST['submit'])) {
        // Calculate Collatz conjecture for a range of numbers
        $start = $_POST['start'];
        $finish = $_POST['finish'];

        // Test with a single number
        if (isset($_POST['single_number'])) {
            $singleNumber = $_POST['single_number'];
            $result = CollatzCalculator::collatz($singleNumber);

            echo "<h2>First test the program with a single number $singleNumber:</h2>\n";
            echo "<table style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>\n";
            echo "<tr><th style='border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;'>Collatz sequence</th><td>";
            echo implode(' -> ', $result['sequence']) . "</td></tr>\n";
            echo "<tr><th style='border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;'>Max value</th><td>" . $result['maxValue'] . "</td></tr>\n";
            echo "<tr><th style='border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;'>Iterations</th><td>" . $result['iterations'] . "</td></tr>\n";
            echo "</table>\n";
        }

        $rangeResults = CollatzCalculator::calculateRange($start, $finish);

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

        echo "<table style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>\n";
        echo "<tr><th style='border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;'>Max Iterations</th><th></th></tr>\n";
        echo "<tr><td style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Max value:</td><td>" . $maxIterationsNumber['maxValue'] . "</td></tr>\n";
        echo "<tr><td style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Iterations:</td><td>" . $maxIterationsNumber['iterations'] . "</td></tr>\n";
        echo "</table>\n";

        echo "<table style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>\n";
        echo "<tr><th style='border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;'>Min Iterations</th><th></th></tr>\n";
        echo "<tr><td style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Max value:</td><td>" . $minIterationsNumber['maxValue'] . "</td></tr>\n";
        echo "<tr><td style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Iterations:</td><td>" . $minIterationsNumber['iterations'] . "</td></tr>\n";
        echo "</table>\n";
    }
}
?>

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

        h2 {
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
    <h2>Calculate Arithmetic Progression</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Start: <input type="text" name="start"><br><br>
        Finish: <input type="text" name="finish"><br><br>
        Difference: <input type="text" name="difference"><br><br>
        <input type="submit" name="arithmetic_progression" value="Generate AP">
    </form>

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
