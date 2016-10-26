<script src="https://unpkg.com/imagesloaded@4.1/imagesloaded.pkgd.min.js"></script>
<script src="//unpkg.com/masonry-layout@4.1/dist/masonry.pkgd.min.js"></script>
<script src="/js/jquery.infinitescroll.js"></script>
<style>
    .grid-sizer, .grid-item { 
        width: 48%;
        padding: 5px;
        box-shadow: 2px 2px 2px 2px rgba(0,0,0,0.2);
        margin-bottom: 8px;
        border-radius: 5px;
    }
    .grid-sizer {
        visibility: hidden;
    }
    .grid-item--width2 { 
        width: 80%; 
    }
    
    .gutter-sizer { 
        width: 2%; 
    }
    
    .grid-buttons{
        margin-top: 3px;
    }
    
    .grid-comment{
        font-size:12px;
        padding: 2px;
        padding-left: 10px;
    }
    
    .btn-default {
        background-image: none;
    }
    
    .grid {
        padding-bottom: 150px;
    }
    
    #add-neko{
        background-color: white;
        border-width: 1px;
        border-color: gray;
        position: fixed;
        bottom: 0;
        width: 100%;
        height: 50px;
    }
    
    #add-neko button {
      position: absolute;
      top: 50%;
      left: 40%;
      transform: translate(-20%, -50%);
    }
    
    .user {
        width: 100%;
        text-align: right;
        font-size: 12px;
    }
    
    .user a{
        color: gray;
    }
    
    .favorited {
        color: #ffa500 !important;
    }
}

</style>
<?php 
    $ear_images = ['normal', 'donno', 'trimmed_right', 'trimmed_left'];
?>
<div class="row">
    <div class="grid">
        <div class="grid-sizer"></div>
        <div class="gutter-sizer"></div>
        <?php foreach ($cats as $cat): ?>
            <?php foreach ($cat->cat_images as $imgIdx => $image): ?>
                <?php if($imgIdx >= 1) break; ?>
                <div class="grid-item">
                    <?php if($image->thumbnail):?>
                        <div><a title="<a href='/cats/view/<?=$cat->id ?>'>もっと見る</a>" class='gallery' href="<?= $image->url ?>"><img src="<?= $image->thumbnail ?>" width="100%"></img></a></div>
                    <?php else: ?>
                        <div><a title="<a href='/cats/view/<?=$cat->id ?>'>もっと見る</a>" class='gallery' href="<?= $image->url ?>"><img src="<?= $image->url ?>" width="100%"></img></a></div>
                    <?php endif; ?>
                    <?php if(!empty($cat->user->username)): ?>
                        <div class="user"><a href="/profiles/user/<?= h($cat->user->username) ?>" >@<?= h($cat->user->username) ?></a></div>
                    <?php endif; ?>
                    <div>
                        <?php foreach ($cat->comments as $idx => $comment): ?>
                            <?php if($idx >= 3) break; ?>
                            <div class="grid-comment"><?= $comment->comment ?></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="grid-buttons">
                        <img width='20px' src='/img/cat_ears/<?="cat_".$ear_images[$cat->ear_shape].".png" ?>'>
                        <?php if(!count(array_filter($cat->favorites, function($v){
                            return $v["users_id"]===$this->viewVars['auth']["id"];
                        }))>0): 
                            ?>
                            <a class="favorite btn btn-default btn-sm" href="/cats/favorite/<?=$cat->id ?>" role="button">
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="count"><?=count($cat->favorites) ?></span>
                            </a>
                        <?php else: ?>
                            <a class="favorite favorited btn btn-default btn-sm" href="/cats/favorite/<?=$cat->id ?>" role="button">
                                  <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                  <span class="count"> <?=count($cat->favorites) ?></span>
                            </a>
                        <?php endif; ?>
                        <a href="/cats/view/<?=$cat->id ?>">
                            <button type="button" class="btn btn-default btn-sm">
                              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> <?=count($cat->comments) ?>
                            </button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>
<div class='loading-selector' style='margin-top: -150px;'>
</div>
<div class='row' id="page-nav">
    <?php echo $this->Paginator->next('もっと見る', ['class'=>'next']); ?>
</div>
<div id="add-neko">
    <a class="btn btn-default btn-sm" href="<?=$this->Url->build('/', false); ?>cats/add" role="button">
        <span class="glyphicon glyphicon-camera" aria-hidden="true"></span> 投稿する
    </a>
</div>

<script type="text/javascript">
$(function(){
    
    function initialize(){
        lightboxing();
        favoriting();
    }
    
    function lightboxing(){
      
        $('a.gallery').magnificPopup({
            type:'image',
            gallery:{
                enabled:true
                
            },
            titleSrc: 'title'
            
        });
    }
    
    function favoriting(){
        //fav button
    	$(".favorite").click(function(e){
    	    console.log("fav");
    	    event.preventDefault();
            event.stopPropagation();
            console.log(e.currentTarget.href);
            
            var t = $(this);
            
            $.ajax({
                type: 'POST',
                url: e.currentTarget.href+".json",
                success: function(response){
                    if(response.cat){
                        console.log(response.cat.favorites.length);
                        t.children('.count').text(response.cat.favorites.length);
                        t.prependClass('favorited');
                    }
                },
                error: function(response){
                    console.log(response);
                }
            });
    	}); 
    }
    
    var $container = $('.grid');
	$container.imagesLoaded(function(){
		$container.masonry({
		  //  isFitWidth: true,
			isAnimated: true,
// 			isResizable: true,
			columnWidth: '.grid-sizer',
            gutter: '.gutter-sizer',
            itemSelector: '.grid-item',
            percentPosition: true
		});
		initialize();
	});
	
    $container.infinitescroll({
        navSelector : '.next', // ナビゲーション
        nextSelector : '.next a', // 次ページへのリンク
        itemSelector : '.grid-item', // 次ページ内で探す要素
        debug         : true,
        dataType      : 'html',
        loading: {
            selector: '.loading-selector',
            finishedMsg: 'No more posts to load.',
            img: '<?=$this->Url->build('/', false); ?>img/ajax-loader.gif'
        }
    }, function( newElements ) {
		var $newElems = $( newElements );
		$newElems.imagesLoaded(function(){
		    $container.masonry( 'appended', $newElems, true ); 
		    initialize();
		});
	});

});
</script>

<link rel="stylesheet" type="text/css" href="<?php echo$this->Url->build('/', false); ?>css/lightbox/magnific-popup.css"> 
<script src="<?php echo$this->Url->build('/', false); ?>js/lightbox/jquery.magnific-popup.js"></script>

