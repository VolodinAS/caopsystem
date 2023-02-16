$( document ).ready(function()
{
    
});

function surveySetSecondary(survey_id)
{
    let TR = $('#survey_tr_' + survey_id)
    if (TR.length > 0)
    {
        logger(TR, 'TR')
        TR.addClass('bg-secondary');
        TR.hide(1000);
    }
}