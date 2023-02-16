<div class="my-5">
    <ul class="row row-cols-3 row-cols-sm-4 row-cols-lg-6 row-cols-xl-8 list-unstyled list">
        <?php
        foreach ($IconsArray as $icon_name)
        {
?>
        <li class="col mb-4">
            <div class="p-3 py-4 mb-2 bg-light text-center rounded">
                <?php
                echo constant($icon_name);
                ?>
            </div>
            <div class="name text-muted text-decoration-none text-center pt-1"><?=$icon_name;?></div>
        </li>
<?php
        }
        ?>
    </ul>
</div>