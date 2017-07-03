$(document).on('click', (event) => {
    if (!$(event.target).closest('.list-fab').length) {
        $('.list-menu').removeClass("active");
    } else {
        $(event.target).parents(".card-image").find(".list-menu").addClass("active");
    }
})
