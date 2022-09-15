<?php
    $title;
    $description;
?>
<section class="heading">
    <h1><?= $title ?></h1>
    <?php if(isset($description)): ?>
    <div class=""><?= $description ?></div>
    <?php endif; ?>
</section>