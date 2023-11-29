//navbar


//to do list


//date
document.addEventListener('DOMContentLoaded', function() {
    var today = new Date();
    var options = { day: 'numeric', month: 'long', year: 'numeric' };
    var dateString = today.toLocaleDateString('fr-FR', options);
    document.querySelector('.titles-container h2').textContent = dateString;
});


//dropdown
// Fonction pour basculer l'affichage des menus déroulants
function toggleDropdown(dropdownId) {
    document.getElementById(dropdownId).classList.toggle('show');
}

// Ajoutez un événement pour fermer les dropdowns si l'utilisateur clique en dehors
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn') && !event.target.matches('.dropdown-clear')) {
        var dropdowns = document.getElementsByClassName('dropdown-content');
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
};

// Empêcher le clic sur les checkboxes de fermer le dropdown
document.querySelectorAll('.dropdown-content input[type="checkbox"]').forEach(function(checkbox) {
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});

// Gestionnaire d'événements pour mettre à jour les tags lorsqu'une checkbox est modifiée
document.querySelectorAll('.dropdown-content input[type="checkbox"]').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        updateSelectedFilters();
        updateDropdownCount(checkbox.closest('.dropdown-content').id);
    });
});

// Fonction pour mettre à jour les tags des filtres sélectionnés
function updateSelectedFilters() {
    var selectedFilters = document.getElementById('selected-filters');
    selectedFilters.innerHTML = ''; // Efface les tags existants
    var checkboxes = document.querySelectorAll('.dropdown-content input[type="checkbox"]:checked');
    checkboxes.forEach(function(checkbox) {
        var label = checkbox.parentNode.textContent.trim();
        var tag = document.createElement('div');
        tag.className = 'filter-tag';
        tag.textContent = label;
        var removeTag = document.createElement('span');
        removeTag.className = 'remove-tag';
        removeTag.innerHTML = '&times;';
        removeTag.onclick = function() {
            checkbox.checked = false;
            tag.remove();
            updateSelectedFilters(); // Refresh the tags after removal
            updateDropdownCount(checkbox.closest('.dropdown-content').id);
            applyFiltersAndSearch(); // Ajout de cette ligne
        };
        tag.appendChild(removeTag);
        selectedFilters.appendChild(tag);
    });
}

// Fonction pour effacer les sélections et les tags
// Fonction pour réinitialiser les sélections dans un dropdown
function clearDropdownSelection(dropdownId) {
    var checkboxes = document.querySelectorAll('#' + dropdownId + ' input[type="checkbox"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);

    updateSelectedFilters();
    updateDropdownCount(dropdownId);
    applyFiltersAndSearch();

}

// Attacher les écouteurs d'événements aux éléments dropdown-clear
document.querySelectorAll('.dropdown-clear').forEach(function(clearSpan) {
    clearSpan.addEventListener('click', function(event) {
        event.stopPropagation();
        var dropdownId = this.closest('.dropdown').querySelector('.dropdown-content').id;
        clearDropdownSelection(dropdownId);
    });
});
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si l'élément #clear-filters existe et le créer si nécessaire
    let clearFiltersButton = document.getElementById('clear-filters');
    if (!clearFiltersButton) {
        clearFiltersButton = document.createElement('button');
        clearFiltersButton.id = 'clear-filters';
        clearFiltersButton.style.display = 'none'; // Cachez-le car il est factice
        document.body.appendChild(clearFiltersButton);
    }
    document.querySelectorAll('.dropdown-clear').forEach(function(clearSpan) {
        clearSpan.addEventListener('click', function(event) {
            event.stopPropagation();
            let dropdownId = this.closest('.dropdown').querySelector('.dropdown-content').id;
            clearDropdownSelection(dropdownId);
        });
    });

    // Initialisation des compteurs des dropdowns
    let dropdowns = document.getElementsByClassName('dropdown-content');
    for (var i = 0; i < dropdowns.length; i++) {
        updateDropdownCount(dropdowns[i].id);
    }

    // Attacher l'écouteur d'événements au bouton clear-filters
    clearFiltersButton.addEventListener('click', function() {
        var allCheckboxes = document.querySelectorAll('.dropdown-content input[type="checkbox"]');
        allCheckboxes.forEach(checkbox => checkbox.checked = false);

        updateSelectedFilters();
        applyFiltersAndSearch(); // Actualise l'affichage des cartes

        // Mettre à jour le compteur pour chaque dropdown
        let dropdowns = document.getElementsByClassName('dropdown-content');
        for (var i = 0; i < dropdowns.length; i++) {
            updateDropdownCount(dropdowns[i].id);
        }
    });

    // Attacher des écouteurs d'événements aux éléments dropdown-clear
    document.querySelectorAll('.dropdown-clear').forEach(function(clearSpan) {
        clearSpan.addEventListener('click', function(event) {
            event.stopPropagation();
            var dropdownId = this.closest('.dropdown').querySelector('.dropdown-content').id;
            clearDropdownSelection(dropdownId); // Assurez-vous que cette fonction est définie
        });
    });



});


