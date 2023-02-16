<?php

//debug($_SESSION);

$date_from = $date_to = '';
if ( isset($_SESSION['stat_howmany_phys_face_cured']) )
{
	$date_from = $_SESSION['stat_howmany_phys_face_cured']['date_from'];
	$date_to = $_SESSION['stat_howmany_phys_face_cured']['date_to'];
}

// через ajax обновляем сессионные данные, по возврату - window.open()
spoiler_begin('Сколько было физических лиц пролечено', 'stat_howmany_phys_face_cured');
{
?>
	<form action="" method="post" id="form_stat_howmany_phys_face_cured">
		<div class="row">
			<div class="col font-weight-bolder">
				Выберите сроки:
			</div>

			<div class="col">
				<input type="date" class="form-control form-control-lg" id="stat_howmany_phys_face_cured_from" name="stat_howmany_phys_face_cured_from" value="<?=$date_from;?>">
			</div>

			<div class="col">
				<input type="date" class="form-control form-control-lg" id="stat_howmany_phys_face_cured_to" name="stat_howmany_phys_face_cured_to" value="<?=$date_to;?>">
			</div>

			<div class="col">
				<button type="button" class="btn btn-primary btn-lg btn_stat_howmany_phys_face_cured">Создать отчёт</button>
			</div>
		</div>
	</form>
<?php
}
spoiler_end();

spoiler_begin('Сколько было визитов за период', 'stat_howmany_visits');
{
	?>
    <form action="" method="post" id="form_stat_howmany_visits">
        <div class="row">
            <div class="col font-weight-bolder">
                Выберите сроки:
            </div>

            <div class="col">
                <input type="date" class="form-control form-control-lg" id="stat_howmany_visits_from" name="stat_howmany_visits_from" value="<?=$date_from;?>">
            </div>

            <div class="col">
                <input type="date" class="form-control form-control-lg" id="stat_howmany_visits_to" name="stat_howmany_visits_to" value="<?=$date_to;?>">
            </div>

            <div class="col">
                <button type="button" class="btn btn-primary btn-lg btn_stat_howmany_visits">Создать отчёт</button>
            </div>
        </div>
    </form>
	<?php
}
spoiler_end();
?>

<script defer src="/engine/js/dayStac/dayStac_statistic.js?<?=rand(0,999999);?>" type="text/javascript"></script>