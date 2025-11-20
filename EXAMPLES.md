# Exemplos de Uso - ESPN Sports Scores

Este arquivo contém exemplos práticos de como usar o plugin ESPN Sports Scores em suas páginas e posts do WordPress.

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

## Dicas de Uso

1. **Performance**: Use o parâmetro `limit` para controlar quantos jogos são exibidos
2. **Cache**: O plugin cacheia os dados por 1 hora automaticamente
3. **Responsividade**: Todos os componentes são responsivos por padrão
4. **Atualização**: Para jogos ao vivo, considere usar um plugin de auto-refresh da página

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

## Recursos Adicionais

Para mais informações sobre os códigos de times e parâmetros disponíveis, consulte o arquivo README.md principal.
