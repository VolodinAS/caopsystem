$(document).ready(function() {



});

function setNurseDefault(nurseId)
{
	$.ajax({
		url: '/processor/setNurseDefault',
		data: {nurseId: nurseId},
		dataType: 'json',
		type: 'post',
		success: function (json)
		{
			if (json)
			{
				if (json.stat == true)
				{
					window.location.reload();
				}
			}
		}
	});
}

function setPeriod(doctor_param, doctor_value)
{
	$.ajax({
		url: '/processor/setPeriod',
		data: {doctor_param: doctor_param, doctor_value: doctor_value},
		dataType: 'json',
		type: 'post',
		success: function (json)
		{
			if (json)
			{
				if (json.stat == true)
				{
					window.location.reload();
				}
			}
		}
	});
}