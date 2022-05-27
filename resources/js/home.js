$(function () {
    var promiseArray = [];

    $(".request").map(function (_, item) {
        const div = $(item);
        if (div.data("route") !== undefined && div.data("route") != "") {
            const promisse = new Promise(function (resolve, reject) {
                return $.get(div.data("route"))
                    .then((json) => resolve({json: json, div: div}, div))
                    .catch((json) => reject(json));
            });

            promiseArray.push(promisse);
        }
    });

    Promise.all(promiseArray)
        .then(function (values) {
            values.map(function (item) {
                const div = item.div
                const json = item.json
                div.find('.quantity').html(json.quantity);
                div.find('.total').html(json.total);
                
                div.find('.total_real').each(function(i, item){
                    let prefix = $(item).data('prefix');
                    if (prefix !== undefined) {
                        prefix = `<small>${prefix}</small> `;
                    } else {
                        prefix = "";
                    }
                    $(item).html(`${prefix}${json.total_real}`);
                });
                
                if(json.total > 0) {
                    div.find('.box').removeClass('bg-secondary').addClass(div.find('.box').data('success'));
                } else if(json.total < 0) {
                    div.find('.box').removeClass('bg-secondary').addClass(div.find('.box').data('danger'));
                } else {
                    div.find('.box').removeClass('bg-secondary').addClass('bg-warning');
                }
            });
        })
        .catch(function (reason) {
            console.log(reason);
        });
});
