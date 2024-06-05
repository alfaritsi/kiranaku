<?php
 
	header("Content-type: application/vnd-ms-excel");
	 
	header("Content-Disposition: attachment; filename=$title.xls");
	 
	header("Pragma: no-cache");
	 
	header("Expires: 0");

		foreach ($pabrik as $key1 => $dt1) {
			if(in_array($dt1->plant, $filterpabrik)){
				echo "<strong>".$dt1->plant_name."</strong><br/>";
				echo "<table border='1' width='100%'>";
				echo "<thead>";
				echo "<tr>";
				echo "<th class='text-center'>No.</th>";
				echo "<th class='text-center'>No. Ticket</th>";          
				echo "<th class='text-center'>Requestor</th>";       
				echo "<th class='text-center'>Date Open</th>";          
				echo "<th class='text-center'>Date Pickup By Agent</th> ";          
				echo "<th class='text-center'>Response Time</th>";          
				echo "<th class='text-center'>Date Close By User</th>";          
				echo "<th class='text-center'>Date Close By Agent</th>";          
				echo "<th class='text-center'>Leadtime</th>";          
				echo "<th class='text-center'>Actual</th>";          
				echo "<th class='text-center'>Agent</th>";          
				echo "<th class='text-center'>Category</th>";          
				echo "<th class='text-center'>Sub Category</th>";   
				echo "<th class='text-center'>Title</th>";          
				echo "<th class='text-center'>Keterangan</th>";          
				echo "<th class='text-center'>Lokasi</th>";          
				echo "<th class='text-center'>Status</th>";          
				echo "</tr>";
				echo "</thead>";
			  	echo "<tbody>";

				$total_actual 	= 0;
	        	$no 			= 0;

		        foreach($ticket as $key => $dt){
					if($dt->lokasi == $dt1->plant){
						$no = $no + 1;
						echo "<tr>";
						echo "<td align='center'>".$no."</td>";
						echo "<td align='center'>".$dt->no_ticket."</td>";
						echo "<td>".$dt->nama."</td>";
						echo "<td>".$this->generate->generateDatetimeFormat($dt->tanggal_awal)."</td>";
						echo "<td>".($dt->open_tiket_end ? $this->generate->generateDatetimeFormat($dt->open_tiket_end) : '-')."</td>";
						echo "<td>".($dt->responsetime ? $dt->responsetime : '-')."</td>";
						echo "<td>".($dt->tglwaktu_userclose ? $this->generate->generateDatetimeFormat($dt->tglwaktu_userclose) : '-')."</td>";
						echo "<td>".($dt->tglwaktu_touser ? $this->generate->generateDatetimeFormat($dt->tglwaktu_touser) : '-')."</td>";
						echo "<td>".($dt->leadtime ? $dt->leadtime : '-')."</td>";
						if($dt->actual == "2" || $dt->actual == "1" || $dt->actual == "0"){
							$actualtable = "1";
						}elseif($dt->actual >= "3"){
							$actualtable = $dt->actual;
						}else{
							$actualtable = "";
						}
						echo "<td>".$actualtable."</td>";
						echo "<td>".$dt->agent."</td>";
						$total_actual += $actualtable == "" ? 0 : $actualtable;
						echo "<td>".$dt->kategori."</td>";
						echo "<td>".$dt->nama_subkategori."</td>";
						echo "<td>".$dt->title."</td>";
						echo "<td>".$dt->keterangan."</td>";
						echo "<td>".$dt->lokasi."</td>";
						echo "<td>".$dt->status."</td>";
						echo "</tr>";
		            }
		        }

				echo "<tr>";
				echo "<td colspan='2' align='center'><strong>Total Ticket</strong></td>";
				echo "<td align='right'><strong>".$no."</strong></td>";
				echo "<td colspan='3'></td>";
				echo "<td align='right'><strong>".$total_actual."</strong></td>";
				echo "<td colspan='7'></td>";
				echo "</tr>";
				echo "</tbody>";
				echo "</table>";					
				echo "<br/>";				

			}
		}
