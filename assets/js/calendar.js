import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'; // pour les interactions comme le clic

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin],
            initialView: 'timeGridWeek',
            locale: 'fr',
            timeZone: 'Europe/Paris',
            headerToolbar: {
                start: 'dayGridMonth,timeGridWeek,timeGridDay',
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
            nowIndicator: true,
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

//dayGrid = affiche pur
//timeGrid = affichage avec les heures
//timeGridDay / timeGridMonth / timeGridWeek = affichage jour, mois, semaine
//on peut lui passer autant de paramètres que l'on veut pour influer sur affichage des infos
