jQuery(document).ready(function() {
    var form = $("#step-form");
    form.children("div").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    onStepChanging: function (event, currentIndex, newIndex)
    {
        return true;
    },
    onFinishing: function (event, currentIndex)
    {
        window.location = "#checkblogs";
        $($('body > .overlay')).hide();
    }
});
});