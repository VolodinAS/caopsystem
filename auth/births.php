<br>
<?php

$DoctorsListBirths = getarr(CAOP_DOCTOR, "doctor_isBirth='1'", "ORDER BY doctor_birth_unix ASC");

for ($index = 0; $index < count($DoctorsListBirths); $index++)
{
	$birth_data = $DoctorsListBirths[$index]['doctor_birth'];
	$bd = explode('.', $birth_data);
	$bd_unix = mktime(0, 0, 0, $bd[1], $bd[0], date('Y') + ($bd[1] . $bd[0] <= date('md')));
	$days_until = ceil(($bd_unix - time()) / 86400);
	
	if ($days_until == 365)
	{
		$days_until = 0;
	}
	
	$year = date('Y', time());
	
	$age = (int)$year - (int)$bd[2];
	
	$DoctorsListBirths[$index]['days_until'] = $days_until;
	$DoctorsListBirths[$index]['age'] = $age;
}
$DoctorsListBirths = array_orderby($DoctorsListBirths, 'days_until', SORT_ASC);

foreach ($DoctorsListBirths as $doctorsListBirth)
{
//	debug($doctorsListBirth);
	?>
    <h5>
        <img src="/engine/images/icons/birth.png"
             width="14"
             alt="">
		<?= docNameShort($doctorsListBirth, 'famimot'); ?> (<?=$doctorsListBirth['doctor_duty'];?>),
		<?= $doctorsListBirth['doctor_birth']; ?>,
                                                          через <?= $doctorsListBirth['days_until']; ?>
		<?= wordEnd($doctorsListBirth['days_until'], 'день', 'дня', 'дней'); ?>
                                                          исполнится <?= $doctorsListBirth['age']; ?> <?= wordEnd($doctorsListBirth['age'], 'год', 'года', 'лет'); ?>
    </h5><br/>
	<?php
}