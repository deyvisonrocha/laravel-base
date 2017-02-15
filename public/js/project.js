Project = {
    init: function () {
        MultiSelect.init();

        $(".datepicker").datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });

        $('.ipaddress').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
            translation: {
                'Z': {
                    pattern: /[0-9]/, optional: true
                }
            }
        });

        $('body').tooltip({
            selector: "[data-tooltip=tooltip]",
            container: "body"
        });
    }
}

Project.init();
