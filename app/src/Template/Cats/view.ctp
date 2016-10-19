<script src="//unpkg.com/masonry-layout@4.1/dist/masonry.pkgd.min.js"></script>
<style>
    .grid-item { 
        width: 200px; 
        padding: 5px;
        box-shadow: 2px 2px 2px 2px rgba(0,0,0,0.2);
        margin-bottom: 8px;
        border-radius: 5px;
        
    }
    .grid-item--width2 { 
        width: 400px; 
        
    }
</style>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Cats'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cat'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="cats view large-9 medium-8 columns content">
    <!--<table class="vertical-table">-->
    <!--    <tr>-->
    <!--        <th><?= __('Locate') ?></th>-->
    <!--        <td><?= h($cat->locate) ?></td>-->
    <!--    </tr>-->
    <!--    <tr>-->
    <!--        <th><?= __('Id') ?></th>-->
    <!--        <td><?= $this->Number->format($cat->id) ?></td>-->
    <!--    </tr>-->
    <!--    <tr>-->
    <!--        <th><?= __('Time') ?></th>-->
    <!--        <td><?= $this->Number->format($cat->time) ?></td>-->
    <!--    </tr>-->
    <!--    <tr>-->
    <!--        <th><?= __('Flg') ?></th>-->
    <!--        <td><?= $this->Number->format($cat->flg) ?></td>-->
    <!--    </tr>-->
    <!--    <tr>-->
    <!--        <th><?= __('Status') ?></th>-->
    <!--        <td><?= $this->Number->format($cat->status) ?></td>-->
    <!--    </tr>-->
    <!--    <tr>-->
    <!--        <th><?= __('Ear Shape') ?></th>-->
    <!--        <td><?= $this->Number->format($cat->ear_shape) ?></td>-->
    <!--    </tr>-->
    <!--    <?php if(isset($cat->user)): ?>-->
    <!--    <tr>-->
    <!--        <th><?= __('User') ?></th>-->
    <!--        <td><?= h($cat->user->username) ?></td>-->
    <!--    </tr>-->
    <!--    <?php endif; ?>-->
    <!--</table>-->
    <!--<?php if(isset($cat->address)): ?>-->
    <!-- <div class="row">-->
    <!--    <h4><?= __('Address') ?></h4>-->
    <!--    <?= $this->Text->autoParagraph(h($cat->address)); ?>-->
    <!--</div>-->
    <!--<?php endif; ?>-->
    <div class="row">
        <h4><?= __('Images') ?></h4>
    </div>
	<div class="row">
	    <div class="grid" data-masonry='{ "itemSelector": ".grid-item", "columnWidth": 200px }'>
            <?php foreach ($cat->cat_images as $image): ?>
                <div class="grid-item">
                    <img src="<?= $image->url ?>"></img>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="row">
        <h4><?= __('Comments') ?></h4>
        <?php
            echo $this->Form->create(null, [
                'url' => 'cats/addComment',
                'id' => 'addComment'
            ]);
        ?>
        <?php if ($auth): ?>
        <div class="row">
            <?= $this->Form->input('cat_id', ['type' => 'hidden', 'id' => 'cat_id', 'value' => $cat->id]); ?>
            <div class="view large-10 medium-10 columns content">
                <?= $this->Form->input('comment', ['id' => 'comment', 'label' => false]); ?>
            </div>
            <div class="view large-2 medium-2 columns content">
                <?=$this->Form->submit('投稿', ['id' => 'js-submit-button', 'value'=>'投稿', 'label' => false]); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
        <?php else: ?>
        <div class="row">
            <div class="view large-12 medium-12 columns content">
                <?= $this->Form->input('comment', [
                    'id' => 'comment', 
                    'label' => false,
                    'disabled' => true,
                    'placeholder' => 'ログインするとコメントを投稿できます'
                    
                ]); ?>
            </div>
            <div class="view large-12 medium-12 columns content">
                <a href="/login">ログインする</a>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="row" id="comments">
            <div class="comment" hidden>
                <div class="chat-face">
    			    <img src="" width="30" height="30">
    		    </div>
    		    <div class="chat-area">
        			<div class="chat-fukidashi">
        			  コメントテンプレート
        			</div>
			    </div>
	  	    </div>
	  	</div>
        
        <script  type="text/javascript">
            var updateComments = function(data){
                $("#comment").val('');
                $("#comments").empty();
                $.each(data.comments, function() {
                    var cln = template.comment.clone();
                    cln.find('.chat-fukidashi').text(this.comment);
                    cln.find('.chat-face img').attr(
                        'src', 
                        "//pbs.twimg.com/profile_images/3743464897/25f216ba2e62d6fe043013b58b6dad3a_400x400.jpeg");
                   $("#comments").append(cln);
                   cln.show();
                });
                if($(".comment:first").length > 0){
                    $(".comment:first").hide();
                    $(".comment:first").slideDown(500);
                    $("html,body").animate({scrollTop:$('.comment:first').offset().top});
                }
            };
            
            $(function(){
                // commentTemplate = $("#comments .comment:first").first();
                createTemplate("#comments .comment:first", "comment");
                
                $.post({                                                                            
                    url: '/cats/comments/<?= $cat->id ?>.json',                   
                }).done(function(data) {
                    updateComments(data);
                });         
            });
        
            $('#addComment').on('submit', function() {                                                               
                event.preventDefault();                                                                       
                event.stopPropagation();                                                                      
            
                $.post({                                                                            
                    url: '/cats/addComment.json',                   
                    data: { data: $(this).serialize() },                                                      
                }).done(function(data) {
                    $.post({                                                                            
                        url: '/cats/comments/<?= $cat->id ?>.json',                   
                    }).done(function(data) {
                        updateComments(data);
                    });         
                });                                                                                           
             }); 
        </script>
        <script type="text/javascript">
            $(function(){
                $('.grid').masonry({
                  // options...
                  itemSelector: '.grid-item',
                  columnWidth: 200,
                  gutter: 8
                });
            });
        </script>
    </div>
</div>
