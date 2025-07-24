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

    function establecerEventos() {      
        $('.select-estado').on('change', (e) => {
            if ($(e.currentTarget).val() == 'completada') {
                swal({
                    title: 'COMPLETAR TAREA',
                    text: '¿Vas a marcar esta tarea como completada?',
                    icon: 'success',
                    buttons: ['NO', 'Sí']
                }).then((result) => {
                    if (result) {
                        $(e.currentTarget.form.submit())
                    }
                })
            } else if ($(e.currentTarget).val() == 'cancelada') {
                swal({
                    title: 'CANCELAR TAREA',
                    text: '¿Está seguro que desea cancelar esta tarea?',
                    icon: 'error',
                    buttons: ['NO', 'Sí'],
                    dangerMode: true
                }).then((result) => {
                    if (result) {
                        $(e.currentTarget.form.submit())
                    }
                })
            } else {
                e.currentTarget.form.submit();
            }
        })
    }

    establecerEventos();

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

                let ids = estadoFlip.targets.map((t) => $(t).attr("flip-id"));
                let new_ids = [];
                for (let i = 0; i < childrens.length; i++) {
                    const childtr = childrens[i];
                    new_ids.push($(childtr).attr("flip-id"));
                    if (!ids.includes($(childtr).attr("flip-id"))) {
                        $("#tareas-tbody").append(childtr);
                    }
                }
                
                let current_trs = estadoFlip.targets;
                for (let i = 0; i < current_trs.length; i++) {
                    const current_tr = current_trs[i];
                    if (!new_ids.includes($(current_tr).attr("flip-id"))) {
                        $("#tareas-tbody").remove(current_tr);
                    }
                }

                do {
                    continuar = true;
                    let targets = $("#tareas-tbody").children();
                    for (let i = 0; i < targets.length; i++) {
                        const elemento = targets[i];
                        if (childrens[i]) {
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
                                        $(elemento).insertBefore(targets[j]);
                                        continuar = false;
                                        break;
                                    }
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

                establecerEventos();
            },
            error: function () {
                console.error("Error al cargar tareas.");
            },
        });
    }

    window.Echo.channel("tarea-channel").listen(".tarea-update", (e) => {
        // Actualiza la tabla
        actualizarTabla();
    });
});