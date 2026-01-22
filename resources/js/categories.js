$(document).ready(function() {
    let subcategoryIndex = 0;

    // Inicializar contador con subcategorías existentes
    subcategoryIndex = $('.subcategory-item').length;

    // Añadir nueva subcategoría
    $('#add-subcategory-btn').on('click', function(e) {
        e.preventDefault();
        addSubcategoryRow();
    });

    // Función para añadir una fila de subcategoría
    function addSubcategoryRow(name = '', descripcion = '', id = null) {
        const rowHtml = `
            <div class="subcategory-item bg-gray-50 border border-gray-300 rounded-lg p-4 mb-3" data-index="${subcategoryIndex}">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-grip-vertical text-gray-400"></i>
                        <span class="font-semibold text-gray-700">
                            <i class="fas fa-folder text-blue-500 mr-1"></i>
                            Subcategoría ${subcategoryIndex + 1}
                        </span>
                    </div>
                    <button type="button" class="remove-subcategory text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-times-circle text-xl"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-blue-900 mb-1">
                            <i class="fas fa-tag mr-1"></i>Nombre *
                        </label>
                        <input type="text" 
                               name="subcategories[${subcategoryIndex}][name]" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                               placeholder="Nombre de la subcategoría"
                               value="${name}"
                               required>
                        ${id ? `<input type="hidden" name="subcategories[${subcategoryIndex}][id]" value="${id}">` : ''}
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-blue-900 mb-1">
                            <i class="fas fa-align-left mr-1"></i>Descripción
                        </label>
                        <input type="text" 
                               name="subcategories[${subcategoryIndex}][descripcion]" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                               placeholder="Descripción opcional"
                               value="${descripcion}">
                    </div>
                </div>
            </div>
        `;
        
        $('#subcategories-list').append(rowHtml);
        subcategoryIndex++;
        updateSubcategoryCount();
        updateRemoveButtons();
    }

    // Eliminar subcategoría
    $(document).on('click', '.remove-subcategory', function(e) {
        e.preventDefault();
        $(this).closest('.subcategory-item').fadeOut(300, function() {
            $(this).remove();
            updateSubcategoryNumbers();
            updateSubcategoryCount();
            updateRemoveButtons();
        });
    });

    // Actualizar numeración de subcategorías
    function updateSubcategoryNumbers() {
        $('.subcategory-item').each(function(index) {
            $(this).find('span.font-semibold').html(`
                <i class="fas fa-folder text-blue-500 mr-1"></i>
                Subcategoría ${index + 1}
            `);
        });
    }

    // Actualizar contador de subcategorías
    function updateSubcategoryCount() {
        const count = $('.subcategory-item').length;
        $('#subcategory-count').text(count);
        
        if (count === 0) {
            $('#subcategories-empty-message').show();
        } else {
            $('#subcategories-empty-message').hide();
        }
    }

    // Actualizar visibilidad de botones de eliminar
    function updateRemoveButtons() {
        const count = $('.subcategory-item').length;
        if (count <= 1) {
            $('.remove-subcategory').addClass('opacity-50 cursor-not-allowed');
        } else {
            $('.remove-subcategory').removeClass('opacity-50 cursor-not-allowed');
        }
    }

    // Hacer las subcategorías ordenables con drag & drop
    if (typeof $.fn.sortable !== 'undefined') {
        $('#subcategories-list').sortable({
            handle: '.fa-grip-vertical',
            cursor: 'move',
            opacity: 0.6,
            update: function(event, ui) {
                updateSubcategoryNumbers();
            }
        });
    }

    // Validación antes de enviar el formulario
    $('form').on('submit', function(e) {
        // Validar que todas las subcategorías tengan nombre
        let isValid = true;
        $('.subcategory-item').each(function() {
            const nameInput = $(this).find('input[name*="[name]"]');
            if (!nameInput.val().trim()) {
                isValid = false;
                nameInput.addClass('border-red-500');
            } else {
                nameInput.removeClass('border-red-500');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Por favor, completa el nombre de todas las subcategorías o elimina las vacías.');
            return false;
        }
    });

    // Inicializar contador
    updateSubcategoryCount();
    updateRemoveButtons();
});
