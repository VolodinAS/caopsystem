$(document).ready(function(){
    
});

function importDiagnosis()
{
    var selectDiag = $('#ds');
    var DID = selectDiag.val();
    var inputElemText = $('#dstext_'+DID);
    var inputElemMKB = $('#dsmkb_'+DID);
    if ( inputElemText.length > 0 )
    {
        var inputElemMorph = $('#dsmorph_'+DID);
        var inputElemTextValue = inputElemText.val();
        var inputElemMorphValue = inputElemMorph.val();
        var inputElemMKBValue = inputElemMKB.val();

        $('#rs_ds_mkb').val(inputElemMKBValue);
        $("#rs_ds_mkb").trigger("change");

        $('#rs_ds_text').val(inputElemTextValue);
        $("#rs_ds_text").trigger("change");

        $('#rs_morphology').val(inputElemMorphValue);
        $("#rs_morphology").trigger("change");
    }
}

function parseOncoInfo()
{
    var oncoInfo = $('#oncoinfo');
    var oncoInfoHTML = oncoInfo.val();
    $.ajax({
        url: '/processor/routeSheet_oncoInfo',
        data: {oncoInfo: oncoInfoHTML},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json.result === true) {

                if (json.patient_diagnosis_data.length > 0)
                {
                    for (var i=0; i<json.patient_diagnosis_data.length; i++) {
                        var patient_data = json.patient_diagnosis_data[i];
                        if ( patient_data.field_place == 'rs' )
                        {
                            var input_field = $("div[data-import='"+patient_data.field_name+"']");
                            if ( input_field.length === 1 )
                            {
                                input_field.html("<b>Данные найдены:</b><br>" + patient_data.value)
                                var main_data = $('#' + patient_data.field_name);
                                if ( main_data.length == 1 )
                                {
                                    main_data.val(patient_data.value);
                                    main_data.change();
                                }
                            }
                        }
                    }

                }
            } else
            {
                alert(json.msg);
            }
        }
    });
}