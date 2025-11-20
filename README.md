# ESPN Sports Scores - Plugin WordPress

Plugin WordPress para exibir resultados de jogos, tabelas de classificação e próximos jogos usando a API não-oficial da ESPN.

## Funcionalidades

- **Resultados de Jogos (Scoreboard)**: Exibe resultados ao vivo e finalizados
- **Tabelas de Classificação**: Mostra as classificações por conferência/divisão
- **Próximos Jogos**: Lista os jogos agendados
- **Calendário de Times**: Exibe o calendário completo de um time específico
- **Tradução para pt_BR**: Termos comuns, status de jogos e datas automaticamente traduzidos

## Esportes Suportados

- NFL (Futebol Americano)
- NBA (Basquete)
- MLB (Beisebol)
- NHL (Hóquei)
- Soccer (Futebol)
- College Football
- College Basketball

## Instalação

1. Faça upload da pasta `wp-espn-sports-scores` para o diretório `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Use os shortcodes em suas páginas e posts

## Shortcodes Disponíveis

### 1. Scoreboard (Resultados de Jogos)

Exibe os resultados dos jogos recentes ou em andamento.

```
[espn_scoreboard sport="nfl" limit="5" title="Resultados NFL"]
```

**Parâmetros:**
- `sport`: Código do esporte (nfl, nba, mlb, nhl, soccer, college-football, college-basketball)
- `limit`: Número de jogos a exibir (padrão: 10)
- `date`: Data específica no formato YYYYMMDD (opcional)
- `title`: Título da seção (padrão: "Resultados")

**Exemplos:**

```
[espn_scoreboard sport="nba" limit="3"]
[espn_scoreboard sport="soccer" date="20231225"]
[espn_scoreboard sport="nfl" limit="8" title="Jogos de Hoje"]
```

### 2. Standings (Tabela de Classificação)

Exibe a tabela de classificação de uma liga.

```
[espn_standings sport="nfl" season="2023" title="Classificação NFL 2023"]
```

**Parâmetros:**
- `sport`: Código do esporte
- `season`: Ano da temporada (padrão: ano atual)
- `title`: Título da seção (padrão: "Classificação")

**Exemplos:**

```
[espn_standings sport="nba"]
[espn_standings sport="nfl" season="2023"]
[espn_standings sport="mlb" title="MLB Standings"]
```

### 3. Upcoming Games (Próximos Jogos)

Lista os próximos jogos agendados.

```
[espn_upcoming sport="nba" limit="5" title="Próximos Jogos"]
```

**Parâmetros:**
- `sport`: Código do esporte
- `limit`: Número de jogos a exibir (padrão: 5)
- `title`: Título da seção (padrão: "Próximos Jogos")

**Exemplos:**

```
[espn_upcoming sport="nfl" limit="3"]
[espn_upcoming sport="nba" limit="10" title="Jogos da Semana"]
```

### 4. Team Schedule (Calendário do Time)

Exibe o calendário completo de um time específico.

```
[espn_team_schedule sport="nfl" team="dal" season="2023" title="Dallas Cowboys"]
```

**Parâmetros:**
- `sport`: Código do esporte
- `team`: ID do time (ex: "dal" para Dallas Cowboys)
- `season`: Ano da temporada (padrão: ano atual)
- `title`: Título da seção (padrão: "Calendário")

**Exemplos:**

```
[espn_team_schedule sport="nfl" team="dal"]
[espn_team_schedule sport="nba" team="lal" title="Lakers Schedule"]
```

## Códigos de Times Comuns

### NFL
- `dal` - Dallas Cowboys
- `ne` - New England Patriots
- `gb` - Green Bay Packers
- `sf` - San Francisco 49ers
- `kc` - Kansas City Chiefs

### NBA
- `lal` - Los Angeles Lakers
- `gsw` - Golden State Warriors
- `bos` - Boston Celtics
- `mia` - Miami Heat
- `chi` - Chicago Bulls

### MLB
- `nyy` - New York Yankees
- `bos` - Boston Red Sox
- `lad` - Los Angeles Dodgers
- `sf` - San Francisco Giants

## Internacionalização (i18n)

O plugin possui **suporte completo para português brasileiro (pt_BR)**. Embora a API da ESPN retorne dados em inglês, o plugin traduz automaticamente:

### O que é traduzido

- **Status de jogos**: "Final", "Live", "Scheduled" → "Final", "Ao Vivo", "Agendado"
- **Períodos**: "1st Quarter", "Halftime", "Overtime" → "1º Quarto", "Intervalo", "Prorrogação"
- **Conferências/Divisões**: "AFC East", "Western Conference" → "AFC Leste", "Conferência Oeste"
- **Datas e horas**: Formatação automática em português (dd/mm/aaaa hh:mm)
- **Termos gerais**: "vs", "at", "Week" → "vs", "em", "Semana"

### O que NÃO é traduzido

- **Nomes de times**: Mantidos em inglês (ex: "Dallas Cowboys", "Los Angeles Lakers")
- **Nomes de jogadores**: Mantidos originais
- **Nomes de estádios**: Mantidos originais

### Customização de Traduções

Você pode adicionar suas próprias traduções usando filtros WordPress:

```php
// Customizar tradução de status
add_filter('wp_espn_translate_game_status', function($translated, $original) {
    if ($original === 'postponed') {
        return 'Reagendado';
    }
    return $translated;
}, 10, 2);

