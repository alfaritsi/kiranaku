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
    			font-size:11px;
            }

			body {
                margin-top: 0.5cm;
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
    			font-size:13px;
            }

            footer {
                position: fixed; 
                bottom: -20px; 
                left: 0px; 
                right: 0px;
                height: 40px; 

                /** Extra personal styles **/
                /*background-color: #03a9f4;*/
                /*color: white;*/
                text-align: left;
                line-height: 40px;
                font-family: 'Segoe UI';
    			font-size:11px;
            }

            #footer .page:after { 
            	content: counter(page); 
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
	<body>
          		<header>
          			<strong>
			            NERACA LIMBAH BAHAN BERBAHAYA DAN BERACUN <br/>
			            <?php echo $header[0]->pabrik; ?> <br/>
			            Periode : <?php echo $header[0]->period; ?> 
		            </strong>
          		</header>
          		<br/>
          		
	          	<main>
	           		<table width="100%" border="1" cellspacing="0" cellpadding="4px">
						<?php
							echo "<tr>";
								echo "<th align='center'>No</th>";
								echo "<th align='center'>Jenis Limbah B3</th>";
								echo "<th align='center'>Kode Limbah</th>";
								echo "<th align='center'>Jumlah (Ton)</th>";
								// echo "<th align='center'>Jumlah Awal</th>";
								// echo "<th align='center'>Satuan</th>";
								// echo "<th align='center'>Faktor Konversi</th>";
								echo "<th align='center' colspan='3'>Catatan</th>";
							echo "</tr>";
							$total_A = 0;
							foreach($report1 as $key => $dt){
								$no = $key + 1;
								$jumlah = ($dt->jumlah)>0?$dt->jumlah:0;
								$total_A += $jumlah;
								$jumlah_arr[] = $jumlah;
								echo "<tr>";
									echo "<td align='center'>".$no."</td>";
									echo "<td>".$dt->limbah."</td>";
									echo "<td>".$dt->kode_limbah."</td>";
									echo "<td align='right'>".number_format($jumlah,4)."</td>";
									// echo "<td align='right'>".number_format($dt->jumlah,4,",",".")."</td>";
									// echo "<td align='right'>".number_format($dt->jumlah_awal,4,",",".")."</td>";
									// echo "<td align='center'>".$dt->satuan."</td>";
									// echo "<td align='right'>".number_format($dt->fkonv,5,",",".")."</td>";
									echo "<td colspan='3'>-</td>";
								echo "</tr>";
							}
							echo "<tr>";
								echo "<td></td>";
								echo "<td align='center'><strong>TOTAL (A)</strong></td>";
								echo "<td></td>";
								echo "<td align='right'><strong>".number_format($total_A,4)."</strong></td>";
								// echo "<td></td>";
								// echo "<td></td>";
								// echo "<td></td>";
								echo "<td colspan='3'></td>";
							echo "</tr>";
							
						?>									
					
	              		<thead id="table_header">
							<?php
			                  	echo "<tr>";
				                  	echo "<td align='center' rowspan='2'><strong>No</strong></td>";
				                  	echo "<td align='center' colspan='2' rowspan='2'><strong>Pengelolaan</strong></td>";
				                  	echo "<td align='center' rowspan='2'><strong>Jumlah (Ton)</strong></td>";
				                  	echo "<td align='center' rowspan='2'><strong>Jenis Limbah yang Dikelola</strong></td>";
				                  	// echo "<td align='center' rowspan='2'><strong>Nomor Manifest</strong></td>";
				                  	echo "<td align='center' colspan='2'><strong>Perizinan Limbah B3</strong></td>";
			                  	echo "</tr>";
			                  	echo "<tr>";
				                  	echo "<td align='center'><strong>Ada</strong></td>";
				                  	echo "<td align='center'><strong>Tidak Ada</strong></td>";
			                  	echo "</tr>";
							?>
			            </thead>
		              	<tbody id="table_body">
			                <?php
								if(@$perijinan[0]->tanggal_kadaluarsa!=NULL){
									$cek_ada		= "V";	
									$cek_tidak_ada	= "-";	
								}else{
									$cek_ada		= "-";	
									$cek_tidak_ada	= "V";	
								}
							
			                  	echo "<tr>";
				                  	echo "<td align='center'>1</td>";
				                  	echo "<td colspan='2'>Disimpan di TPS</td>";
				                  	echo "<td></td>";
				                  	echo "<td></td>";
				                  	// echo "<td></td>";
				                  	echo "<td  align='center'>$cek_ada</td>";
				                  	echo "<td  align='center'>$cek_tidak_ada</td>";
			                  	echo "</tr>";

			                  	$total_B1 = 0;
			                  	foreach($report2 as $key2 => $dt2){
				                  	// $izin = ($dt2->lampiran != "")?"Ada":"";
				                  	// $total_B1 += ($jumlah_arr[$key2] - $dt2->jumlahout);
				                  	// $total_B1 += ($jumlah_arr[$key2] - $dt2->jumlahsimpan);
									$jumlahsimpan = $dt2->jumlah_masuk - $dt2->jumlah_keluar;
									$total_B1 += ($jumlahsimpan > 0 )?$jumlahsimpan:0;
				                  	echo "<tr>";
					                  	echo "<td></td>";
					                  	echo "<td align='center' colspan='2'></td>";
										// echo "<td align='right'>".number_format($dt2->jumlahsimpan,4,",",".")."</td>";
										echo "<td align='right'>".number_format($jumlahsimpan,4)."</td>";
					                  	echo "<td>".$dt2->limbah."</td>";
					                  	// echo "<td></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
				                  	echo "</tr>";
				                }

			                  	echo "<tr>";
				                  	echo "<td align='center'>2</td>";
				                  	echo "<td colspan='2'>Dimanfaatkan</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td></td>";
				                  	echo "<td></td>";
			                  	echo "</tr>";
			                  	echo "<tr>";
				                  	echo "<td align='center'>3</td>";
				                  	echo "<td colspan='2'>Diolah</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td></td>";
				                  	echo "<td></td>";
			                  	echo "</tr>";
			                  	echo "<tr>";
				                  	echo "<td align='center'>4</td>";
				                  	echo "<td colspan='2'>Ditimbun</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td></td>";
				                  	echo "<td></td>";
			                  	echo "</tr>";
			                  	echo "<tr>";
				                  	echo "<td align='center'>5</td>";
				                  	echo "<td colspan='2'>Diserahkan Pihak Ketiga</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td></td>";
				                  	echo "<td></td>";
			                  	echo "</tr>";

			                  	$total_B2 = 0;
			                  	foreach($report3 as $key3 => $dt3){
				                  	$izin = ($dt3->lampiran != "")?"Ada":"";
									$jumlah3 = ($dt3->jumlah>0)?$dt3->jumlah:0;
									$total_B2 += $jumlah3;

				                  	echo "<tr>";
					                  	echo "<td></td>";
					                  	echo "<td colspan='2'></td>";
										echo "<td align='right'>".number_format($jumlah3,4)."</td>";
					                  	echo "<td>".$dt3->limbah."</td>";
					                  	echo "<td>".$izin."</td>";
					                  	echo "<td></td>";
				                  	echo "</tr>";
				                }

			                  	echo "<tr>";
				                  	echo "<td align='center'>6</td>";
				                  	echo "<td colspan='2'>Eksport</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td></td>";
				                  	echo "<td></td>";
			                  	echo "</tr>";
			                  	echo "<tr>";
				                  	echo "<td align='center'>7</td>";
				                  	echo "<td colspan='2'>Perlakuan Lainya</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td></td>";
				                  	echo "<td></td>";
			                  	echo "</tr>";
								$total_B = $total_B1+$total_B2;
			                  	echo "<tr>";
				                  	echo "<td></td>";
				                  	echo "<td colspan='2'><strong>TOTAL (B)</strong></td>";
									echo "<td align='right'><strong>".number_format($total_B,4)."</strong></td>";
				                  	echo "<td></td>";
				                  	echo "<td></td>";
				                  	echo "<td></td>";
			                  	echo "</tr>";
								$total_C = $total_A-$total_B;
			                  	echo "<tr>";
				                  	echo "<td align='center'>8</td>";
				                  	echo "<td colspan='2'>Residu (C)</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td></td>";
				                  	echo "<td></td>";
			                  	echo "</tr>";
			                  	echo "<tr>";
				                  	echo "<td align='center'>9</td>";
				                  	echo "<td colspan='2'>Jumlah limbah belum dikelola (D)</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td>-</td>";
				                  	echo "<td></td>";
				                  	echo "<td></td>";
			                  	echo "</tr>";

				                  	$total_D2 = 0;
				                  	foreach($report4 as $key4 => $dt4){
					                  	$total_D2 += $dt4->jumlah;
					                  	echo "<tr>";
						                  	echo "<td></td>";
						                  	echo "<td colspan='2'></td>";
											echo "<td>-</td>";
						                  	echo "<td>".$dt4->limbah."</td>";
						                  	// echo "<td>".$dt4->manifest."</td>";
						                  	echo "<td></td>";
						                  	echo "<td></td>";
					                  	echo "</tr>";
					                }
				                  	echo "<tr>";
					                  	echo "<td></td>";
					                  	echo "<td colspan='2'><strong>Total Jumlah Limbah yang Tersisa (D)</strong></td>";
										echo "<td>-</td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
					                  	// echo "<td></td>";
				                  	echo "</tr>";

									$total_limbah = $total_C+$total_D2;

								$persen = ($total_A == 0)?0:(($total_B - $total_C) / $total_A)*100;
			                  	echo "<tr>";
				                  	echo "<td></td>";
				                  	echo "<td colspan='2'><strong>Kinerja pengelolaan limbah B3 Selama periode penataan</strong></td>";
									echo "<td align='right'><strong>".number_format($persen,2)." %</strong></td>";
				                  	echo "<td></td>";
				                  	// echo "<td></td>";
				                  	echo "<td></td>";
				                  	echo "<td></td>";
			                  	echo "</tr>";

			                ?>
		              	</tbody>
		            </table>
	            
		            <br/><br/>

			        <table width="100%">
			        	<tr>
			        		<td></td>
			        		<td></td>
			        		<td width="40%" align="center"><?php echo $region.', '.date('d M Y'); ?> <br/><br/></td>
			        	</tr>
			        	<tr><td height="40px"></td><td></td><td></td></tr>
			        	<tr>
			        		<td></td>
			        		<td></td>
			        		<td width="40%" align="center"> ( <?php echo $user->nama; ?> ) </td>
			        	</tr>
			        	<tr>
			        		<td></td>
			        		<td></td>
			        		<td width="40%" align="center">PIC SHE</td>
			        	</tr>
			        </table>
	          	</main>

	          	<footer>
			        <table width="100%">
			        	<tr>
			        		<td></td>
			        		<td align="right"><div id="footer"><p class="page">Page </p></div></td>
			        	</tr>
			        </table>
	          		
	          	</footer>

	</body>
</html>

