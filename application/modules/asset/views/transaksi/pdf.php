<!--
/*
@application    : Management Asset 
@author 		: Lukman Hakim (7143)
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
		<main>
			<table width="30%">
				<tr>
					<td colspan=2><?php echo $main[0]->nama_pabrik?></td>
				</tr>
				<tr>
					<td>Tanggal</td>
					<td>: <?php echo $main[0]->tanggal_buat?></td>
				</tr>
				<tr>
					<td>Pekerjaan</td>
					<td>: <?php echo $main[0]->nama_jenis_tindakan?></td>
				</tr>
				<tr>
					<td>Operator</td>
					<td>: <?php echo $main[0]->operator?></td>
				</tr>
			</table>
			<table width="100%" border="1" cellspacing="1px" cellpadding="1px">
				<tbody>
					<tr>
						<td>Item</td>
						<td>Pekerjaan</td>
						<td>Cek</td>
						<td>Keterangan</td>
					</tr>
					<?php
					foreach($main_detail as $dt){
						echo "<tr>";
						echo "<td>".$dt->nama_jenis_detail."</td>";
						echo "<td>".$dt->nama_periode_detail."</td>";
						echo "<td align='center'><input type='checkbox'></td>";
						echo "<td></td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</main>
	</body>
</html>

