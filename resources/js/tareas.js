import $ from "jquery";
import select2 from "select2";
import swal from "sweetalert";
import "select2/dist/css/select2.css";
import gsap from "gsap";
import Flip from "gsap/Flip";

gsap.registerPlugin(Flip);

$(document).ready(function () {
    $("#cliente_id,#encargado_id").select2({
        tags: true,
        placeholder: "-- seleccione tags--",
        // theme: "classic",
        allowClear: true,
    });

    // Confirmación al enviar el formulario de creación de tarea
    $('form[action$="tareas/store"]').on("submit", function (e) {
        e.preventDefault();
        swal({
            title: "¿Guardar tarea?",
            text: "¿Estás seguro de crear esta tarea?",
            icon: "info",
            buttons: true,
            dangerMode: false,
        }).then((willSave) => {
            if (willSave) {
                this.submit();
            }
        });
    });

    $(".select2").select2({
        width: "100%",
        placeholder: "Selecciona una opción",
        allowClear: true,
        dropdownAutoWidth: true,
    });

    // Enfocar el input de búsqueda al abrir select2
    $(document).on("select2:open", () => {
        document.querySelector(".select2-search__field").focus();
    });

    function actualizarTabla() {
        $.ajax({
            url: "/tareas/render",
            type: "GET",
            success: function (nuevoHtml) {
                const estadoFlip = Flip.getState("[flip-id]");

                const newContenedor = $("<tbody/>", {
                    html: nuevoHtml,
                });

                let childrens = newContenedor.children();
                let continuar = true;

                let ids = estadoFlip.targets.map( t => $(t).attr('flip-id'));
                
                for (let i = 0; i < childrens.length; i++) {
                    const childtr = childrens[i];
                    if (!ids.includes($(childtr).attr('flip-id'))) {
                        $('#tareas-tbody').append(childtr);
                    }
                }

                do {
                    continuar = true;
                    let targets = $('#tareas-tbody').children();
                    for (let i = 0; i < targets.length; i++) {
                        const elemento = targets[i];
                        if (
                            elemento.getAttribute("flip-id") ===
                            childrens[i].getAttribute("flip-id")
                        ) {
                            $(elemento).attr(
                                "class",
                                $(childrens[i]).attr("class")
                            );
                            $(elemento).html($(childrens[i]).html());
                        } else {
                            for (let j = 0; j < childrens.length; j++) {
                                const element = childrens[j];
                                if (
                                    element.getAttribute("flip-id") ===
                                    elemento.getAttribute("flip-id")
                                ) {
                                    $(elemento).html($(element).html());
                                    $(elemento).insertBefore(
                                        targets[j]
                                    );
                                    continuar = false;
                                    break;
                                }
                            }
                        }
                    }
                } while (!continuar);

                Flip.from(estadoFlip, {
                    duration: 0.5,
                    ease: "power1.inOut",
                    absolute: false,
                    stagger: 0.02,
                });
            },
            error: function () {
                console.error("Error al cargar tareas.");
            },
        });
    }

    window.Echo.channel('tarea-channel')
        .listen('.tarea-update', (e) => {
            // Actualiza la tabla
            actualizarTabla();
        });

});
