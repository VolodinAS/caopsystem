$(document).ready(function(){
    document.getElementById('print-selected').addEventListener('click', function() {
        elem = document.getElementById('print-selected');
        selection = window.getSelection();    // Save the selection.
        range = document.createRange();
        range.selectNodeContents(elem);
        selection.removeAllRanges();          // Remove all ranges from the selection.
        selection.addRange(range);
    });
});