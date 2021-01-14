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

