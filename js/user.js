
$(document).ready(function () {

    $("#add_location_btn").click(function () {
        openPopup($(this).data("action"));
    });

    function openPopup(url) {
        $("#popup_model").html("").modal("show").load(url);
    }

});

$(document).ready(function () {

    $("#user-user_type").click(function(){
           
        var sample = $("#user-role_id option:selected").text();
        var sample2 = $("input[name='User[user_type]']:checked").val();
        console.log(sample);
        console.log(sample2);
       if( sample2 == 4 && sample == 'TaskDoer' )
       {
          console.log('true');
          $('#email').css('display', 'none');
            $('#taskpass').css('display', 'block');
            $('#taskpass2').css('display', 'block');
            $('#uname').css('display', 'block');
       }

       if(sample != 'TaskDoer' || sample2 != 4 )
       {
        console.log('false');
        $('#email').css('display', 'block');
       $('#taskpass').css('display', 'none');
       $('#taskpass2').css('display', 'none');
       $('#uname').css('display', 'none');
       }

    });

    
    $("#user-role_id").change(function(){
           
        var sample = $("#user-role_id option:selected").text();
        var sample2 = $("input[name='User[user_type]']:checked").val();
        console.log(sample);
        console.log(sample2);
       if( sample2 == 4 && sample == 'TaskDoer' )
       {
          console.log('true');
          $('#email').css('display', 'none');
            $('#taskpass').css('display', 'block');
            $('#taskpass2').css('display', 'block');
            $('#uname').css('display', 'block');
       }

       if(sample != 'TaskDoer' || sample2 != 4 )
       {
        console.log('false');
        $('#email').css('display', 'block');
       $('#taskpass').css('display', 'none');
       $('#taskpass2').css('display', 'none');
       $('#uname').css('display', 'none');
       }

    });


});
