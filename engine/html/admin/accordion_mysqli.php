<?php
$DivideSettings = array(
	'title' => 'Мои функции для MySQLi',
	'dom_title' => 'mysqli',
	'updated_date' => '22.10.2022',
	'updated_time' => '16:18'
);
?>
<?php
spoiler_begin($DivideSettings['title'], $DivideSettings['dom_title'], 'collapse', '', 'admin-spoiler');
?>
<h4>Обновлено: <?=$DivideSettings['updated_date'];?> <?=$DivideSettings['updated_time'];?></h4>

<?php
foreach (mysqli_about() as $func=>$about)
{
    $usage = array_keys($about)[0];
    $desc = array_values($about)[0];
?>
<ul>
    <li>
        <b><?=$func;?>:</b> <?=$usage;?><br>
        <?=$desc;?>
    </li>
</ul>
<?php
}
?>

<?php
spoiler_end();
?>
