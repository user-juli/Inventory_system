
function suggetion() {

     $('#sug_input').keyup(function(e) {

         var formData = {
             'product_name' : $('input[name=title]').val()
         };

         if(formData['product_name'].length >= 1){
 
           // process the form
           $.ajax({
               type        : 'POST',
               url         : 'ajax.php',
               data        : formData,
               dataType    : 'json',
               encode      : true
           })
               .done(function(data) {
                    $('#result').html(data).fadeIn();
                    $('#result li').click(function() {
 
                    $('#sug_input').val($(this).text());
                    $('#result').fadeOut(500);
                    
                    });
                    
                   $("#sug_input").blur(function(){
                     $("#result").fadeOut(500);
                   });

               });

         } else {

           $("#result").hide();

         };

         e.preventDefault();
     });

} 

  $('#sug-form').submit(function(e) {
        var formData = {
          'p_name' : $('input[name=title]').val()
        };
        // process the form
        $.ajax({
            type        : 'POST',
            url         : 'ajax.php',
            data        : formData,
            dataType    : 'json',
            encode      : true
        })
            .done(function(data) {
              var linea = data;
              //$('#product_info').html(data).show().length;
              $('#product_info').append(linea);
              total();
              $('.datePicker').datepicker('update', new Date());
              
            }).fail(function() {
                $('#product_info').html(data).show();
            });
      e.preventDefault();
  }); 

  function total() {

    $("#product_info").change(function(event) {
    var total = 0;
    $("#product_info .fields").each(function() {
        var qty = +$(this).find('#s_qy').val() || 0;
        var price = +$(this).find('#s_price').val() || 0;
        var iva = (price * 0.19);
        var pst = (price + iva);
        var subtotal = (qty * pst);
        $(this).find("#s_total").val(subtotal);
        if(!isNaN(subtotal))
            total+=subtotal;
    });
    $("#total").val(total);
    });
  }

  $(document).ready(function() {

    $('[data-toggle="tooltip"]').tooltip();
    $('.submenu-toggle').click(function () {
       $(this).parent().children('ul.submenu').toggle(200);
    });
    suggetion();
    total();
    $('.datepicker')
        .datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });
  });

  function pagoOnChange(sel) {
    if (sel.value=="1"){
      divC = document.getElementById("abonar");
      divC.style.display="none";

      divC = document.getElementById("dinero");
      divC.style.display="";

    }if (sel.value=="2"){
      divC = document.getElementById("abonar");
      divC.style.display = "";

      divC = document.getElementById("dinero");
      divC.style.display="none";

    }if (sel.value=="3"){
      divC = document.getElementById("abonar");
      divC.style.display = "none";

      divC = document.getElementById("dinero");
      divC.style.display="none";

    }
  }

 $("#descuento").click(function(){
    var total = $('#total').val();
    var desc = (5 * total) / 100;
    var dt = (total - desc);
    $("#total").val(dt);
  }); 

  $(document).on("click",".eliminar",function(e){
    id = $(this).parents("tr").find("td").eq(0).html();
    var valor = document.getElementById("s_total").value;
    var tot = $("#total").val();
    $(this).parents("tr").fadeOut("normal", function(){
      $(this).remove();
      $("#total").val(tot-valor);
    });
  });

  $('#efe').keyup(function(e) {
    var efectivo = $("#efe").val();
    var total = $("#total").val();
    var cambio = efectivo - total;
    $("#camb").val(cambio);
  });
  
  function restoration() {
    
    $('#fact_input').keyup(function(e) {

        var formD = {
            'fact_name' : $('input[name=title]').val()
        };

        if(formD['fact_name'].length >= 1){

          // process the form
          $.ajax({
              type        : 'POST',
              url         : 'ajax.php',
              data        : formD,
              dataType    : 'json',
              encode      : true
          })
              .done(function(data) {
                  $('#result').html(data).fadeIn();
                  $('#result li').click(function() {

                  $('#fact_input').val($(this).text());
                  $('#result').fadeOut(500);
                  
                  });
                  
                  $("#fact_input").blur(function(){
                    $("#result").fadeOut(500);
                  });

              });

        } else {

          $("#result").hide();

        };

        e.preventDefault();
    });
  
  } 

  $('#fact-form').submit(function(e) {
    var formD = {
      'f_name' : $('input[name=title]').val()
    };
    // process the form
    $.ajax({
        type        : 'POST',
        url         : 'ajax.php',
        data        : formD,
        dataType    : 'json',
        encode      : true
    })
      .done(function(data) {
        var linea = data;
        $('#sale_info').append(linea);
        
      }).fail(function() {
          $('#sale_info').html(data).show();
      });
    e.preventDefault();
  }); 

 
/* 
$(document).on("click",".devolver",function(e){
  var fila = $(this).parents("tr");
  var factura = fila.find(".sale").val();
  var producto = fila.find(".pd").val();
  var cantidad = fila.find(".qy").val();
  var total = fila.find(".tot").val();
          
  console.log(`Factura ${factura} Producto ${producto} Cantidad ${cantidad} Total ${total}`);
  
  }); */


$(document).on("click",".devolver",function(e){
  var fila = $(this).parents("tr");
  var factura = fila.find(".sale").val();
  var producto = fila.find(".pd").val();
  var cantidad = fila.find(".qy").val();
  var total = fila.find(".tot").val();
  
  var parametros = {
    factura: factura,
    producto: producto,
    cantidad: cantidad,
    total: total
  };

  $.ajax({
    type:  'POST', //método de envio
    data:  parametros, //datos que se envian a traves de ajax
    url:   'save_return.php', //archivo que recibe la peticion
    success:  function (response) {
      location.href='returns.php';
      
    }
  });
  
  }); 






