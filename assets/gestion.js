import './styles/gestion.scss';


$(function(){
    console.log("%c chargement js 0.3", 'background: #222; color: #bada55');
    $("a.nav-link.ajax").on("click", function(evtClick){
        evtClick.preventDefault();
        $.ajax({
            url: $(this).prop("href"),
            dataType: "html",
            success: function(donnees) {
                $("#gestion-contenu").html(donnees);            
            },
            error: function(jqXHR, status, error){
                console.log(jqXHR);
                $("#gestion-contenu").html("<div class='alert alert-danger'>" + status + " : " + error + "</div>");
            }
        });
    });
});