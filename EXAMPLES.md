# Exemplos de Uso - ESPN Sports Scores

Este arquivo contém exemplos práticos de como usar o plugin ESPN Sports Scores em suas páginas e posts do WordPress.

## Tradução Automática pt_BR

O plugin obtém dados em português de duas formas:

1. **Direto da API**: Usa o parâmetro `lang=pt` nas requisições, obtendo muitos dados já traduzidos
2. **Tradução Local**: Sistema de tradução adicional para campos não traduzidos pela API

Resultado final:
- ✅ Status: "Final" → "Final", "Live" → "Ao Vivo"
- ✅ Períodos: "1st Quarter" → "1º Quarto", "Halftime" → "Intervalo"
- ✅ Conferências: "AFC East" → "AFC Leste"
- ✅ Datas: Formato brasileiro (dd/mm/aaaa hh:mm)

Nomes de times e jogadores permanecem em inglês (padrão no Brasil).

## Uso Mais Simples - Season Navigator ⭐

**A forma MAIS FÁCIL de criar uma página completa com todas as semanas da NFL:**

### Passo 1: Crie uma nova página no WordPress
- Título: "NFL - Temporada 2024"

### Passo 2: Cole APENAS este shortcode:
```
[espn_season_navigator sport="nfl"]
```

### Passo 3: Publique!

**Pronto!** Você terá:
- ✅ **Página inicia automaticamente na semana atual**
- ✅ Navegação entre todas as 18 semanas
- ✅ Navegação entre as 4 rodadas de playoffs
- ✅ Toggle entre Temporada Regular e Playoffs
- ✅ Design profissional e responsivo
- ✅ Tudo em português

**URLs geradas automaticamente:**
- `/sua-pagina/?week=1&seasontype=2` - Semana 1
- `/sua-pagina/?week=18&seasontype=2` - Semana 18
- `/sua-pagina/?week=4&seasontype=3` - Super Bowl

### Variações:

```
// Apenas temporada regular
[espn_season_navigator sport="nfl" show_playoffs="false"]

// Apenas playoffs
[espn_season_navigator sport="nfl" show_regular="false"]

// Com título personalizado
[espn_season_navigator sport="nfl" title="NFL 2024 - Temporada Completa"]

// Para NBA (82 jogos na temporada)
[espn_season_navigator sport="nba" regular_weeks="82"]
```

---

## Exemplos Básicos

### 1. Scoreboard NFL Simples

```
[espn_scoreboard sport="nfl"]
```

### 2. Scoreboard NBA com Limite

```
[espn_scoreboard sport="nba" limit="3" title="Jogos de Hoje - NBA"]
```

### 3. Classificação NFL

```
[espn_standings sport="nfl" title="NFL Standings 2023"]
```

### 4. Próximos Jogos NBA

```
[espn_upcoming sport="nba" limit="5" title="Próximos Jogos NBA"]
```

### 5. Semana Específica da NFL

```
[espn_scoreboard sport="nfl" week="1" seasontype="2" title="NFL - Semana 1"]
```

### 6. Playoffs NFL

```
[espn_scoreboard sport="nfl" week="1" seasontype="3" title="Wild Card"]
[espn_scoreboard sport="nfl" week="2" seasontype="3" title="Divisional Round"]
[espn_scoreboard sport="nfl" week="3" seasontype="3" title="Conference Championships"]
[espn_scoreboard sport="nfl" week="4" seasontype="3" title="Super Bowl"]
```

## Exemplos Avançados

### Página de Esportes Completa

Crie uma página com múltiplos esportes:

```html
<h2>Futebol Americano (NFL)</h2>
[espn_scoreboard sport="nfl" limit="6" title="Resultados NFL"]
[espn_standings sport="nfl" title="Classificação NFL"]

<h2>Basquete (NBA)</h2>
[espn_scoreboard sport="nba" limit="6" title="Resultados NBA"]
[espn_upcoming sport="nba" limit="5" title="Próximos Jogos NBA"]
```

