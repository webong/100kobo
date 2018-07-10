      	<div class="center-container col-lg-12 col-md-12 col-xs-12">
      		<?php if($this->session->flashdata('message_f')) { ?>
			<div class="alert alert-danger alert-dismissible" role="alert">
		  		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  		<?php echo $this->session->flashdata('message_f'); ?>
		  	</div>
		  	<?php } elseif($this->session->flashdata('message_i')) { ?>
			<div class="alert alert-info alert-dismissible" role="alert">
		  		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  		<?php echo $this->session->flashdata('message_i'); ?>
		  	</div>
		  	<?php } elseif ($this->session->flashdata('message_s')) { ?>
		  	<div class="alert alert-success alert-dismissible" role="alert">
		  		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  		<?php echo $this->session->flashdata('message_s'); ?>
		  	</div>
		  	<?php } ?>
			  	
			<div class="row post">
				<div class="page-header"><h4>Reports (unresolved)</h4></div>
				<?php
				if ($reports_unresolved) :
				?>
				<div class="autoscroll">
					<table class="table">
					    <thead>
				      		<tr>
					        	<th class="text-center">User Complained</th>
					        	<th class="text-center">User Reported</th>
					        	<th class="text-center">Message</th>
					        	<th class="text-center">Date/Time</th>
					        	<th class="text-center">Mark As</th>
					        	<th></th>
				      		</tr>
					    </thead>
					    <tbody>
					<?php
						foreach ($reports_unresolved as $report) :
							echo form_open('reports/process');

							echo form_hidden('id', $report->id);
					?>
							<tr class="text-center">
								<td><a href="<?= base_url().'user/'.$report->user_report ?>"><?= $report->user_report ?></a></td>
								<td><a href="<?= base_url().'user/'.$report->user_reported ?>"><?= $report->user_reported ?></a></td>
						        <td class="text-left"><?= $report->message ?></td>
						        <td><?= $report->added ?></td>
						        <td>
						        	<select name="mark_as">
						        		<option>...</option>
						        		<option value="solved">Solved</option>
						        	</select>
						        </td>
						        <td>
						        <?php
						        $args = array('class' => 'btn btn-blue btn-xs align-right');
						  		echo form_submit('submit', 'Resolve', $args);
						        ?>
						        </td>
				      		</tr>
				    <?php
				    		echo form_close();
				    	endforeach;
				    ?>
				    	<tbody>
				    </table>
			   	</div>
				<?php 
				else :
				?>
				<div class="line">
					<div class="text-center">No unresolved reports</div>
				</div>
				<?php
				endif;
				?>
      		</div><!-- /.post -->

      		<div class="row post">
				<div class="page-header"><h4>Reports (resolved)</h4></div>
				<?php
				if ($reports_resolved) :
				?>
				<div class="autoscroll">
					<table class="table">
					    <thead>
				      		<tr>
					        	<th class="text-center">User Complained</th>
					        	<th class="text-center">User Reported</th>
					        	<th class="text-center">Message</th>
					        	<th class="text-center">Date/Time</th>
				      		</tr>
					    </thead>
					    <tbody>
					<?php
						foreach ($reports_resolved as $report) :
					?>
							<tr class="text-center">
								<td><a href="<?= base_url().'user/'.$report->user_report ?>"><?= $report->user_report ?></a></td>
								<td><a href="<?= base_url().'user/'.$report->user_reported ?>"><?= $report->user_reported ?></a></td>
						        <td class="text-left"><?= $report->message ?></td>
						        <td><?= $report->added ?></td>
				      		</tr>
				    <?php
				    	endforeach;
				    ?>
				    	<tbody>
				    </table>
				</div>
				<?php 
				else :
				?>
				<div class="line">
					<div class="text-center">No resolved reports</div>
				</div>
				<?php
				endif;
				?>
      		</div><!-- /.post -->
      	</div><!-- /.center -->