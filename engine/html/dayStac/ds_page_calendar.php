<?php

?>
<br>
<div id="calendar">
    <div class="input-group input-group-sm" id="calendar_loader">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>

<script defer type="text/javascript">
$( document ).ready(function()
{
    setTimeout(function ()
    {
        loadCalendar( <?=time();?> )
    }, 750);
});
</script>
