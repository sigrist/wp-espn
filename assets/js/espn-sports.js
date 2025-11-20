/**
 * Scripts para o plugin ESPN Sports Scores
 */

(function($) {
    'use strict';

    /**
     * Atualização automática de scoreboards ao vivo
     */
    var ESPNSports = {

        /**
         * Inicializa o plugin
         */
        init: function() {
            this.bindEvents();
            this.initAutoRefresh();
            this.convertTimezones();
        },

        /**
         * Vincula eventos
         */
        bindEvents: function() {
            // Adicionar eventos conforme necessário
        },

        /**
         * Inicializa atualização automática para jogos ao vivo
         */
        initAutoRefresh: function() {
            var $liveGames = $('.espn-game-card.status-in');

            if ($liveGames.length > 0) {
                // Atualiza a cada 60 segundos para jogos ao vivo
                setInterval(function() {
                    ESPNSports.refreshLiveGames();
                }, 60000);
            }
        },

        /**
         * Atualiza jogos ao vivo
         */
        refreshLiveGames: function() {
            // Pode ser implementado com AJAX se necessário
            // Por enquanto, apenas recarrega a página
            // location.reload();
            console.log('Auto-refresh disponível para implementação futura');
        },

        /**
         * Formata números com separador de milhares
         */
        formatNumber: function(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },

        /**
         * Anima a mudança de placar
         */
        animateScore: function($element, newScore) {
            $element.addClass('score-changed');
            setTimeout(function() {
                $element.removeClass('score-changed');
            }, 1000);
        },

        /**
         * Converte timestamps para o timezone local do navegador
         */
        convertTimezones: function() {
            var $timestamps = $('[data-timestamp]');

            $timestamps.each(function() {
                var $elem = $(this);
                var timestamp = $elem.attr('data-timestamp');

                if (!timestamp) return;

                try {
                    var date = new Date(timestamp);

                    // Verifica se a data é válida
                    if (isNaN(date.getTime())) return;

                    // Formata a data no timezone local usando pt-BR
                    var options = {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        timeZoneName: 'short'
                    };

                    var formatted = new Intl.DateTimeFormat('pt-BR', options).format(date);

                    // Atualiza o texto do elemento
                    $elem.text(formatted);

                    // Adiciona tooltip com informação do timezone
                    var userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                    $elem.attr('title', 'Horário local (' + userTimezone + ')');

                } catch (e) {
                    console.error('Erro ao converter timezone:', e);
                }
            });
        }
    };

    /**
     * Inicializa quando o documento estiver pronto
     */
    $(document).ready(function() {
        ESPNSports.init();
    });

})(jQuery);
