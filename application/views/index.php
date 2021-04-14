<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url() ?>static/index.css">
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script type="text/javascript">
<?php   if($this->session->userdata("leads") != NULL){ 
?>
            window.onload = function() {

                var chart = new CanvasJS.Chart("chartContainer", {
                    exportEnabled: false,
                    animationEnabled: true,
                    title: {
                        text: "Leads and Clients | <?= $this->session->userdata("from_date") ?> - <?= $this->session->userdata("to_date") ?>"
                    },
                    legend:{
                        cursor: "pointer",
                        itemclick: explodePie
                    },
                    data: [{
                        type: "pie",
                        showInLegend: true,
                        toolTipContent: "{name}: <strong>{y}%</strong>",
                        indexLabel: "{name} - {y}%",
                        dataPoints: [
<?php                       foreach($this->session->userdata("leads") AS $lead){
                                echo "{y:".$lead["number_of_leads"].", name: '".$lead["client_name"]."'},";
                            }
?>
                        ]
                    }]
                });
                chart.render();
            }

            function explodePie (e) {
                if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded){
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
                }
                else {
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
                }
                e.chart.render();
            }
<?php   }
?>
    </script>
    <title>PHP | Leads and Clients</title>
</head>
<body>
    <header>
        <h2>Report Dashboard</h2>
    </header>
    <section>
        <?php  $attributes = array("role" => "form"); echo form_open("setDateRange", $attributes);  ?>
        <?php  if($this->session->userdata("date_error") != NULL){ echo "<p class='error'>".$this->session->userdata("date_error")."</p>"; $this->session->unset_userdata("date_error");  }    ?>
            <label>From: <input type="date" name="from_date"></label>
            <label>To: <input type="date" name="to_date"></label>
            <button type="submit">Update</button>
        </form>
        <h4>List of all Customers and Number of Leads</h4>
        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Number of Leads</th>
                </tr>
            </thead>
            <tbody>
<?php   if($this->session->userdata("leads") != NULL){ 
            foreach($this->session->userdata("leads") AS $lead){
?>
                <tr>
                    <td><?= $lead["client_name"] ?></td>
                    <td><?= $lead["number_of_leads"] ?></td>
                </tr>
<?php       }
        }
        else{
?>
            <tr>
                <td colspan=2>No leads to display</td>
            </tr>
<?php   }    
?>
            </tbody>
        </table>
        <div id="chartContainer"></div>
    </section>
    <footer>Village88 | &copy <?= date("Y") ?></footer>
</body>
</html>