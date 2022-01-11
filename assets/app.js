/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
// import './bootstrap';

const $ = require("jquery");
global.$ = global.jQuery = $;
require("bootstrap");

$(function(){
    $(".frm-ajouter-panier").on("submit", function(evtSubmit){
        evtSubmit.preventDefault();  // empêche l'action par défaut de l'évènement
        console.log("formulaire soumis");
        $.ajax({
            url: $(this).prop("action"),
            method: "get",
            dataType: "json",
            data: { qte: $(this).find("[name='qte']").val() },
            success: function(donnees){
                if( donnees ) {
                    alert("produit ajouté au panier");
                }
            },
            error: function(jqXHR, status, error){
                alert("Erreur AJAX : " + status + ", " + error);
            }
        });
    });
})