### Página Dedicada a um Time

Exemplo para Dallas Cowboys:

```html
<h1>Dallas Cowboys</h1>

<h2>Calendário Completo</h2>
[espn_team_schedule sport="nfl" team="dal" title="Temporada 2023"]

<h2>Classificação da Conferência</h2>
[espn_standings sport="nfl"]
```

### Widget na Sidebar

Cole este código em um widget de texto HTML:

```html
<div style="background: #f5f5f5; padding: 15px; border-radius: 8px;">
    <h3 style="margin-top: 0;">NFL Hoje</h3>
    [espn_scoreboard sport="nfl" limit="3" title=""]
</div>
```

### Jogos de Data Específica

Para exibir jogos de uma data específica (Natal 2023):

```
[espn_scoreboard sport="nba" date="20231225" title="Jogos de Natal - NBA"]
```

## Exemplos por Esporte

### NFL (Futebol Americano)

```
<!-- Resultados da semana -->
[espn_scoreboard sport="nfl" limit="10" title="NFL - Resultados da Semana"]

<!-- Classificação por conferência -->
[espn_standings sport="nfl" season="2023"]

<!-- Próximos jogos -->
[espn_upcoming sport="nfl" limit="5" title="Jogos da Próxima Semana"]
```

### NBA (Basquete)

```
<!-- Jogos de hoje -->
[espn_scoreboard sport="nba" limit="15" title="NBA - Jogos de Hoje"]

<!-- Classificação -->
[espn_standings sport="nba"]

<!-- Calendário Lakers -->
[espn_team_schedule sport="nba" team="lal" title="Los Angeles Lakers"]
```

### MLB (Beisebol)

```
<!-- Resultados -->
[espn_scoreboard sport="mlb" limit="10" title="MLB - Resultados"]

<!-- Classificação -->
[espn_standings sport="mlb" title="MLB Standings"]
```

### NHL (Hóquei)

```
<!-- Jogos -->
[espn_scoreboard sport="nhl" limit="8" title="NHL - Jogos"]

<!-- Próximos jogos -->
[espn_upcoming sport="nhl" limit="5"]
```

### Soccer (Futebol)

```
<!-- Resultados -->
[espn_scoreboard sport="soccer" limit="10" title="Resultados de Futebol"]

<!-- Próximos jogos -->
[espn_upcoming sport="soccer" limit="8" title="Próximos Jogos"]
```

### College Football

```
<!-- Top 25 -->
[espn_scoreboard sport="college-football" limit="25" title="College Football - Top 25"]

<!-- Classificação -->
[espn_standings sport="college-football"]
```

## Combinando Shortcodes

### Dashboard de Esportes

```html
<div class="sports-dashboard">
    <div class="col-left">
        <h2>NFL</h2>
        [espn_scoreboard sport="nfl" limit="4"]
        [espn_upcoming sport="nfl" limit="3"]
    </div>

    <div class="col-right">
        <h2>NBA</h2>
        [espn_scoreboard sport="nba" limit="4"]
        [espn_upcoming sport="nba" limit="3"]
    </div>
</div>
```

### Página de Time Específico com Múltiplas Seções

```html
<h1>New England Patriots</h1>

<div class="team-page">
    <!-- Calendário -->
    <section>
        <h2>Calendário 2023</h2>
        [espn_team_schedule sport="nfl" team="ne" season="2023"]
    </section>

    <!-- Classificação da Divisão -->
    <section>
        <h2>Classificação AFC East</h2>
        [espn_standings sport="nfl"]
    </section>

    <!-- Próximos jogos da NFL -->
    <section>
        <h2>Próximos Jogos da NFL</h2>
        [espn_upcoming sport="nfl" limit="5"]
    </section>
</div>
```

## Personalização com CSS

Adicione estilos personalizados no seu tema:

