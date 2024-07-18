<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compound Interest Calculator</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        .calculator {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .input-group {
            margin-bottom: 15px;
        }
        label {
            display: inline-block;
            width: 220px;
            font-weight: bold;
            color: #2c3e50;
        }
        input, select {
            width: 150px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        #results {
            margin-top: 20px;
            padding: 15px;
            background-color: #e8f4f8;
            border-radius: 4px;
        }
        #results h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        #chart-container {
            margin-top: 30px;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <h1>Compound Interest Calculator</h1>
    <div class="calculator">
        <div class="input-group">
            <label for="principal">Initial Investment ($):</label>
            <input type="number" id="principal" value="10000" min="0" step="100">
        </div>
        <div class="input-group">
            <label for="annualContribution">Annual Contribution ($):</label>
            <input type="number" id="annualContribution" value="1000" min="0" step="100">
        </div>
        <div class="input-group">
            <label for="interestRate">Annual Interest Rate (%):</label>
            <input type="number" id="interestRate" value="7" min="0" max="100" step="0.1">
        </div>
        <div class="input-group">
            <label for="years">Investment Period (Years):</label>
            <input type="number" id="years" value="30" min="1" max="100" step="1">
        </div>
        <div class="input-group">
            <label for="compoundFrequency">Compound Frequency:</label>
            <select id="compoundFrequency">
                <option value="1">Annually</option>
                <option value="2">Semi-annually</option>
                <option value="4">Quarterly</option>
                <option value="12">Monthly</option>
                <option value="365">Daily</option>
            </select>
        </div>
        <button onclick="updateChart()">Calculate</button>
        <div id="results"></div>
    </div>
    <div id="chart-container">
        <canvas id="chart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chart;

        function calculateCompoundInterest(principal, annualContribution, rate, years, frequency) {
            const periods = years * frequency;
            const ratePerPeriod = rate / 100 / frequency;
            const contributionPerPeriod = annualContribution / frequency;
            
            let balance = principal;
            const balances = [balance];
            
            for (let i = 1; i <= periods; i++) {
                balance = balance * (1 + ratePerPeriod) + contributionPerPeriod;
                if (i % frequency === 0) {
                    balances.push(balance);
                }
            }
            
            return balances;
        }

        function updateChart() {
            const principal = parseFloat(document.getElementById('principal').value);
            const annualContribution = parseFloat(document.getElementById('annualContribution').value);
            const interestRate = parseFloat(document.getElementById('interestRate').value);
            const years = parseInt(document.getElementById('years').value);
            const frequency = parseInt(document.getElementById('compoundFrequency').value);

            const balances = calculateCompoundInterest(principal, annualContribution, interestRate, years, frequency);
            const labels = Array.from({length: years + 1}, (_, i) => i);

            if (chart) {
                chart.destroy();
            }

            const ctx = document.getElementById('chart').getContext('2d');
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Balance',
                        data: balances,
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Compound Interest Growth',
                            font: {
                                size: 18,
                                weight: 'bold'
                            }
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Years'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Balance ($)'
                            },
                            ticks: {
                                callback: function(value, index, values) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Display results
            const totalContributions = principal + (annualContribution * years);
            const totalInterest = balances[balances.length - 1] - totalContributions;
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = `
                <h3>Results:</h3>
                <p><strong>Final Balance:</strong> $${balances[balances.length - 1].toLocaleString(undefined, {maximumFractionDigits: 2})}</p>
                <p><strong>Total Contributions:</strong> $${totalContributions.toLocaleString(undefined, {maximumFractionDigits: 2})}</p>
                <p><strong>Total Interest Earned:</strong> $${totalInterest.toLocaleString(undefined, {maximumFractionDigits: 2})}</p>
            `;
        }

        updateChart();
    </script>
</body>
</html>