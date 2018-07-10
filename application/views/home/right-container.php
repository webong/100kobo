		<div class="right-container col-lg-3 col-md-3 col-xs-12">
			<div class="row">
				<div class="page-header">
					<h3>Billboard</h3>
                    <div class="time-ago"><a href="<?= base_url().'billboard' ?>">View all</a></div>
				</div>
      		</div>
      		<div class="row gallery">
      			<?php
      			if ($gallery) :
                    foreach($gallery as $image) :
                        echo '<a href="'.base_url().'post/p/'.$image->id.'"><img src="'.base_url().'images/'.$image->image.'" alt="'.$image->text.'" /></a>';
                    	// echo '<a href="'.base_url().'images/'.$image->image.'" data-title="'.$image->text.'" data-lightbox="billboard"><img src="'.base_url().'images/'.$image->image.'" alt="'.$image->text.'" /></a>';
                    endforeach;
                else :
                	echo '<div class="line posts-empty">No images on billboard</div>';
                endif;
                ?>
      		</div>

      		<div class="row">
				<div class="page-header">
					<h3>Trending</h3>
				</div>
      		</div>

            <div id="t-loading">
                <img src="<?= base_url() ?>assets/img/loading.gif" />
            </div>
            <div id="t-posts-list">
      		<?php
  			 if ($trending) :
              foreach($trending as $post) : ?>
	            	<div class="row post-mini">
						<div class="line">
							<a href="<?= base_url()."user/".$post->username ?>"><h5><?= $post->first_name ?> <?= $post->surname ?></h5></a>
						</div>
                        <a href="<?= base_url().'post/p/'.$post->id ?>" target="_parent">
    						<div class="line">
    							<p>
                                <?php
                                echo '<span class="line-text">'.$post->text.'</span>';
                                if (strlen($post->image) > 1) {
                                    echo '<img src="'.base_url().'images/'.$post->image.'" class="post_image" alt="'.$post->text.'" />';
                                } ?>
                                </p>
    						</div>
    						<div class="line push-top-4">
    							<span class="time-ago"><?= $post->time_ago ?></span>
    						</div>
                        </a>
                        <div class="bottom-post">
                        <?php if ($access > 0) : ?>
                            <a href="<?= base_url().'post/p/'.$post->id ?>" target="_parent"><button class="anchor"><span class="glyphicon glyphicon-comment"></span><?php if ($post->comments > 0) : ?><span class="count-comments"><?php echo $post->comments; ?></span><?php endif; ?></button></a>
                            <button class="anchor" data-toggle="modal" data-target="#modal-<?= $post->id; ?>" onclick="set_validate('10kobo')"><span class="glyphicon glyphicon-pencil"></span></button>
                            <span class="text-muted align-right number-n">10kobo</span>
                        <?php else : ?>
                            <button class="anchor" data-toggle="modal" data-target="#modal-login"><span class="glyphicon glyphicon-comment"></span><?php if ($post->comments > 0) : ?><span class="count-comments"><?php echo $post->comments; ?></span><?php endif; ?></button>
                            <button class="anchor" data-toggle="modal" data-target="#modal-login"><span class="glyphicon glyphicon-pencil"></span></button>
                            <span class="text-muted align-right number-n">10kobo</span>
                        <?php endif; ?>
                        </div>
		      		</div><!-- /.post-mini -->

                    <div id="modal-<?= $post->id ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <?php
                                $args = array(
                                    'id'        => $post->id,
                                    'onsubmit'  => 'return validate(this)'
                                );
                                echo form_open_multipart('post', $args);
                                ?>
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Write a comment to <?= $post->first_name.' '.$post->surname ?></h4>
                                </div>
                                <div class="modal-body post">
                                    <div class="line">
                                        <h5>Message</h5>
                                        <span id="textarea_feedback" class="counter align-right"></span>
                                        <textarea name="message" id="textarea" class="form-control" maxlength="160" placeholder="Message..."></textarea>
                                    </div>
                                    <div class="line push-top-2 push-bottom-1">
                                        <h5>Upload image (optional)</h5>
                                        <input type="file" name="file" size="20" />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <?php
                                    echo form_hidden('reply_id', $post->id);
                                    $args = array('class' => 'btn btn-blue align-right');
                                    echo form_submit('submit', 'Post', $args);
                                    ?>
                                </div>
                                <?= form_close() ?>
                            </div>
                        </div>
                    </div>
            	<?php
                endforeach;
                ?>
            </div>

            <div class="row text-center" id="t-load-more">
                <button class="btn btn-default" id="t_load_more">Load More</button>
                <img id="t_loading" src="<?= base_url() ?>assets/img/loading.gif" />
                <p id="no_more_t_posts" class="gray">No more posts at the moment</p>
            </div>

            <?php
            else :
            	echo '<div class="row post-mini">No trending posts right now</div>';
            endif;
            ?>
        </div>
      	</div><!-- /.right -->