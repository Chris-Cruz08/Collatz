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

        .bar-graph {
            margin-top: 20px;
            width: 100%;
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
        }

        .bar {
            width: 20px;
            background-color: #4CAF50;
            margin-right: 5px;
        }

        .frequency {
            margin-top: 5px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <?php

    class CollatzCalculator {
        private function collatz($n) {
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

        public function calculateSingleNumber($singleNumber) {
            $result = $this->collatz($singleNumber);

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

        protected function calculateRange($start, $finish) {
            $results = array();

            for ($i = $start; $i <= $finish; $i++) {
                $result = $this->collatz($i);
                $results[] = array('number' => $i, 'maxValue' => $result['maxValue'], 'iterations' => $result['iterations']);
            }

            return $results;
        }

        public function calculateStatistics($start, $finish) {
            $rangeResults = $this->calculateRange($start, $finish);

            $histogram = array();
            foreach ($rangeResults as $result) {
                $iterations = $result['iterations'];
                if (!isset($histogram[$iterations])) {
                    $histogram[$iterations] = 1;
                } else {
                    $histogram[$iterations]++;
                }
            }

            return $histogram;
        }
    }

    // Child class inheriting from CollatzCalculator
    class CollatzStatisticsCalculator extends CollatzCalculator {
        public function calculateHistogram($start, $finish) {
            $histogram = $this->calculateStatistics($start, $finish);

            // Display histogram
            echo "<h2>Statistics (Histogram) for Collatz Conjecture Iterations:</h2>\n";
            echo "<table>\n";
            echo "<tr><th>Iterations</th><th>Frequency</th></tr>\n";
            foreach ($histogram as $iterations => $frequency) {
                echo "<tr><td>$iterations</td><td>$frequency</td></tr>\n";
            }
            echo "</table>\n";

            // Draw histogram
            echo '<h3>Graphical Representation:</h3>';
            echo '<div class="bar-graph">';
            foreach ($histogram as $iterations => $frequency) {
                echo '<div class="bar" style="height: ' . ($frequency * 20) . 'px;"></div>';
                echo '<div class="frequency">' . $frequency . '</div>';
            }
            echo '</div>';
        }
    }

    // HTML form handling
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $start = $_POST['start'];
        $finish = $_POST['finish'];

        $statisticsCalculator = new CollatzStatisticsCalculator();

        // Calculate and display statistics
        $statisticsCalculator->calculateHistogram($start, $finish);
    }

    ?>

    <h2>Statistics (Histogram) for Collatz Conjecture Iterations:</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Start: <input type="text" name="start"><br><br>
        Finish: <input type="text" name="finish"><br><br>
        <input type="submit" name="submit" value="Calculate">
    </form>
</div>

</body>
</html>
