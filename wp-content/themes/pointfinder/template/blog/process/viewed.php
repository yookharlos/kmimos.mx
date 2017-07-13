<?php

$args = array(
    'post_type'=>'post',
    'posts_per_page' =>5,
    'paged'=>get_query_var('paged'),
    //'meta_key' => 'post_views_count',
    //'orderby' => 'meta_value_num',
    /*
    'post_type'  => 'post_type',
    'meta_key'   => 'age',
    'orderby'    => 'meta_value_num',
    'order'      => 'ASC',
    */
);

$iblogs = 0;
$blogs = new wp_query($args);
while($blogs->have_posts()){
    $blogs->the_post();
    $blogs_imagen=wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'single-post-thumbnail');
    $blogs_thumbnail=wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'single-post-thumbnail');
    $blogs_custom = get_post_custom($post->ID);
    //$SQLpost_viewed='visto '.wpb_get_post_views($post->ID).' veces';//get_the_ID()
    $iblogs++;

    $blogs_imagen=str_replace('http://kmimos.dev.mx/','https://kmimos.com.mx/',$blogs_imagen);
    $blogs_thumbnail=str_replace('http://kmimos.dev.mx/','https://kmimos.com.mx/',$blogs_thumbnail);

    $blogs_category=wp_get_post_terms($post->ID,'category',array('orderby' => 'name', 'order' => 'ASC'));
    $blogs_category_name=array();
    foreach($blogs_category as $category){
        $blogs_category_id = $category->term_id;
        $blogs_category_name[]=$category->name;
    }



    echo '<div class="post scroll_animate" data-position="self">';
    echo '<div class="image easyload" data-original="'.$blogs_imagen[0].'" style="background-image:url('.$blogs_thumbnail[0].');"></div>';

    echo '<div class="detail">';
    echo '<div class="title">'.the_title('','',false).'</div>';
    echo '<div class="autor scroll_animate" data-position="right">'.$blogs_custom['meta_blog_author_name'][0].'</div>';
    echo '<div class="content scroll_animate" data-position="self">'.wp_trim_words(strip_tags(get_the_content()), 50, $more = ' ...').'</div>';

    if(count($blogs_category)>0){
        echo '<div class="category scroll_animate" data-position="self">'.implode(', ',$blogs_category_name).'</div>';
    }

    echo '<div class="button more scroll_animate" data-position="self">'.__('MORE').'</div>';
    echo '</div>';

    echo '<a class="absolute" href="'.esc_url(get_permalink()).'" title="'.the_title('','',false).'"></a>';
    echo '</div>';
}

wp_reset_postdata();
?>

<script type="text/javascript">
    jQuery(document).on('click','#blog_viewed .control .icon', function(){
        pviewed=jQuery(this).index();
        show_viewed();
    });

    function show_viewed(){
        clearTimeout(tviewed);
        var posts=jQuery('#blog_viewed .post');
        var control=jQuery('#blog_viewed .control');

        if(control.find('.icon').length==0){
            posts.each(function(){
                control.append('<div class="icon"></div>');
            });
        }

        if(pviewed>=0 && posts.length>pviewed){
            posts.removeClass('show');
            posts.eq(pviewed).addClass('show');

            control.find('.icon').removeClass('show');
            control.find('.icon').eq(pviewed).addClass('show');
        }else{
            pviewed=0;
            show_viewed();
        }

        pviewed++;
        tviewed = setTimeout('show_viewed(pviewed)', 5000);
    }

    pviewed=0;
    var tviewed = setTimeout('show_viewed(pviewed)', 0);
</script>