function updateDropdownCount(dropdownId) {
    var count = document.querySelectorAll('#' + dropdownId + ' input[type="checkbox"]:checked').length;
    var dropdownButton = document.querySelector('[onclick="toggleDropdown(\'' + dropdownId + '\')"]');
    var countSpan = dropdownButton ? dropdownButton.querySelector('.dropdown-count') : null;
    if (countSpan) {
        countSpan.textContent = count.toString(); // Affiche le nombre sans parenthèses
    }
}

// Initialisation des compteurs au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    var dropdowns = document.getElementsByClassName('dropdown-content');
    for (var i = 0; i < dropdowns.length; i++) {
        updateDropdownCount(dropdowns[i].id);
    }
});



//calendrier sidebar et filtrage, search bar
moment.locale('fr');
let currentMonth = moment();
const calendarEl = document.getElementById('calendar');
const monthYearEl = document.getElementById('monthYear');
const currentDateDisplayEl = document.getElementById('currentDateDisplay');
let selectedDate = moment();
let selectedCalendarDate = '';

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function updateCurrentDateDisplay() {
    currentDateDisplayEl.textContent = capitalizeFirstLetter(selectedDate.format('dddd D MMM').replace(/\./g, ''));
    monthYearEl.textContent = capitalizeFirstLetter(selectedDate.format('MMMM YYYY'));
}

function renderCalendar() {
    calendarEl.innerHTML = '';
    const daysOfWeek = moment.weekdaysMin(true);
    daysOfWeek.forEach(day => {
        const dayHeaderEl = document.createElement('div');
        dayHeaderEl.className = 'day-header';
        dayHeaderEl.textContent = day.charAt(0).toUpperCase() + day.slice(1);
        calendarEl.appendChild(dayHeaderEl);
    });

    let startOfMonth = currentMonth.clone().startOf('month');
    let endOfMonth = currentMonth.clone().endOf('month');
    let date = startOfMonth.clone();

    while (date.isSameOrBefore(endOfMonth)) {
        const dayEl = document.createElement('div');
        dayEl.className = 'day';
        dayEl.textContent = date.format('D');

        let dayDate = date.clone(); // Copie unique pour ce jour

        dayEl.onclick = () => {
            selectedDate = dayDate;
            selectedCalendarDate = dayDate.format('DD MMM YYYY').toLowerCase();
            updateCurrentDateDisplay();
            updateSelection();
            applyFiltersAndSearch();
        };

        if (dayDate.isSame(selectedDate, 'day')) {
            dayEl.classList.add('selected');
        }

        calendarEl.appendChild(dayEl);
        date.add(1, 'day');
    }

    updateCurrentDateDisplay();
}

function updateSelection() {
    document.querySelectorAll('.day').forEach(d => {
        const dayMoment = moment(d.textContent, 'D').month(currentMonth.month()).year(currentMonth.year());
        d.classList.remove('selected');
        if (dayMoment.isSame(selectedDate, 'day') && dayMoment.isSame(currentMonth, 'month')) {
            d.classList.add('selected');
        }
    });
}

function changeMonth(months) {
    currentMonth.add(months, 'months');
    selectedDate = currentMonth.clone().startOf('month');
    renderCalendar();
}

function normalizeString(str) {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
}

