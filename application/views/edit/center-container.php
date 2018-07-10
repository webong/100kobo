      	<div class="center-container col-lg-6 col-md-6 col-xs-12">
			<div class="row post">
                        <div class="left-post">
                              <img src="<?= base_url()."assets/img/no-profile.png" ?>" />
                        </div>
                        <div class="right-post">
                              <div class="line">
                                    <a href="<?= base_url()."user/".$post->username ?>"><h5><?= $post->first_name ?> <?= $post->surname ?></h5></a>
                                    <span class="time-ago"><?= $post->time_ago ?></span>
                              </div>
                              <div class="line">
                                    <p>
                                    <?php 
                                    echo $post->text;
                                    if (strlen($post->image) > 1) {
                                          echo '<a href="'.base_url().'images/'.$post->image.'"><img src="'.base_url().'images/'.$post->image.'" /></a>';
                                    }
                                    ?>
                                    </p>
                              </div>
                        </div>
                        <div class="bottom-post">
                              <button class="anchor" data-toggle="collapse" data-target="#sub-<?= $post->id ?>"><span class="glyphicon glyphicon-comment"></span></button>
                              <button class="anchor" data-toggle="modal" data-target="#modal-<?php if (isset($is_loged_in) && $is_logged_in == 1) echo $post->id; else echo 'login' ?>"><span class="glyphicon glyphicon-pencil"></span></button>
                              <a href="#" class="btn btn-xs btn-danger btn-m">Delete</a>
                              <a href="#" class="btn btn-xs btn-default btn-m">Edit</a>
                        </div>
                </div><!-- /.post -->
            </div><!-- /.center -->