<!doctype html>
<html lang="en">
<style type="text/css">
<!--
.style2 {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
-->
</style>
	<?php include "header.php";?>
	<?php
		if(empty($_SESSION['id_pengguna']))
		{
			header('Location:login.php');;
		}
	?>
	<?php

		$tbl_name="permohonan";		//your table name
		// How many adjacent page should be shown on each side?
		$adjacents = 3;
		
		$query = "SELECT COUNT(*) as Num FROM $tbl_name WHERE status = '0' ORDER BY id_permohonan DESC";
		$total_page = mysql_fetch_array(mysql_query($query));
		$total_page = $total_page['Num'];
		
		/* Setup vars for query. */
		$targetpage = "administrator.php"; 	//your file name  (the name of this file)
		$limit = 5; 								//how many items to show per page
		
		$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 0; 
		if($page) {
			$start = ($page - 1) * $limit; 			//first item to display on this page
			$end = ($page) * $limit;}
		else
			$start = 0;								//if no page var is given, set start to 0
		
		/* Get data. */
		$sql = "SELECT * FROM $tbl_name WHERE status = '0' ORDER BY id_permohonan DESC LIMIT $start, $limit ";
		$result = mysql_query($sql);
			
		/* Setup page vars for display. */
		$prev = $page - 1;							//previous page is page - 1
		$next = $page + 1;							//next page is page + 1
		$lastpage = ceil($total_page/$limit);		//lastpage is = total page / items per page, rounded up.
		$lpm1 = $lastpage - 1;						//last page minus 1
		
		/* 
			Now we apply our rules and draw the pagination object. 
			We're actually saving the code to a variable in case we want to draw it more than once.
		*/
		$pagination = "";
		if($lastpage > 1)
		{	
			$pagination .= "<div class=\"pagination\">";
			
			//pages	
			if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
			{	
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px; background-color:#3071A9; color:#fff;'><strong>$counter</strong></span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px;'>$counter</a>";					
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px; background-color:#3071A9; color:#fff;'><strong>$counter</strong></span>";
						else
							$pagination.= "<a href=\"$targetpage?page=$counter\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px;'>$counter</a>";					
					}
					$pagination.= "...";
					$pagination.= "<a href=\"$targetpage?page=$lpm1\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px;'>$lpm1</a>";
					$pagination.= "<a href=\"$targetpage?page=$lastpage\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px;'>$lastpage</a>";		
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<a href=\"$targetpage?page=1\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px;'>1</a>";
					$pagination.= "<a href=\"$targetpage?page=2\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px;'>2</a>";
					$pagination.= "...";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px; background-color:#3071A9; color:#fff;'><strong>$counter</strong></span>";
						else
							$pagination.= "<a href=\"$targetpage?page=$counter\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px;'>$counter</a>";					
					}
					$pagination.= "...";
					$pagination.= "<a href=\"$targetpage?page=$lpm1\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px;'>$lpm1</a>";
					$pagination.= "<a href=\"$targetpage?page=$lastpage\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px;'>$lastpage</a>";		
				}
				//close to end; only hide early pages
				else
				{
					$pagination.= "<a href=\"$targetpage?page=1\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px;'>1</a>";
					$pagination.= "<a href=\"$targetpage?page=2\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px;'>2</a>";
					$pagination.= "...";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px; background-color:#3071A9; color:#fff;'><strong>$counter</strong></span>";
						else
							$pagination.= "<a href=\"$targetpage?page=$counter\" style='margin-left:5px;margin-right:5px; border:1px solid #E6E6E6; padding:5px;'>$counter</a>";					
					}
				}
			}
		}
	?>
  <body>
	<div class="container">
		<div class="col-lg-12">
			<div class="card">
			  <div class="card-body shadow">
				<center><img src="img/6.png" class="img-fluid header" alt="Responsive image"></center>
				<?php include "navbar2.php"; ?>
				<div class="row justify-content-md-center">
					<div class="col col-lg-10">
						
						<?php
							$i = 1;
							$j = (($page-1) * $limit) + $i;
							
							$num_results = mysql_num_rows($result);
							
							if($num_results > 0)
							{
								/////////// Now let us print the table headers ////////////////
								echo '<table class="table table-bordered">';
								echo '<thead>';
								echo '<tr>';
								echo '<th scope="col">Bil.</th>';
								echo '<th scope="col">Maklumat Pemohon</th>';
								echo '<th scope="col">Maklumat Peralatan</th>';
								echo '</tr>';
								echo '</thead>';
								
								while($row = mysql_fetch_array($result))
								{        
							
									$result_peralatan1 = mysql_query("SELECT * FROM peralatan WHERE no_aset = '".$row['peralatan1']."'") or die(mysql_error());
									$row1 = mysql_fetch_array( $result_peralatan1 );
							
									$result_peralatan2 = mysql_query("SELECT * FROM peralatan WHERE no_aset = '".$row['peralatan2']."'") or die(mysql_error());
									$row2 = mysql_fetch_array( $result_peralatan2 );
									
							
									$result_peralatan3 = mysql_query("SELECT * FROM peralatan WHERE no_aset = '".$row['peralatan3']."'") or die(mysql_error());
									$row3 = mysql_fetch_array( $result_peralatan3 );
									
									
									echo "<tbody>";
									echo "<tr>";
									echo "<th style='text-align:center' rowspan='4' scope='row' width='2%'>".$j."."."</th>";
									echo '<td rowspan="4">
										<span style="font-weight:bold;">Pemohon : </span>'.strtoupper($row['nama_pemohon']).' <br>
										<span style="font-weight:bold;">Tujuan : </span>'.strtoupper($row['tujuan']).' <br>
										<span style="font-weight:bold;">Tempat Digunakan : </span>'.strtoupper($row['tempat_digunakan']).' <br>
										<span style="font-weight:bold;">Tarikh Pinjam : </span>'.strtoupper($row['tarikh_pinjam']).' <br>
										<span style="font-weight:bold;">Tarikh Dijangka Pulang : </span>'.strtoupper($row['tarikh_dijangkapulang']).'
										<br><br>
									
										
									
										
						<a href="perincian.php?permohonanID=' .$row['id_permohonan']. '" class="btn btn-primary btn-sm" role="button">Lihat lanjut</a>
															
										
										</td>';
									echo '<tr><td>
											<span style="font-weight:bold;">Peralatan 1 : </span>'.strtoupper($row1['peralatan']).' <br>
											<span style="font-weight:bold;">Model : </span>'.strtoupper($row1['model']).' <br>
											<span style="font-weight:bold;">No. Aset : </span>'.strtoupper($row1['no_aset']).'
										</td></tr>
										<tr><td>
											<span style="font-weight:bold;">Peralatan 2 : </span>'.strtoupper($row2['peralatan']).' <br>
											<span style="font-weight:bold;">Model : </span>'.strtoupper($row2['model']).' <br>
											<span style="font-weight:bold;">No. Aset : </span>'.strtoupper($row2['no_aset']).'
										</td></tr>
										<tr><td>
											<span style="font-weight:bold;">Peralatan 3 : </span>'.strtoupper($row3['peralatan']).' <br>
											<span style="font-weight:bold;">Model : </span>'.strtoupper($row3['model']).' <br>
											<span style="font-weight:bold;">No. Aset : </span>'.strtoupper($row3['no_aset']).'
										</td></tr>';
									echo "</tr>";
									echo "</tbody>";
									$j++;
								}
								echo "</table>";
							}
							
							else
							{
								echo "<center>Tiada permohonan baru setakat ini!</center>";
							}
						?>
						<br>
						<br>
						<?php echo"<div style='text-align:center;'>".$pagination."</div>";?>
					</div>
				</div>
				<?php include "footer.php";?>
			  </div>
			</div>
		</div>
	</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>