// Customizar tradução de período
add_filter('wp_espn_translate_period', function($translated, $original) {
    if ($original === 'Halftime') {
        return 'Meio-tempo';
    }
    return $translated;
}, 10, 2);

// Customizar tradução de conferência
add_filter('wp_espn_translate_conference', function($translated, $original, $sport) {
    if ($sport === 'nfl' && $original === 'AFC East') {
        return 'Divisão Leste da AFC';
    }
    return $translated;
}, 10, 3);
```

### Adicionar Traduções Programaticamente

```php
// Adicionar nova tradução de status
WP_ESPN_i18n::add_translation('game_status', 'weather_delay', 'Adiado por Clima');

// Adicionar nova tradução de período
WP_ESPN_i18n::add_translation('periods', 'Shootout', 'Disputa de Pênaltis');
```

## Cache

O plugin utiliza o sistema de transientes do WordPress para cachear as respostas da API por 1 hora. Isso melhora o desempenho e reduz o número de requisições.

Para limpar o cache manualmente, você pode usar o seguinte código PHP:

```php
WP_ESPN_API::clear_cache();
```

## Personalização de Estilos

O plugin inclui estilos CSS padrão que podem ser sobrescritos no seu tema. As principais classes CSS são:

- `.espn-scoreboard` - Container do scoreboard
- `.espn-game-card` - Card de cada jogo
- `.espn-standings` - Container da tabela de classificação
- `.espn-standings-table` - Tabela de classificação
- `.espn-upcoming` - Container de próximos jogos
- `.espn-team-schedule` - Container do calendário do time

## Aviso Importante

Este plugin utiliza a API não-oficial da ESPN. A ESPN não oferece suporte oficial e pode modificar, depreciar ou remover os endpoints a qualquer momento sem aviso prévio.

**Recomendações:**
- Use com cautela em ambientes de produção
- Implemente tratamento de erros robusto
- Não dependa deste plugin para sistemas críticos
- Verifique os Termos de Serviço da ESPN

## Suporte

Para reportar problemas ou sugerir melhorias:
- Abra uma issue no GitHub
- Visite: https://github.com/sigrist/wp-espn

## Licença

GPL2

## Créditos

Desenvolvido usando informações da API pública disponibilizadas pela comunidade em:
https://github.com/pseudo-r/Public-ESPN-API

## Changelog

### 1.0.0
- Lançamento inicial
- Suporte para NFL, NBA, MLB, NHL, Soccer
- Shortcodes para scoreboard, standings, upcoming games e team schedule
- Sistema de cache implementado
- Estilos responsivos
