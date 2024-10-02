<h1>Employees</h1>
<div class="content_wrapper">
<table id="tablerecords">   
    <thead>
        <tr>
            <th>Nurse ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact No.</th>
            <th>Department</th>
        </tr>
    </thead>
    <?php
			/*Display each admin records located on the database */
			$count = 1;
			if($employee->list_employees() != false){
				foreach($employee->list_employees() as $value){
   					extract($value);
					?>
					<tbody>
      					<tr>
	
							<!--Redirects to the profile page if clicked-->
        					<td><?php echo $emp_id;?></td>
							<td><?php echo $emp_fname.' '.$emp_mname.' '.$emp_lname;?></td>
        					<td><?php echo $emp_email;?></a></td>
							<td><?php echo $emp_contact;?></td>
							<td><?php echo $emp_department;?></td>
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