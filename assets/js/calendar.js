import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'; // pour les interactions comme le clic

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    let events = window.calendarEvents;
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
            height: 'auto',
            weekNumbers: true,
            weekNumberFormat: { week: 'numeric' },
            firstDay: 1,
            nowIndicator: true,
            slotMinTime: "08:00:00",
            slotMaxTime: "20:00:00",
            // weekends: false, //uncomment this line to hide Saturday/Sunday from calendar
            //hiddenDays: [0, 1, 2, 3...], //uncomment to customize hidden days (0 = sunday, 1 = monday...)
            //allDaySlot: false, //uncomment to hide "all-day" slot at top of calendar
            events: events
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
