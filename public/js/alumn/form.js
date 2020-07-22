//Sirve para habilitar y deshabilitar campos dependiendo la opcion que seleccionen
$("#TrabajaActualmente").change(function()
{
  var flag = $(this).val();
  if( flag == "1")
  {
    $("#Puesto").removeAttr("readonly");
    $("#SueldoMensualAlumno").removeAttr("readonly");

  }
  else
  {
    $("#Puesto").attr("readonly","readonly");
    $("#SueldoMensualAlumno").attr("readonly","readonly");
  }
});

$("#TransporteUniversidad").change(function()
{
  var flag = $(this).val();
  if($(this).val() === "1")
  {
    $("#Transporte").removeAttr("readonly");
  }
  else
  {
    $("#Transporte").attr("readonly","readonly");   
  }
}); 

$(document).ready(function()
{
    //se verifica si hay datos en el local sotrage 
    var data = JSON.parse(localStorage.getItem('tempData'));

    //le pone al valor al input
    if(data != null)
    {
        swal.fire({
            title: "Parece que la última vez no terminaste de completar el fromulario",
            text: "Si cambiaste algunos datos y avanzaste a pasos posteriores algunos cambios se guardaron ",
            type: 'success',
        });
        data.forEach(element =>
        {
            //se le pone el valor por defecto a los input desde local storage
            if(element['type'] == 'INPUT')
            {
              $('#'+element['name']).val(element['value']);
            }
            else
            {
              let $option = $('<option />', {
                  text: element["text"],
                  value: element["value"],
                  selected: ""
              });
              $('#'+element['name']).prepend($option);
            }
        });
    }

    //bloqueo el boton para que al hacer click no se envie el formulario
    $(".button-next").click(function(event){
        event.preventDefault();
    });
      
    $(".button-back").click(function(event){
        event.preventDefault();
    });

    $("#button-sumbit").click(function(event){
        var route = '/alumn/form/save';
        var token = $('#token').val();
        var data = new FormData();
        var tempData = JSON.parse(localStorage.getItem('tempData'));
        data.append('data', JSON.stringify(tempData));
        data.append('recaptcha',grecaptcha.getResponse());
        $.ajax({
            url:route,
            headers:{'X-CSRF-TOKEN': token},
            method:'POST',
            data:data,
            cache:false,
            contentType:false,
            processData:false,
            success:function(response)
            {
                if(response == 'ok')
                {
                    localStorage.removeItem('tempData');
                    window.location = '/alumn/payment';
                }
                else
                {
                    toastr.error("Tiene que verificar que no es un robot");
                }
        }});
    });
});

//aqui guardo los items que cambian
var changeItems = [];

//cuando se modifica un campo se verfica si está en el arreglo y se actualiza el valor, si no lo agrega al arreglo
var modified;
$("input, select").change(function ()
{   
  modified = true; 
  var value = $(this).val(); 
  var name =  $(this).prop('name');
  var elementType = $(this)[0].tagName;

  // en caso de ser un select guarda el el texto  para ponerlo con jquery desde local storage
  if(elementType == 'SELECT')
  {
    var textSelect = $('#'+name+' option:selected').text();
  }
  else
  {
    var textSelect = null;
  }     

  let tempIndex = checkExistThisName(name);
  let field = 
  {
   'name' : name, 
   'value':value.toUpperCase() ,
   'type' :  elementType,
   'text' : textSelect 
  };    

  if(tempIndex >= 0)
  {
    changeItems[tempIndex] = field;
  }
  else
  {
    changeItems.push(field);
  }    
}); 

// este método determina si ya existe en el arreglo y si es asi devuelve la posión en que se encuentra
function checkExistThisName(name)
{
  for (let index = 0; index < changeItems.length; index++) 
  {
      if(changeItems[index]['name'] == name)
      {
          return index;
      }
  }
  return -1;
}


//se ubica donde se da clic y mediante clases de css hace que se meuva el formulario
let form = document.querySelector('.form-inscription');
let progressbarOptions = document.querySelectorAll('.progressbar-option');
  