function applyFiltersAndSearch() {
    var searchValue = normalizeString(document.getElementById('search-input').value);
    var selectedDates = Array.from(document.querySelectorAll('#date-dropdown input[type="checkbox"]:checked')).map(cb => normalizeString(cb.value));
    var selectedMatieres = Array.from(document.querySelectorAll('#matieres-dropdown input[type="checkbox"]:checked')).map(cb => normalizeString(cb.value));
    var selectedGroupes = Array.from(document.querySelectorAll('#group-dropdown input[type="checkbox"]:checked')).map(cb => normalizeString(cb.value));
    var selectedSemestres = Array.from(document.querySelectorAll('#semestre-dropdown input[type="checkbox"]:checked')).map(cb => normalizeString(cb.value));

    var cards = document.querySelectorAll('.card');
    cards.forEach(function(card) {
        var cardDate = normalizeString(card.getAttribute('data-date'));
        var cardMatiere = normalizeString(card.getAttribute('data-matiere'));
        var cardGroupe = normalizeString(card.getAttribute('data-groupe'));
        var cardSemestre = normalizeString(card.getAttribute('data-semestre'));

        var dateMatch = selectedDates.length === 0 || selectedDates.includes(cardDate);
        var matiereMatch = selectedMatieres.length === 0 || selectedMatieres.includes(cardMatiere);
        var groupeMatch = selectedGroupes.length === 0 || selectedGroupes.includes(cardGroupe);
        var semestreMatch = selectedSemestres.length === 0 || selectedSemestres.includes(cardSemestre);
        var isCalendarMatch = selectedCalendarDate === '' || cardDate === selectedCalendarDate;

        var isSearchMatch = searchValue === '' || cardDate.includes(searchValue) ||
            cardMatiere.includes(searchValue) || cardGroupe.includes(searchValue) ||
            cardSemestre.includes(searchValue) ||
            card.querySelector('.details').textContent.toLowerCase().includes(searchValue) ||
            card.querySelector('.email-address').textContent.toLowerCase().includes(searchValue);

        card.style.display = (dateMatch && matiereMatch && groupeMatch && semestreMatch && isSearchMatch && isCalendarMatch) ? '' : 'none';
    });
}

document.querySelectorAll('.dropdown-content input[type="checkbox"]').forEach(function(checkbox) {
    checkbox.addEventListener('change', applyFiltersAndSearch);
});

document.getElementById('search-input').addEventListener('input', applyFiltersAndSearch);

document.addEventListener('DOMContentLoaded', function() {
    selectedCalendarDate = '';
    applyFiltersAndSearch();
    renderCalendar();
});

renderCalendar();




//dark mode
function initializeDarkModeToggle() {
    var body = document.body;
    var lightModeIcon = document.getElementById('lightModeIcon');
    var darkModeIcon = document.getElementById('darkModeIcon');

    // Vérifier et appliquer l'état du mode sombre stocké
    if (localStorage.getItem('darkMode') === 'true') {
        body.classList.add('dark-mode');
        if (lightModeIcon && darkModeIcon) {
            lightModeIcon.style.display = 'none';
            darkModeIcon.style.display = 'block';
        }
    }

    // Gestionnaire d'événements pour le basculement
    var toggleButton = document.querySelector('.mod-button a');
    if (toggleButton) {
        toggleButton.addEventListener('click', function(e) {
            e.preventDefault();

            // Bascule la classe pour le mode sombre
            body.classList.toggle('dark-mode');

            // Enregistrer l'état dans localStorage
            localStorage.setItem('darkMode', body.classList.contains('dark-mode'));

            // Bascule les icônes SVG
            if (lightModeIcon && darkModeIcon) {
                lightModeIcon.style.display = body.classList.contains('dark-mode') ? 'none' : 'block';
                darkModeIcon.style.display = body.classList.contains('dark-mode') ? 'block' : 'none';
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    initializeDarkModeToggle();
});





//liste de rappels
function addReminderToSidebar(element) {
    const cardId = element.getAttribute('data-card-id');
    if (document.getElementById(`reminder-${cardId}`)) {
        alert('Ce rappel a déjà été ajouté.');
        return;
    }

    const cardClass = element.getAttribute('data-card-class');
    const cardTitle = element.getAttribute('data-card-title');
    const cardDetails = element.getAttribute('data-card-details');
    const cardDetailItems = element.getAttribute('data-card-detail-items');
    const cardDate = element.getAttribute('data-card-date');

    const reminderList = document.getElementById('reminderList');
    const reminderCard = document.createElement('div');
    reminderCard.id = `reminder-${cardId}`;
    reminderCard.className = `reminder-card ${cardClass}`;
    reminderCard.style.color = cardClass === 'blue' ? '#FFFFFF' : '#000000';
    reminderCard.innerHTML = `
    <div class="reminder-checkbox-container">
        <input type="checkbox" class="reminder-checkbox" data-card-id="${cardId}" onchange="toggleCardAndReminder('${cardId}', this.checked)">
        <label for="reminder-checkbox-${cardId}"></label>
    </div>
        <div class="reminder-date">${cardDate}</div>
        <div class="reminder-title">${cardTitle}</div>
        <div class="reminder-details">${cardDetails} ${cardDetailItems}</div>
        
    </div>
    `;
    reminderList.appendChild(reminderCard);
}
// Pour les cartes de base
document.querySelectorAll('.card-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const cardId = this.getAttribute('data-card-id');
        toggleCardAndReminder(cardId, this.checked);
    });
});

