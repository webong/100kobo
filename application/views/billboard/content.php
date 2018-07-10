			<div class="center-container col-lg-6 col-md-6 col-xs-12">
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
					<div><h4><big>Billboard</big></h4></div>
				</div>

				<div class="row gallery">
				<?php
				if ($gallery) :
					foreach ($gallery as $image) :
						echo '<a href="'.base_url().'images/'.$image->image.'" data-title="<a href=\''.base_url().'post/p/'.$image->id.'\'>Link to post</a>" data-lightbox="billboard"><img src="'.base_url().'images/'.$image->image.'" /></a>';
					endforeach;
				else :
					echo '<p class="center">No images on billboard at the moment</p>';
				endif;
				?>
				</div><!-- /.gallery -->
            </div><!-- /.center -->