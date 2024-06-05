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
                margin-top: 1cm;
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
			            LOG BOOK LIMBAH BAHAN BERBAHAYA & BERACUN <br/>
			            <?php echo $header[0]->pabrik; ?> <br/>
			            Periode : <?php echo $header[0]->period; ?> <br/>
			           	Jenis Limbah : <?php echo $header[0]->jenis_limbah; ?> 
		            </strong>
          		</header>
	          	<main>
	           		<table width="100%" border="1" cellspacing="0" cellpadding="4px">
	              		<thead>
			                <?php
								echo "<tr>";
									echo "<th align='center' colspan='7'>Limbah B3 Masuk</th>";
									echo "<th align='center' colspan='5'>Limbah B3 Keluar</th>";
									echo "<th align='center' colspan='2'>Saldo</th>";
								echo "</tr>";
								echo "<tr>";
									echo "<th align='center' rowspan='2'>No.</th>";
									echo "<th align='center' rowspan='2'>Jenis Limbah</th>";
									echo "<th align='center' rowspan='2'>Tanggal Masuk</th>";
									echo "<th align='center' rowspan='2'>Sumber Limbah</th>";
									echo "<th align='center' colspan='2'>Jumlah Limbah Masuk</th>";
									echo "<th align='center' rowspan='2'>Max. Masa Simpan</th>";
									echo "<th align='center' rowspan='2'>Tanggal Keluar</th>";
									echo "<th align='center' colspan='2'>Jumlah Limbah Keluar</th>";
									echo "<th align='center' rowspan='2'>Tujuan Penyerahan</th>";
									echo "<th align='center' rowspan='2'>Nomor Manifest</th>";
									echo "<th align='center' colspan='2'>Sisa LB3 yang ada di TPS</th>";
								echo "</tr>";
								echo "<tr>";
									echo "<th align='center'>Jml <br/></th>";
									echo "<th align='center'>Satuan</th>";
									echo "<th align='center'>Jml</th>";
									echo "<th align='center'>Satuan</th>";
									echo "<th align='center'>Jml</th>";
									echo "<th align='center'>Satuan</th>";
								echo "</tr>";
			                ?>									
			            </thead>
		              	<tbody>
			                <?php
				                	$no = 0;
				                	foreach($report as $key => $dt){
					            		$qty_in = ($dt->type == 'IN')?$dt->quantity:'';
					            		$satuan_in = ($dt->type == 'IN')?$dt->satuan:'';
					            		$qty_out = ($dt->type == 'OUT')?$dt->quantity:'';
					            		$satuan_out = ($dt->type == 'OUT')?$dt->satuan:'';
					            		$no++;
						            	if($dt->number == 0){
							            	echo "<tr>";
											echo "<td></td>";
											echo "<td>".$dt->jenis_limbah."</td>";
											echo "<td>".$dt->tgl_masuk."</td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td align='center'>".$dt->stok."</td>";
											echo "<td align='center'>".$dt->satuan."</td>";
							            	echo "</tr>";
						            	}else{
							            	echo "<tr>";
											echo "<td align='center'>".$no."</td>";
											echo "<td>".$dt->jenis_limbah."</td>";
											echo "<td>".$dt->tgl_masuk."</td>";
											echo "<td>".$dt->sumber_limbah."</td>";
											echo "<td align='center'>".$qty_in."</td>";
											echo "<td align='center'>".$satuan_in."</td>";
											echo "<td align='center'>".$dt->max_simpan."</td>";
											echo "<td align='center'>".$dt->tgl_keluar."</td>";
											echo "<td align='center'>".$qty_out."</td>";
											echo "<td align='center'>".$satuan_out."</td>";
											echo "<td>".$dt->nama_vendor."</td>";
											echo "<td>".$dt->no_manifest."</td>";
											echo "<td align='center'>".$dt->stok."</td>";
											echo "<td align='center'>".$dt->satuan."</td>";
							            	echo "</tr>";
						            	}
						        	}
			                ?>
		              	</tbody>
		            </table>
	            
			        <br/>

			        <table width="100%">
			        	<tr>
			        		<td></td>
			        		<td></td>
			        		<td width="20%" align="center"> <?php echo $region.', '.date('d M Y'); ?> <br/><br/><br/></td>
			        	</tr>
			        	<tr>
			        		<td height="50px"></td>
			        		<td></td>
			        		<td></td>
			        	</tr>
			        	<tr>
			        		<td></td>
			        		<td></td>
			        		<td width="20%" align="center"> <?php echo $user->nama; ?> </td>
			        	</tr>
			        </table>
	          	</main>

				<footer>
			        <table width="100%">
			        	<tr>
			        		<td><?php echo $header[0]->form_log_book_number; ?></td>
			        		<td align="right"><div id="footer"><p class="page">Page </p></div></td>
			        	</tr>
			        </table>
					
		        </footer>		        


	</body>
</html>

