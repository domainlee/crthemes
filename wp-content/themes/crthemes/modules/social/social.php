<?php
    $team_image = get_sub_field('team_image');
?>

<section class="team">
    <div id="particles-js2" class="particle-js"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <figure class="lazy team__image wp2" data-src="<?= $team_image['url'] ?>"></figure>
            </div>
        </div>
    </div>
</section>
