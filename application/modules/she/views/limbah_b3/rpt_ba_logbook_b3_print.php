<!--
/*
@application    : SHE 
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/
-->

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo strtoupper($module) ?> | PT. Kirana Megatara Tbk</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<style>
            /** Define the margins of your page **/
            @page {
                margin: 70px 25px;
                font-family: 'Segoe UI';
    			font-size:12px;
            }

			body {
                margin-top: 0cm;
                margin-left: 0cm;
                margin-right: 0cm;
                margin-bottom: 0cm;
            }

            header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 60px;

                /** Extra personal styles **/
                /*background-color: #03a9f4;*/
                /*color: white;*/
                text-align: center;
                line-height: 20px;
                font-family: 'Segoe UI';
    			font-size:15px;
            }

            footer {
                position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px;
                height: 40px; 

                /** Extra personal styles **/
                /*background-color: #03a9f4;*/
                color: white;
                text-align: left;
                line-height: 40px;
                font-family: 'Segoe UI';
    			font-size:14px;
            }

/*			@page { margin: 180px 50px; }
			    #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }
			    #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px; background-color: lightblue; }
			    #footer .page:after { content: counter(page, upper-roman); }
*/

/*		    @media print
			 {
			 .firstrow {page-break-before:always}
			 }
*/
        </style>
	</head>
	<body">
			<section>
          		<div align="center">
          			<strong>
			            <h2> BERITA ACARA PENGANGKUTAN LIMBAH B3 </h2>
			            <h3> <u> Nomor : <?php echo $report[0]->no_berita_acara; ?> </u> </h3>
		            </strong>
          		</div>

          		<div>
		            Pada hari ini, <?php echo $report[0]->hari." ".$report[0]->tanggal; ?> telah dilakukan kegiatan pengangkutan limbah B3 di <?php echo $report[0]->nama; ?> oleh <?php echo $report[0]->nama_vendor; ?>. <br/><br/>

		            Jenis dan jumlah yang diangkut adalah: <br/> <br/>
				</div>

           		<table width="100%" border="1px" cellspacing="0">
              		<thead>
		                <?php
							echo "<tr>";
								echo "<th style='padding:10px;' align='center'>No</th>";
								echo "<th style='padding:10px;' align='center'>Jenis Limbah B3</th>";
								echo "<th style='padding:10px;' align='center'>Jumlah (Kg atau Liter)</th>";
								echo "<th style='padding:10px;' align='center'>Nomor Manifest Manual</th>";
							echo "</tr>";
		                ?>									
		            </thead>
	              	<tbody>
		                <?php
		                	$no = 0;
		                	foreach($report as $key => $dt){
					            echo "<tr>";
				            		$no++;
									echo "<td style='padding:3px;' align='center'>".$no."</td>";
									echo "<td style='padding:3px;' align='center'>".$dt->jenis_limbah."</td>";
									echo "<td style='padding:3px;' align='center'>".$dt->quantity." ".$dt->satuan."</td>";
									echo "<td style='padding:3px;' align='center'>".$dt->no_manifest."</td>";
					            echo "</tr>";
							}
						?>
	              	</tbody>
	            </table>

	            <br/>

          		<div>
	            	Keterangan pihak pengangkutan limbah adalah sebagai berikut: <br/><br/>
          		</div>

		        <table width="100%" border="1px" cellspacing="0">
		        	<tbody>
		                <?php
				            echo "<tr>";
								echo "<td style='padding-left:10px;' width='30%'>Nama / Petugas Driver</td>";
								echo "<td width='3%' align='center'>:</td>";
								echo "<td style='padding-left:10px;'>".$report[0]->nama_driver."</td>";
				            echo "</tr>";
				            echo "<tr>";
								echo "<td style='padding-left:10px;'>Nomor Kendaraan</td>";
								echo "<td width='3%' align='center'>:</td>";
								echo "<td style='padding-left:10px;'>".$report[0]->nomor_kendaraan."</td>";
				            echo "</tr>";
				            echo "<tr>";
								echo "<td style='padding-left:10px;'>Jenis Kendaraan</td>";
								echo "<td width='3%' align='center'>:</td>";
								echo "<td style='padding-left:10px;'>".$report[0]->jenis_kendaraan."</td>";
				            echo "</tr>";
						?>		        		
		        	</tbody>
		        </table>
				
				<br/><br/>

				Demikian berita acara ini dibuat agar bisa digunakan sebagaimana mestinya. <br/><br/><br/>
								
		        <table width="100%">
		        	<tbody>
			        	<tr>
			        		<td align="center">Pihak Pengangkut,</td>
			        		<td align="center">Dibuat Oleh,</td>
			        	</tr>
			        	<tr> <td height="60px"></td><td></td> </tr>
			        	<tr>
			        		<td align="center"><?php echo $report[0]->nama_driver; ?> </td>
			        		<td align="center"> <?php echo $report[0]->nama_user; ?> </td>
			        	</tr>
			        	<tr>
			        		<td align="center"> <?php echo $report[0]->nama_vendor; ?> </td>
			        		<td align="center">SHE <?php echo $report[0]->nama; ?> </td>
			        	</tr>
			        	<tr> <td height="30px"></td><td></td> </tr>
			        	<tr>
			        		<td align="center">Disaksikan Oleh,</td>
			        		<td align="center">Diketahui Oleh,</td>
			        	</tr>
			        	<tr> <td height="60px"></td><td></td> </tr>
			        	<tr>
			        		<td align="center"></td>
			        		<td align="center"><?php echo $report[0]->manager; ?></td>
			        	</tr>
			        	<tr>
			        		<td align="center">( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;)</td>
			        		<td align="center">(Mnj. Kantor / Mnj. Pabrik)</td>
			        	</tr>
			        	<tr> <td height="30px"></td><td></td> </tr>
			        	<tr>
			        		<td align="center" colspan="2">Disetujui Oleh,</td>
			        	</tr>
			        	<tr> <td height="60px"></td><td></td> </tr>
			        	<tr>
			        		<td align="center" colspan="2"><?php echo $report[0]->dirops; ?></td>
			        	</tr>
			        	<tr>
			        		<td align="center" colspan="2">(Direktur Operasional)</td>
			        	</tr>
					</tbody>
		        </table>
			
			</section>
	</body>
</html>