form.addEventListener('click',function(e)
{
   let element = e.target;
   let isButtonNext = element.classList.contains('button-next');
   let isButtonBack = element.classList.contains('button-back');
   let isButtonSumbit = element.classList.contains('sumbit');
  
   if( isButtonBack || isButtonNext)
   {
      let currentStep = document.getElementById('step-' + element.dataset.step);
      let goToStep = document.getElementById('step-' + element.dataset.to_step);

      // en este arreglo se guardan todos los cmapos y el valor para verifcar de que alguno no quede vacio
      //nota:solo verfica con forma la base de datos ,  los que dice not null
      //nota2: si quieres que se valide el campo, en el html hay una propiedad llamada nullable cambiala a no y entonces se validará
      var stepItems = [];
      let itemCount = 0;

      // dentro de esta funcion del selector se guardan todos los inputs del paso en el que está
      $('#step-' + element.dataset.step + " input").each(function()
      {
          let itemName =  $(this).attr('name'); 
          stepItems[itemCount] = {
              "name"      : itemName,
              "value"     : $(this).val(),
              "nullable"  : $(this).attr('isnullable')
          };
          itemCount++;
      });

      // dentro de esta función del selector se guardan todos los select del paso en el que está
      $('#step-' + element.dataset.step + " select").each(function()
      {
          stepItems[itemCount] = 
          {
              "name"      : $(this).attr('name'),
              "value"     : $(this).val(),
              "nullable"  : $(this).attr('isnullable')
          };
        
          itemCount++;
      });

      if(isButtonNext)
      {
        // aqui se recorre el arreglo y si es null o el length es cero incrementa el error count  
        var errorCount = 0;
        for (var i = 0; i < stepItems.length; i++) 
        {    
          if(stepItems[i]['nullable'] == 'no')
          {
            if( stepItems[i]['value'] == null)
            {
                    toastr.error("Tiene que llenar el campo " + stepItems[i]['name']);
                    errorCount++;

            }
            else if( stepItems[i]['value'].length == 0 )
            {
              toastr.error("Tiene que llenar el campo " + stepItems[i]['name']);
              errorCount++;
            }
          }
        }
            
        //para que pueda cmabiar de paso el error count debe ser igual a cero, es decir que no haya errores
        if(errorCount == 0 )
        {
          if(changeItems.length != 0)
          {
            if(localStorage.getItem('tempData') == null)
            {
              localStorage.setItem('tempData', JSON.stringify(changeItems));
            }
            else
            {
              localStorage.removeItem('tempData');
              localStorage.setItem('tempData' , JSON.stringify(changeItems));
            }

          }

          //aqui se hace el cambio del progres bar y cambio de paso del formulario hacia adelante
          currentStep.classList.add('disabled');
          currentStep.classList.remove('active');
          goToStep.classList.add('active');
          currentStep.classList.add('to-left');
          progressbarOptions[element.dataset.to_step - 1].classList.add('active');
          currentStep.classList.add('inactive');
          goToStep.classList.remove('inactive');

        }
      }
      else if(isButtonBack)
      {
        //aqui se hace el cambio del progres bar y cambio de paso del formulario hacia atras
        currentStep.classList.add('disabled');
        currentStep.classList.remove('active');
        goToStep.classList.add('active');
        goToStep.classList.remove('to-left');
        progressbarOptions[element.dataset.step - 1].classList.remove('active');
        currentStep.classList.add('inactive');
        goToStep.classList.remove('inactive');
      }
   }
});

$(".select2").select2();

$('.phone').mask('0000000000');

$('.eleven').mask('00000000000');

$('.codigo').mask('00000');

$("#EstadoDom").change(function()
{
  var EstadoId = $(this).val();
  getEstados("MunicipioDom",EstadoId);
});

$("#EstadoNac").change(function()
{
  var EstadoId = $(this).val();
  getEstados("MunicipioNac",EstadoId);
});

function getEstados(elementName, EstadoId)
{
  var route = 'form/getMunicipio';
  var token = $('#token').val();
  var data = new FormData();
  data.append("EstadoId",EstadoId);
  $.ajax({
      url:route,
      headers:{'X-CSRF-TOKEN': token},
      method:'POST',
      data:data,
      cache:false,
      contentType:false,
      processData:false,
      success:function(response)
      {
          $('#'+elementName).empty().append('whatever');
          for (var i = 0; i < response.length; i++)
          {
            let $option = $('<option />', {
                text: response[i]["Nombre"],
                value: response[i]["MunicipioId"],
                selected: ""
            });
            $('#'+elementName).prepend($option);
          }
      }
  });
}

$('.date').inputmask({"mask": "99-99-9999", "placeholder": 'dd-mm-yyyy'});

$(".date").blur(function()
{
  var text = $(this).val();
  var aux = text.split("-");
  console.log(aux);
  if(parseInt(aux[1])>12)
  {
    if(aux[2]=="yyyy")
    {
      $(this).val(aux[0]+"-12-1999");
      toastr.warning("Solo existen 12 meses en el año y no especificaste un año de nacimiento");
      $(this).focus();
    }
    else
    {
      $(this).val(aux[0]+"-12-"+aux[2]);
      toastr.warning("Solo existen 12 meses en el año");
    }
  }
  else
  {
    if(aux[2]=="yyyy")
    {
      $(this).val(aux[0]+"-"+aux[1]+"-1999");
      toastr.warning("no especifico un año de nacimiento");
      $(this).focus();
    }
  } 
});