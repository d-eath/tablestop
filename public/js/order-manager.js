// Fichier : order-manager.js
// Date : 2021-04-23
// Auteur : Davis Eath
// But : Gérer l'affichage des dates et des comptes à rebours de façon dynamique

(() => {
    $('[data-uts-date]').each(function() {
        $(this).text(dayjs($(this).data('uts-date') * 1000).format('YYYY-MM-DD'));
    });
    
    function updateCountdowns() {
        $('[data-uts-cd]').each(function() {
            const diff = dayjs($(this).data('uts-cd') * 1000).diff(dayjs());
    
            if (diff < 0) {
                $(this).closest('.cancel-prompt').remove();
            }
    
            const duration = dayjs.duration(diff);
            
            const days = duration.days();
            const hours = duration.hours();
            const minutes = duration.minutes();
            const seconds = duration.seconds();
        
            if (days > 0) {
                $(this).text(`${days} jour${days > 1 ? 's' : ''} et ${hours} heure${hours > 1 ? 's' : ''}`);
            } else if (hours > 0) {
                $(this).text(`${hours} heure${hours > 1 ? 's' : ''} et ${minutes} minute${minutes > 1 ? 's' : ''}`);
            } else {
                $(this).text(`${minutes} minute${minutes > 1 ? 's' : ''} et ${seconds} seconde${seconds > 1 ? 's' : ''}`);
            }
        });
    }
    
    setInterval(updateCountdowns, 500);
    updateCountdowns();
})();