```css
/* Tema escuro para scoreboard */
.espn-scoreboard {
    background: #1a1a1a;
    padding: 20px;
    border-radius: 10px;
}

.espn-game-card {
    background: #2a2a2a;
    border-color: #3a3a3a;
}

.espn-team-name {
    color: #fff;
}

/* Destacar times favoritos */
.espn-team:has(.espn-team-name:contains("Cowboys")) {
    background: #003594 !important;
    color: white;
}
```

## Customização de Traduções

### Exemplo: Customizar termo "Halftime" para "Meio-tempo"

Adicione no `functions.php` do seu tema:

```php
add_filter('wp_espn_translate_period', function($translated, $original) {
    if ($original === 'Halftime') {
        return 'Meio-tempo';
    }
    return $translated;
}, 10, 2);
```

### Exemplo: Adicionar nova tradução

```php
// Adicionar tradução personalizada
add_action('init', function() {
    WP_ESPN_i18n::add_translation('game_status', 'weather_delay', 'Atraso Climático');
});
```

### Exemplo: Customizar formato de data

```php
add_filter('wp_espn_format_date', function($formatted, $original_date, $format) {
    // Usar formato personalizado: "Seg, 25/12 às 14:30"
    $timestamp = strtotime($original_date);
    return date_i18n('D, d/m \à\s H:i', $timestamp);
}, 10, 3);
```

### Exemplo: Mudar idioma da API

```php
// Usar inglês ao invés de português
add_filter('wp_espn_api_lang', function() {
    return 'en';
});

// Usar espanhol
add_filter('wp_espn_api_lang', function() {
    return 'es';
});
```

## Dicas de Uso

1. **Performance**: Use o parâmetro `limit` para controlar quantos jogos são exibidos
2. **Cache**: O plugin cacheia os dados por 1 hora automaticamente
3. **Responsividade**: Todos os componentes são responsivos por padrão
4. **Atualização**: Para jogos ao vivo, considere usar um plugin de auto-refresh da página
5. **Idioma**: Todas as traduções podem ser customizadas via filtros WordPress

## Troubleshooting

### Nenhum dado é exibido

Verifique se:
- O código do esporte está correto
- Há jogos disponíveis na data selecionada
- A API da ESPN está respondendo

### Erros de API

Se aparecer mensagem de erro:
- Aguarde alguns minutos e recarregue a página
- Limpe o cache do WordPress
- Verifique se a API da ESPN não está fora do ar

## Estrutura de Páginas para Temporada Completa

Se você quer criar páginas para todas as semanas da NFL (temporada regular + playoffs), consulte o arquivo **WORDPRESS-STRUCTURE.md** com várias opções:

1. **Página Dinâmica com Navegação** (Recomendado)
2. **Páginas Individuais**
3. **Template Personalizado**
4. **Plugin de Navegação Automática**

### Exemplo Rápido - Página Dinâmica

Crie uma página e adicione este código:

```php
<?php
$week = isset($_GET['week']) ? intval($_GET['week']) : 1;
$seasontype = isset($_GET['seasontype']) ? intval($_GET['seasontype']) : 2;
?>

<h1>NFL - Temporada Regular</h1>

<!-- Navegação -->
<div class="week-nav">
    <?php for ($w = 1; $w <= 18; $w++): ?>
        <a href="?week=<?php echo $w; ?>&seasontype=2">Semana <?php echo $w; ?></a>
    <?php endfor; ?>
</div>

<!-- Resultados -->
<?php echo do_shortcode('[espn_scoreboard sport="nfl" week="' . $week . '" seasontype="' . $seasontype . '"]'); ?>
```

## Recursos Adicionais

- **README.md** - Documentação completa do plugin
- **WORDPRESS-STRUCTURE.md** - Guia de estruturas WordPress para temporada completa
- Para mais informações sobre os códigos de times e parâmetros disponíveis, consulte o arquivo README.md principal.
