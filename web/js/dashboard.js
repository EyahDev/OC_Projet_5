$("div.titre").click(function(){
    $(this).find(".fleche").toggleClass("rotate");
});

$("a.collapsed").click(function(){
    $(this).find(".fleche").toggleClass("tranparent");
});
