        


<?php
    //inisialisai curl untuk digunakan
    $curl = curl_init();
    //set URL
    curl_setopt($curl, CURLOPT_URL, "https://data.covid19.go.id/public/api/update.json");
    //kembalikan nilai menjadi string
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //eksekusi curl dan masukan data ke dalam variabel output
    $output = curl_exec($curl);

    curl_close($curl);

    //simpan output ke dalam variabel data
    $data = json_decode($output, true);

?>


    <!DOCTYPE html>
    <html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
            integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    </head>
    
    <body>
        <div class="container mt-4">
            <nav class="navbar navbar-light bg-light mb-4">
                <span class="navbar-brand mb-0 h1"> Grafik Corona</span>
            </nav>
      
            <!-- diagram garis akan kita tampilkan disini -->
            <canvas id="myChart2"></canvas>
                        
        </div>
    
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
        <script>
            //mebuat chart
            var myChart2 = new Chart(
                //masukan chart ke element canvas dengan id myChart2
                document.getElementById('myChart2'),
                {
                    //tipe chart yg digunakan adalah line chart atau diagram garis
                    type: 'line',
                    data: {
                        //data labels akan diganti dengan data api pada step berikutnya
                        labels: [
                            "1-12-2021",
                            "2-12-2021",
                            "3-12-2021",
                            "4-12-2021",
                            "5-12-2021",
                            "6-12-2021",
                            "7-12-2021",
                            "8-12-2021",
                            "9-12-2021",
                            "10-12-2021"
                        ],
                        datasets: [{
                            label: 'Jumlah Sebaran Corona Setiap Hari',
                            //data akan diganti dengan data api pada step berikutnya
                            data: [
                                100,200,300,400,500,600,700,800,900,1000
                            ],
                            //line akan diwarnai dengan warna merah
                            backgroundColor: [
                            'rgb(255, 99, 132)',
                            ],
                            hoverOffset: 4
                        }]
                    }
                }
            );
        </script>
    </body>
    
    </html>