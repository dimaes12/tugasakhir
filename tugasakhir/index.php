<?php

require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\Classification\NaiveBayes;

// This assumes that you have placed the Firebase credentials in the same directory
// as this PHP file.
$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/sekripsi-8d736-firebase-adminsdk-zizo5-fda0c20450.json');

$firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    // The following line is optional if the project id in your credentials file
    // is identical to the subdomain of your Firebase project. If you need it,
    // make sure to replace the URL with the URL of your project.
    ->withDatabaseUri('https://sekripsi-8d736.firebaseio.com/')
    ->create();
$database = $firebase->getDatabase();
$reference = $database->getReference('path/to/child/location'); //mengambil data dari firebase

$snapshot = $reference->getSnapshot();
$value = $snapshot->getValue(); //pembuatan variable untuk melakukan tampilan data yang ingin diambil
//$value = $reference->getValue();

$getNew = $database->getReference('PositiveNegative')
    ->getSnapshot(); //digunakan untuk menampilkan data dari tabel positif negatif
$titleNew = $database->getReference('BrowserHistory')
    ->getSnapshot(); //digunakan untuk menampilkan data dari tabel BrowserHistory

$valueBroHis = $titleNew->getValue(); //untuk mengambil data dari tabel BrowserHistory hanya title nya saja
$valuePosNeg = $getNew->getValue(); //untuk mengambil semua data dari table yang ada di positifnegatif 

unset($valuePosNeg[0]); //digunakan untuk pemanggilan data dari table positifnegati dan di mulai dari array ke 1
unset($valueBroHis[0]); //digunakan untuk pemanggilan data dari table Browhis dan di mulai dari array ke 1

$valuePosNeg = array_values($valuePosNeg); //digunakan untuk menyimpan array ke dalam variable 
$valueBroHis = array_values($valueBroHis); //digunakan untuk menyimpan array ke dalam variable


// print_r($valueBroHis);
foreach ($valuePosNeg as $key) {
    $kata[] = $key['B'];
    switch ($key['C']) {
        case 'negative':
            $labels[] = -1;
            break;
                            //digunakan sebagai penentu apabila data yang dihitung hasilnya -1 maka kata tersebut negatif
        default:
            $labels[] = 1;
            break;
    }
}

foreach ($valueBroHis as $key) {
    if ($key['history_title'] == null) {
        $title[] = 'bijak';
        //misalkan ada judul maka dia diganti dengan kata bijak dan kata yg kosong termasuk kategori kata positif
    }else{
        $title[] = $key['history_title']; //mengambil data title dari table browhis
    }
}
    $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer()); //memanggil library class dari token
    $vectorizer->fit($kata); //memetakan template array, array keberapa
    $vectorizer->transform($kata); //mengubah kata ke jumlah kata
    $vectorizer->transform($title); //mengubah title ke jumlah title

    $classifier = new NaiveBayes(); //memanggil library class naive bayes
    $classifier->train($kata, $labels); //menghitung semua datasesuai rumus naive bayes

    $hasil = $classifier->predict($title,$kata);
 ?>
 <!DOCTYPE html>
 <html>
 <head>
     <title>Metode Naive Bayes</title>
     <style>
         {
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
}
body{
    font-family: Helvetica;
    -webkit-font-smoothing: antialiased;
    background: rgba( 0, 0, 0, 0.25);
}
h2{
    text-align: center;
    font-size: 18px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: white;
    padding: 30px 0;
}

/* Table Styles */

.table-wrapper{
    margin: 10px 70px 70px;
    box-shadow: 0px 35px 50px rgba( 0, 0, 0, 0.2 );
}

.fl-table {
    border-radius: 5px;
    font-size: 12px;
    font-weight: normal;
    border: none;
    border-collapse: collapse;
    width: 100%;
    max-width: 100%;
    white-space: nowrap;
    background-color: white;
}

.fl-table td, .fl-table th {
    text-align: center;
    padding: 8px;
}

.fl-table td {
    border-right: 1px solid #f8f8f8;
    font-size: 12px;
}

.fl-table thead th {
    color: #ffffff;
    background: #4FC3A1;
}


.fl-table thead th:nth-child(odd) {
    color: #ffffff;
    background: #324960;
}

.fl-table tr:nth-child(even) {
    background: #F8F8F8;
}

/* Responsive */

@media (max-width: 767px) {
    .fl-table {
        display: block;
        width: 100%;
    }
    .table-wrapper:before{
        content: "Scroll horizontally >";
        display: block;
        text-align: right;
        font-size: 11px;
        color: white;
        padding: 0 0 10px;
    }
    .fl-table thead, .fl-table tbody, .fl-table thead th {
        display: block;
    }
    .fl-table thead th:last-child{
        border-bottom: none;
    }
    .fl-table thead {
        float: left;
    }
    .fl-table tbody {
        width: auto;
        position: relative;
        overflow-x: auto;
    }
    .fl-table td, .fl-table th {
        padding: 20px .625em .625em .625em;
        height: 60px;
        vertical-align: middle;
        box-sizing: border-box;
        overflow-x: hidden;
        overflow-y: auto;
        width: 120px;
        font-size: 13px;
        text-overflow: ellipsis;
    }
    .fl-table thead th {
        text-align: left;
        border-bottom: 1px solid #f7f7f9;
    }
    .fl-table tbody tr {
        display: table-cell;
    }
    .fl-table tbody tr:nth-child(odd) {
        background: none;
    }
    .fl-table tr:nth-child(even) {
        background: transparent;
    }
    .fl-table tr td:nth-child(odd) {
        background: #F8F8F8;
        border-right: 1px solid #E6E4E4;
    }
    .fl-table tr td:nth-child(even) {
        border-right: 1px solid #E6E4E4;
    }
    .fl-table tbody td {
        display: block;
        text-align: center;
    }

    html, body, #container {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
}

     </style>

 </head>
 <h2>Hasil Penghitungan Metode Naive Bayes</h2>
 <body>
    <div style="display: inline-flex">
        <div class="table-wrapper" style="width: 50%">
     <table class="fl-table" id="kata">
        <thead>
        <tr>
            <th>No</th>
            <th>Kata Negatif/Positif</th>
        </tr>
        </thead>
        <?php $i=1; ?>
          <?php foreach ($valueBroHis as $key) { ?> 
          <tbody>                        
            <tr>
                <td><?php echo $i; ?></td>
                <?php  $i++; ?>
                <?php $title[] = $key['history_title']; ?>
                <td>
                    <?php echo $key['history_title']; ?>                                  
                </td>                                             
            </tr>  
            </tbody>
             <?php } ?>       
  </table>
  </div>
  <div class="table-wrapper" style="width: 50%">
  <table  class="fl-table" id="status">
    <thead>
    <tr>
        <th>Status Kategori</th>
    </tr>
    </thead>
      <?php foreach ($hasil as $key) { ?>   
        <tbody>        
            <tr>  
                <?php if ($key<0 ) { ?>
                <td>
                    negatif
                </td>
                <?php } else { ?>
                <td>
                    positif
                </td>
                <?php } ?>                       
            </tr>
        </tbody>   
        <?php } ?>  
  </table>
    </div>  
</div>

 </body>
 </html>
 
