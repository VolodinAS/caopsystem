const mkb = document.getElementsByClassName('mkbDiagnosis');

const cyrcode = 'ФИСВУАПРШОЛДЬТЩЗЙКЫМЯГ';
const latcode = 'ABCDEFGHIJKLMNOPQRSTVZU'
let icode = '';
let rcode = '';
mkb.addEventListener('keydown', (e) => {
    rcode = '';
    icode = e.key;
    if (icode.length == 1) {
        const ind = cyrcode.indexOf(icode.toUpperCase())
        if (ind>=0) {
            rcode = latcode[ind];
        } else if ('a'<=icode && icode<='z') {
            rcode = icode.toUpperCase();
        }
        if (! (/[A-Z0-9\.]/.test(rcode || icode))) {
            e.preventDefault();
        }

    }
})
mkb.addEventListener('input', (e) => {
    if (rcode) {
        const is = mkb.value.indexOf(icode)
        mkb.value = mkb.value.replace(icode, rcode);
        mkb.setSelectionRange(is+1, is+1);
    }
});