$(document).ready(function () {
    var engine, remoteHost, template;

    $.support.cors = true;
    localStorage.clear();

    remoteHost = 'http://localhost/fsmproject/frontend/web/index.php?r=';
    template = Handlebars.compile($("#result-template").html());

    engine = new Bloodhound({
        identify: function (o) {
            return o.product_name;
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('product_name'),
        dupDetector: function (a, b) {
            return a.product_name === b.product_name;
        },
        prefetch: remoteHost + '/search/prefetch',
        remote: {
            url: remoteHost + 'search/auto-complete&q=%QUERY',
            wildcard: '%QUERY'
        }
    });

    // ensure default users are read on initialization
    //engine.get('1090217586', '58502284', '10273252', '24477185');

    function engineWithDefaults(q, sync, async) {
        if (q === '') {
            //sync(engine.get('1090217586', '58502284', '10273252', '24477185'));
            async([]);
        } else {
            engine.search(q, sync, async);
        }
    }

    $('#search-input').typeahead({
        hint: $('.typeahead-hint'),
        menu: $('.typeahead-menu'),
        minLength: 1,
        classNames: {
            open: 'is-open',
            cursor: 'is-active',
            suggestion: 'typeahead-suggestion',
            selectable: 'typeahead-selectable'
        }
    }, {
        source: engineWithDefaults,
        displayKey: 'product_name',
        templates: {
            suggestion: template
        }
    })
        .on('typeahead:asyncrequest', function () {
            $('.typeahead-spinner').show();
            $('#search-form').hide();
        })
        .on('typeahead:asynccancel typeahead:asyncreceive', function () {
            $('.typeahead-spinner').hide();
            $('#search-form').show();
        })
        .on('typeahead:selected', function (evt, data) {
            window.location.href = remoteHost + 'site/view-detail&product=' + data.product_name;
        });

});