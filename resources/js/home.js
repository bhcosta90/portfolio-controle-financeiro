$(function () {
    var promiseArray = [];

    $(".requisicao").map(function (_, item) {
        const div = $(item);
        if (div.data("route") !== undefined && div.data("route") != "") {
            const promisse = new Promise(function (resolve, reject) {
                return $.get(div.data("route"))
                    .then((json) => resolve(json, div))
                    .catch((json) => reject(json));
            });

            promiseArray.push(promisse);
        }
    });

    Promise.all(promiseArray)
        .then(function (values) {
            values.map(function (item) {
                const div = $(`.${item.tipo}`);

                item.data.map(function (data) {
                    div.find(`.${data.key}`).html(data.value);

                    div.find(".text-calculado").each(function () {
                        const type = $(this).data("class");
                        if (type !== undefined && type == data.key) {
                            if (data.value < 0) {
                                $(this).addClass("text-danger");
                            } else if (data.value == 0) {
                                $(this).addClass("text-warning");
                            } else {
                                $(this).addClass("text-success");
                            }
                        }
                    });
                });
            });
        })
        .catch(function (reason) {
            console.log(reason);
        });
});
