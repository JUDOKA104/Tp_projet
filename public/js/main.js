/* public/js/main.js */

document.addEventListener('DOMContentLoaded', () => {
    initShopFilters();
    initDeleteConfirmations();
});

function initShopFilters() {
    const buttons = document.querySelectorAll('.filter-btn');

    // On récupère toutes les sections possibles
    const sections = {
        'Arme': document.getElementById('section-Arme'),
        'Grade': document.getElementById('section-Grade')
    };

    if (!buttons.length) return;

    buttons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const category = e.currentTarget.dataset.filter;
            const targetSection = sections[category];

            // Vérification de sécurité
            if (!targetSection) return;

            // On vérifie si la section cible n'a pas la classe 'hidden'
            const isAlreadyOpen = !targetSection.classList.contains('hidden');

            // On cache toutes les sections
            Object.values(sections).forEach(s => s.classList.add('hidden'));
            // On désactive tous les boutons
            buttons.forEach(b => b.classList.remove('active'));

            // (Si c'était déjà ouvert, on ne fait rien, donc ça reste fermé)
            if (!isAlreadyOpen) {
                targetSection.classList.remove('hidden'); // Animation d'ouverture
                e.currentTarget.classList.add('active');  // Bouton allumé
            }
        });
    });
}

function previewFile() {
    const preview = document.getElementById('imgPreview');
    const file = document.getElementById('fileUpload').files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        preview.src = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}

function toggleForm() {
    const category = document.getElementById('categorySelector').value;
    const formArme = document.getElementById('formArme');
    const formGrade = document.getElementById('formGrade');

    if (category === 'Arme') {
        formArme.style.display = 'block';
        formGrade.style.display = 'none';
    } else {
        formArme.style.display = 'none';
        formGrade.style.display = 'block';
    }
}

// 2. Preview de l'image (Spécifique à l'ajout)
function previewFileAdd() {
    const preview = document.getElementById('imgPreviewAdd');
    const file = document.getElementById('fileUploadAdd').files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        preview.src = reader.result;
        preview.style.opacity = '1';
        preview.style.height = '100px'; // Agrandi une fois chargée
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}

// 3. Ouvrir le Modal
function openDeleteModal(id, name) {
    document.getElementById('deleteItemId').value = id;
    document.getElementById('deleteItemName').innerText = name;
    var myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    myModal.show();
}