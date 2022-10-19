<?php
    $headline = get_sub_field('faq_heading');
    $faq_column_one = get_sub_field('faq_column_one');
    $faq_column_two = get_sub_field('faq_column_two');
?>

<section class="faq py-6">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="heading__default to-top"><?= $headline ?></h2>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-6">
                <?php if(!empty($faq_column_one)): ?>
                    <?php foreach ($faq_column_one as $v): ?>
                        <div class="faq__item mb-5">
                            <h4 class="faq__question mb-3"><?= $v['title_question'] ?></h4>
                            <div class="faq__answer"><?= $v['content_answer'] ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="col-6">
                <?php if(!empty($faq_column_two)): ?>
                    <?php foreach ($faq_column_two as $v): ?>
                        <div class="faq__item mb-5">
                            <h4 class="faq__question mb-3"><?= $v['title_question'] ?></h4>
                            <div class="faq__answer"><?= $v['content_answer'] ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