// Pour les cartes de rappel
document.querySelectorAll('.reminder-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const cardId = this.getAttribute('data-card-id');
        const isChecked = this.checked;

        // Ajouter un délai avant d'appliquer les changements
        setTimeout(() => {
            toggleCardAndReminder(cardId, isChecked);
        }, 200); // Ici, un délai de 200 millisecondes
    });
});


function toggleCardAndReminder(cardId, isChecked) {
    const cardCheckbox = document.querySelector(`.card-checkbox[data-card-id="${cardId}"]`);
    const reminderCheckbox = document.querySelector(`.reminder-checkbox[data-card-id="${cardId}"]`);

    setTimeout(() => {
        if (cardCheckbox) {
            cardCheckbox.checked = isChecked;
            cardCheckbox.closest('.card').style.display = isChecked ? 'none' : '';
        }

        if (reminderCheckbox) {
            reminderCheckbox.checked = isChecked;
            const reminderCard = document.getElementById(`reminder-${cardId}`);
            if (reminderCard) {
                reminderCard.style.display = isChecked ? 'none' : '';
            }
        }
    }, 200);
}




function toggleEditMode(cardId) {
    const card = document.querySelector(`#card-${cardId}`);
    const isEditable = card.classList.contains('editable');

    if (!isEditable) {
        // Convertir les éléments en champs éditables et sauvegarder le contenu original
        card.querySelectorAll('.editable').forEach(element => {
            const value = element.textContent.trim();
            element.setAttribute('data-original-content', value); // Sauvegarde du contenu original
            const input = document.createElement('input');
            input.type = 'text';
            input.value = value;
            element.innerHTML = '';
            element.appendChild(input);
        });

        card.classList.add('editable');
    } else {
        // Sauvegarder les modifications ou restaurer le contenu original
        const updatedData = {};
        card.querySelectorAll('.editable').forEach(element => {
            const input = element.querySelector('input');
            if (input) {
                const key = element.dataset.field;
                const value = input.value.trim();
                updatedData[key] = value;
                element.innerHTML = value; // Remplacer l'input par du texte
            }
        });

        // Traitement des éléments de la liste de détails
        updatedData['card_detail_items'] = [];
        card.querySelectorAll('.card-details li.editable').forEach(li => {
            const input = li.querySelector('input');
            if (input) {
                const value = input.value.trim();
                updatedData['card_detail_items'].push(value);
                li.innerHTML = value; // Remplacer l'input par du texte
            }
        });

        // Envoyer les données mises à jour au contrôleur
        saveCardData(cardId, updatedData);
        card.classList.remove('editable');
    }
}

function saveCardData(cardId, data) {
    fetch(`/update-card/${cardId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            // Gérer la réponse ici
        })
        .catch(error => {
            console.error('Error:', error);
            // Gérer les erreurs ici
        });
}



document.addEventListener('DOMContentLoaded', function() {
    // Mettre à jour la classe 'active' en fonction de l'URL actuelle au chargement de la page
    updateActiveNavLink(window.location.pathname);

    // Ajout d'écouteurs d'événements pour les clics sur les liens
    document.querySelectorAll('.nav-item a').forEach(link => {
        link.addEventListener('click', function(e) {
            // Mettre à jour la classe 'active' lors du clic sur un lien
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            this.closest('.nav-item').classList.add('active');
        });
    });
});

function updateActiveNavLink(currentPath) {
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });

    document.querySelectorAll('.nav-item a').forEach(link => {
        // Ici, on compare le chemin de l'URL du lien avec le chemin actuel
        if (link.getAttribute('href') === currentPath) {
            link.closest('.nav-item').classList.add('active');
        }
    });
}

