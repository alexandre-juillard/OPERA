import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction'; // pour les interactions comme le clic

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            locale: 'fr',
            headerToolbar: {
                start: 'dayGridMonth,dayGridWeek,dayGridDay',
                center: 'title',
                end: 'prev,next'
            },
            footerToolbar: {
                start: '',
                center: 'today',
                end: ''
            },
            weekNumbers: true,
            weekNumberFormat: { week: 'numeric' },
            firstDay: 1,
            events: [
                { title: 'Réunion', start: '2024-04-25' },
                { title: 'Entretien annuel', start: '2024-04-30' }
            ]
        });
        calendar.render();
    } else {
        console.error('Calendar element not found in the DOM');
    }
});