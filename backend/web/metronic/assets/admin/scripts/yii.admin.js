yii.admin = (function ($) {
    //jQuery.expr[':'].Contains = function(a,i,m){
    //    return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
    //};
    //var _onSearch = false;
    var pub = {
        assignUrl: undefined,
        getRoutesUrl: undefined,
        // route
        assignRoute: function (action) {
            var params = {
                action: action,
                routes: $('#list-' + (action == 'assign' ? 'available' : 'assigned')).val()
            };

            Metronic.blockUI({
                boxed: true
            });

            $.post(pub.assignUrl, params, function (data) {
                Metronic.unblockUI();
                if (data.errors.length !== 0) {
                    alert(data.errors[0]);
                } else {
                    pub.getRoutes('available', true);
                    pub.getRoutes('assigned', true);
                }
            }).fail(function(data) {
                    Metronic.unblockUI();
                    alert(data.responseText);
                });
        },
        listFilter: function(input, select) {
            $(input)
                .change( function () {
                    var filter = $(this).val();
                    if(filter) {
                        // this finds all option in select list that contain the input,
                        // and hide the ones not containing the input while showing the ones that do
                        $(select).find("option:not(:Contains(" + filter + "))").hide();
                        $(select).find("option:Contains(" + filter + ")").show();
                    } else {
                        $(select).find("option").show();
                    }
                    return false;
                })
                .keyup( function () {
                    // fire the above change event after every letter
                    $(this).change();
                });
        },
        getRoutes: function (target, refresh, force) {
            setTimeout(function () {
                var data = {
                    target: target,
                    //refresh: refresh
                };
                $.get(pub.getRoutesUrl, data, function (r) {
                    var $list = $('#list-' + target);
                    $list.html('');
                    $.each(r, function (key, val) {
                        var $opt = $('<option>').val(key).text(key);
                        if (!val) {
                            $opt.addClass('lost');
                        }
                        $opt.appendTo($list);
                    });
                });
            }, 500);
        },
        initProperties: function (properties) {
            $.each(properties, function (key, val) {
                pub[key] = val;
            });
        }
    };
    return pub;
})(jQuery);