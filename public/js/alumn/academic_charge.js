let token = $("#token").val();
const loadAcademicChargeTable = function(peroidId){

    let route = `/alumn/academic-charge/show/${peroidId}`;

    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: "post",
        dataType: "json",
        data: { enrollment: $('#searchAlumn').val() },
        success: function (response) {
        $("#tableBody").html(response);
        }
    });

   
}


const peroidId = $('#period').val();
loadAcademicChargeTable(peroidId);

$('#period').on('change', function() {
    const period = this.value;
    loadAcademicChargeTable(period);
});



const printTable = function(){

    $elementToPrint = document.getElementById('sectionToPrint');
    const periodName = $('select[name="period"] option:selected').text();


    const config = {
        margin:1,
        filename: `mi_carga_periodo_${ periodName.trim() }.pdf`,
        image:{
            type:'jpeg',
            quality : 1.0,
        },
        html2canvas : {
            scale: 2,
            letterRendering: true
        },
        jsPDF:{
            unit: 'in',
            format:'a3',
            orientation: 'portrait'
        }
    };
    



    html2pdf().set(config).from($elementToPrint).save()
    .catch(err => console.log(err));
 };

$("#print").click(function(){
    printTable();
});

