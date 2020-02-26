$(document).ready(function() {

    $("#add_location_btn").click(function() {
        openPopup($(this).data("action"));
    });

    function openPopup(url) {
        $("#popup_model").html("").modal("show").load(url);
    }

});

$(document).ready(function() {

    $("#user-user_type").change(function() {

        var selectedRole = $("#user-role_id option:selected").val();
        var selectedUserType = $("input[name='User[user_type]']:checked").val();
        $('#email').css('display', 'block');
        $('#taskpass').css('display', 'none');
        $('#taskpass2').css('display', 'none');
        $('#uname').css('display', 'none');

        if (selectedUserType == 5) {
            $('#email').css('display', 'none');
            $('#taskpass').css('display', 'block');
            $('#taskpass2').css('display', 'block');
            $('#uname').css('display', 'block');
        }

    });


    /* $("#user-role_id").change(function() {

         var selectedRole = $("#user-role_id option:selected").val();
         var selectedUserType = $("input[name='User[user_type]']:checked").val();

         $('#email').css('display', 'block');
         $('#taskpass').css('display', 'none');
         $('#taskpass2').css('display', 'none');
         $('#uname').css('display', 'none');
         console.log(selectedRole, selectedUserType);
         if (selectedRole == 54 && selectedUserType == 5) {
             $('#email').css('display', 'none');
             $('#taskpass').css('display', 'block');
             $('#taskpass2').css('display', 'block');
             $('#uname').css('display', 'block');
         }

     });*/


});