function addFile(event) {
    var message = $('#load');
    var formData = new FormData();
    formData.append('file', $('#picture')[0].files[0]);


    $.ajax({
        type: "POST",
        url: "/adm/saveFile",
        data: formData,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            message.text("Запрос начат");
            $('#picture').prop("disabled", true);
            $('#submit').prop("disabled", true);
        },
        success: function (data) {
            if (data.status == 'ok') {
                message.html("Файл загружен<br>" +
                    "<img src='../../static/PreFiles/" + data.fileName + "'>" +
                    "<input type='hidden' name='uploadedPicture' value='../../static/PreFiles/" + data.fileName + "'>")

            } else {
                message.text("Что- то пошло не так");
            }
        },
        complete: function () {
            $('#picture').prop("disabled", false);
            $('#submit').prop("disabled", false);
        }
    });

};