<div class="news__tab-right">
        <?php
        $right_sidebar = get_field('news_sidebar', 'option');
            if(!empty($right_sidebar)):
            foreach ($right_sidebar as $post):/*
                print_r($right_sidebar)*/
                ?>
                <div class="sidebar_head">
                     <div class="sidebar_title"><?= $post['sidebar_title'] ?> </div>
                 </div>
                <?php

                if(!empty($post['sidebar_news'])):
                    foreach ($post['sidebar_news'] as $item):
                        $post_title = $item->post_title;
                        $featured_img_url = get_the_post_thumbnail_url($item->ID,'full');
                        $link = get_permalink($item->ID);
                        $cate = get_the_category($item->ID);
                        $time = $item->post_date;
                    ?>
                
                      <div class="sidebar__news--item">
                            <a href="<?= $link ?>">
                                <figure class="sidebar__image lazy" data-src="<?= $featured_img_url ?>"></figure>
                            </a>
                            <div class="sidebar__news--item__box">
                                <p class="sidebar__news__title"><a href="<?= $link ?>" title="<?= $post_title ?>"><?= substr($post_title,0,50) ?>
                                    <?php if(strlen($post_title) > 50): ?><span>...</span><?php endif; ?></a></p>
                                <?php foreach ($cate as $key):

                                ?>
                                <span class="single-news__categories"><a href="<?= get_category_link($key->cat_ID) ?>"><?= $key->cat_name // Separated by commas ?></a></span>
                                <?php endforeach;
                                ?>
                                <span class="sidebar__news__date"><?= substr($time,0,10) ?></span>
                            </div>
                      </div>
                 <?php 
                    endforeach;
                endif;
            endforeach;
        endif;
          ?>
    </div>