<h1>Logs</h1>
<div class="content_wrapper">
<table id="tablerecords">   
    <thead>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Actor</th>
            <th>Action</th>
            <th>Subject</th>
            <th>Description</th>
        </tr>
    </thead>
    <?php
			/*Display each admin records located on the database */
			$count = 1;
			if($log->list_logs() != false){
				foreach($log->list_logs() as $value){
   					extract($value);
					?>
					<tbody>
      					<tr>
	
							<!--Redirects to the profile page if clicked-->
							<td><?php echo $log_date_managed;?></td>
        					<td><?php echo $log_time_managed;?></td>
							<td><?php echo $log_actor;?></td>
							<td><?php echo $log_action;?></td>
                            <td><?php echo $log_subject;?></td>
                            <td><?php echo $log_description;?></td>
						</tr>
					</tbody>
					<?php
 						$count++;
				}
			}else{
						?>
						<tr>
							<!--Display when no records were found-->
							<td colspan="7">"No Record Found."</td>
						</tr>
					<?php
			}
			?>
</table>
<p>Tae</p>
</div>