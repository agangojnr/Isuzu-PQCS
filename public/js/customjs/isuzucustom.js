
$(function () {
    "use strict";
//SUM DIRECT AND INDIRECT
$(document).on('change keyup blur', '.hrs', function () {

    var id_arr = $(this).attr('id');

    var id = id_arr.split('_');
    var directmh = $('#direct_' + id[1]).val();
    var indirectmh = $('#indirect_' + id[1]).val();
    var loanedmh = $('#loaned_' + id[1]).val();
    if (directmh == '') {
        directmh = 0;
    }
    if (indirectmh == '') {
        indirectmh = 0;
    }
    if (loanedmh == '') {
        loanedmh = 0;
    }
    var tot = parseInt(directmh) + parseInt(indirectmh) + parseInt(loanedmh);
    if(tot > 12){
        tot = 0;
        $('#direct_' + id[1]).val("");
        $('#indirect_' + id[1]).val("");
        $('#loaned_' + id[1]).val("");
        $('#total' + id[1]).html(tot);
    }

    //console.log(tot);
    /*if (tot == 0) {
        $('#mrk_' + id[1]).attr('disabled', false);
    } else {
        $('#mrk_' + id[1]).attr('checked', true);
        $('#mrk_' + id[1]).attr('disabled', true);
    }*/
    $('#total' + id[1]).html(tot);

});

//LOADING MODAL MARKING ATTENDANCE
$('.add-loan').on('click', function(event) {
    event.preventDefault();
    $('.add-tsk').show();
    $('.edit-tsk').hide();
    $('#addTaskModal').modal('show');
  });
});


//MARK ATTENDANCE PAGE
$(function(){
    'use strict'
    var today = new Date();
    $('.singledate').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    dateFormat: 'dd/mm/yy',
    endDate: "today",
    maxDate: today
    });
});

//HOLIDAY DATE PICKER
$(function(){
    'use strict'
    $('.holiday').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    dateFormat: 'dd/mm/yy',

    });
});

//SET TARGETS
$(function(){
    'use strict'
    var today = new Date();
    $('.singledate1').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    dateFormat: 'dd/mm/yy',
    });
});

$(function(){
$("#datepicker").datepicker({
    showDropdowns: true,
    format: "MM yyyy",
    viewMode: "years",
    minViewMode: "months"
    });
});

$(document).on('change', '.recshop', function () {

    var id_arr = $(this).attr('id');
    var id = id_arr.split('_');
    //var loanedmh = $('#loaned_' + id[1]).attr('disabled', true);
    var recshop = parseInt($('#recshop_' + id[1]).val());
    if(recshop == 0){
        $('#loaned_' + id[1]).val("0");
        $('#loaned_' + id[1]).attr('readonly', true);
    }else{
        $('#loaned_' + id[1]).attr('readonly', false);
    }

});

//DISRUPTION INPUT

$(document).on('change', '#myonoffswitch', function () {
    var dd = $('#myonoffswitch')[0].checked;

    if(dd==true){
        $('.disrupt').attr('readonly', false);
    }else{
        $('.disrupt').val("");
        $('.disrupt').attr('readonly', true);
    }

});

//EDITING STANDARD WORKING HOURS
/*$(document).on('click', '.stdhr', function () {

    var text = $(this).text();
    var id_arr = $(this).attr('id');
    var id_arr = $(this).attr('id');
    var id = id_arr.split('_');

    $(this).html("<input type='text' class='form-control txtstdhr' value='"+text+"'>");
    $(this).focus();
    var id_arr = $(this).attr('id');
    var id = id_arr.split('_');

    var modelid = id[1];
    var shopid = id[2];

    //var sc =
    //var recshop = parseInt($('#stdhr_' + id[1]).text());
    /*if(recshop == 0){
        $('#loaned_' + id[1]).val("0");
        $('#loaned_' + id[1]).attr('readonly', true);
    }else{
        $('#loaned_' + id[1]).attr('readonly', false);
    }

});*/

$('body').on('click', '[data-editable]', function(){

    var $el = $(this);

    var $input = $('<input type="text" class="form-control" style="font-size: 10px;"/>').val( $el.text() );
    $el.html( $input );
    var $id_arr = $el.attr('id');
    var $id = $id_arr.split('_');

    var $mdlid = $id[1];
    var $shpd = $id[2];

    var save = function(){
        var $p =  $input.val();
        console.log($p);
      var $stdhr = $('<input type="hidden" name="stdhredited[]" value="'+$p+'"/>');
      var $modelid = $('<input type="hidden" name="modelid[]" value="'+$mdlid+'"/>');
      var $shopid = $('<input type="hidden" name="shopid[]" value="'+$shpd+'"/>');

      $el.html( $p );
      $el.append( $stdhr );
      $el.append( $modelid );
      $el.append( $shopid );

      $savestdhr = $('#savestdhr');
      $savestdhr.attr('disabled', false);
    };

    $input.one('blur', save).focus();

  });
