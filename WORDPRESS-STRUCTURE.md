# Estruturas WordPress Recomendadas para NFL

Este guia mostra as melhores formas de organizar páginas para exibir todas as semanas da temporada NFL, playoffs e Super Bowl.

## Opção 1: Página Dinâmica com Navegação (RECOMENDADO)

Crie uma única página que muda de conteúdo baseada em query strings.

### Estrutura
- URL: `/nfl-resultados/?week=1&seasontype=2`
- Uma única página WordPress
- Navegação entre semanas via links

### Passo a Passo

#### 1. Crie a página principal

No WordPress, crie uma página chamada "NFL - Resultados da Temporada"

#### 2. Adicione este código na página:

```php
<?php
// Pega parâmetros da URL
$week = isset($_GET['week']) ? intval($_GET['week']) : 1;
$seasontype = isset($_GET['seasontype']) ? intval($_GET['seasontype']) : 2;

// Define título baseado no tipo de temporada
$season_names = array(
    1 => 'Pré-temporada',
    2 => 'Temporada Regular',
    3 => 'Playoffs',
    4 => 'Pro Bowl'
);
$season_name = $season_names[$seasontype] ?? 'Temporada Regular';

// Para playoffs, nomes das semanas são diferentes
if ($seasontype == 3) {
    $week_names = array(
        1 => 'Wild Card',
        2 => 'Divisional',
        3 => 'Conference Championships',
        4 => 'Super Bowl'
    );
    $week_title = $week_names[$week] ?? "Semana $week";
} else {
    $week_title = "Semana $week";
}
?>

<h1>NFL - <?php echo $season_name; ?></h1>
<h2><?php echo $week_title; ?></h2>

<!-- Navegação -->
<div class="nfl-navigation">
    <?php if ($seasontype == 2): // Temporada Regular ?>
        <div class="week-nav">
            <?php for ($w = 1; $w <= 18; $w++): ?>
                <a href="?week=<?php echo $w; ?>&seasontype=2"
                   class="<?php echo $w == $week ? 'active' : ''; ?>">
                    Semana <?php echo $w; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php elseif ($seasontype == 3): // Playoffs ?>
        <div class="playoff-nav">
            <a href="?week=1&seasontype=3" class="<?php echo $week == 1 ? 'active' : ''; ?>">Wild Card</a>
            <a href="?week=2&seasontype=3" class="<?php echo $week == 2 ? 'active' : ''; ?>">Divisional</a>
            <a href="?week=3&seasontype=3" class="<?php echo $week == 3 ? 'active' : ''; ?>">Conference</a>
            <a href="?week=4&seasontype=3" class="<?php echo $week == 4 ? 'active' : ''; ?>">Super Bowl</a>
        </div>
    <?php endif; ?>

    <!-- Toggle entre Regular e Playoffs -->
    <div class="season-toggle">
        <a href="?week=1&seasontype=2" class="<?php echo $seasontype == 2 ? 'active' : ''; ?>">Temporada Regular</a>
        <a href="?week=1&seasontype=3" class="<?php echo $seasontype == 3 ? 'active' : ''; ?>">Playoffs</a>
    </div>
</div>

<!-- Shortcode com parâmetros dinâmicos -->
<?php echo do_shortcode('[espn_scoreboard sport="nfl" week="' . $week . '" seasontype="' . $seasontype . '" limit="20"]'); ?>

<!-- CSS para navegação -->
<style>
.nfl-navigation {
    margin: 30px 0;
}
.week-nav, .playoff-nav {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.week-nav a, .playoff-nav a {
    padding: 10px 15px;
    background: #f0f0f0;
    text-decoration: none;
    border-radius: 5px;
    color: #333;
    transition: all 0.2s;
}
.week-nav a:hover, .playoff-nav a:hover {
    background: #ddd;
}
.week-nav a.active, .playoff-nav a.active {
    background: #c8102e;
    color: white;
    font-weight: bold;
}
.season-toggle {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #ddd;
}
.season-toggle a {
    padding: 12px 20px;
    background: #333;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-right: 10px;
}
.season-toggle a.active {
    background: #c8102e;
}
</style>
```

### Vantagens
✅ Uma única página para manter
✅ URLs amigáveis com query strings
✅ Fácil navegação entre semanas
✅ SEO-friendly
✅ Fácil de implementar

---

## Opção 2: Páginas Individuais

Crie uma página separada para cada semana.

### Estrutura
- `/nfl/semana-1/`
- `/nfl/semana-2/`
- `/nfl/playoffs/wild-card/`
- `/nfl/playoffs/super-bowl/`

### Passo a Passo

#### 1. Crie páginas para Temporada Regular

Crie 18 páginas:
- Título: "NFL - Semana 1"
- Slug: `nfl-semana-1`
- Conteúdo: `[espn_scoreboard sport="nfl" week="1" seasontype="2" title="Semana 1"]`

Repita para semanas 2-18.

#### 2. Crie páginas para Playoffs

- **Wild Card**: `[espn_scoreboard sport="nfl" week="1" seasontype="3" title="Wild Card"]`
- **Divisional**: `[espn_scoreboard sport="nfl" week="2" seasontype="3" title="Divisional Round"]`
- **Conference**: `[espn_scoreboard sport="nfl" week="3" seasontype="3" title="Conference Championships"]`
- **Super Bowl**: `[espn_scoreboard sport="nfl" week="4" seasontype="3" title="Super Bowl"]`

#### 3. Crie menu de navegação

No WordPress, vá em Aparência > Menus e crie um menu com todas as páginas.

### Vantagens
✅ URLs mais descritivas (`/nfl/semana-1/`)
✅ Melhor para SEO individual
✅ Fácil de gerenciar via menu WordPress

### Desvantagens
❌ 22+ páginas para criar e manter
❌ Mais trabalhoso de configurar

---

## Opção 3: Template Personalizado com Shortcode Automático

Para usuários mais avançados, crie um template PHP que detecta automaticamente a semana.

### Estrutura
- Crie um template de página personalizado
- Detecta semana atual automaticamente
- Navegação automática

### Código do Template

Crie arquivo `page-nfl-temporada.php` no tema:

```php
<?php
/**
 * Template Name: NFL Temporada
 */

get_header();

// Detecta semana atual ou usa parâmetro
$current_week = isset($_GET['week']) ? intval($_GET['week']) : null;
$seasontype = isset($_GET['seasontype']) ? intval($_GET['seasontype']) : 2;

// Se não especificado, tenta detectar semana atual
if (!$current_week) {
    // Lógica para detectar semana atual baseada na data
    $current_date = date('Ymd');
    // Você pode fazer uma chamada à API para descobrir a semana atual
    $current_week = 1; // Fallback
}
?>

<div class="nfl-season-page">
    <!-- Seu código de navegação aqui -->

    <?php echo do_shortcode('[espn_scoreboard sport="nfl" week="' . $current_week . '" seasontype="' . $seasontype . '"]'); ?>
</div>

<?php get_footer(); ?>
```

---

## Opção 4: Plugin de Navegação Automática

Crie um shortcode personalizado que exibe navegação + resultados.

### Adicione ao functions.php do tema:

```php
function nfl_season_navigator($atts) {
    $atts = shortcode_atts(array(
        'season' => date('Y'),
        'default_week' => 1,
        'default_seasontype' => 2
    ), $atts);

    $week = isset($_GET['nfl_week']) ? intval($_GET['nfl_week']) : $atts['default_week'];
    $seasontype = isset($_GET['nfl_seasontype']) ? intval($_GET['nfl_seasontype']) : $atts['default_seasontype'];

    ob_start();
    ?>
    <div class="nfl-season-navigator">
        <!-- Navegação aqui -->
        <div class="week-selector">
            <?php for ($w = 1; $w <= 18; $w++): ?>
                <a href="<?php echo add_query_arg(array('nfl_week' => $w, 'nfl_seasontype' => 2)); ?>">
                    Semana <?php echo $w; ?>
                </a>
            <?php endfor; ?>
        </div>

        <?php echo do_shortcode('[espn_scoreboard sport="nfl" week="' . $week . '" seasontype="' . $seasontype . '"]'); ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('nfl_navigator', 'nfl_season_navigator');
```

Uso: `[nfl_navigator]`

---

## Comparação das Opções

| Opção | Facilidade | Flexibilidade | Manutenção | SEO |
|-------|-----------|--------------|-----------|-----|
| Página Dinâmica | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ |
| Páginas Individuais | ⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐ | ⭐⭐⭐⭐⭐ |
| Template Personalizado | ⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ |
| Plugin Navegação | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ |

## Recomendação Final

Para a maioria dos casos: **Opção 1 - Página Dinâmica com Navegação**

Motivos:
- ✅ Mais fácil de implementar
- ✅ Uma única página para manter
- ✅ Navegação intuitiva
- ✅ Funciona sem conhecimento técnico avançado
- ✅ Fácil de adicionar novas features

---

## Parâmetros dos Shortcodes

### Season Types (seasontype)
- `1` - Pré-temporada
- `2` - Temporada Regular
- `3` - Playoffs
- `4` - Pro Bowl / All-Star

### Weeks
- **Temporada Regular**: 1-18
- **Playoffs**:
  - 1 = Wild Card
  - 2 = Divisional Round
  - 3 = Conference Championships
  - 4 = Super Bowl

### Exemplo de uso completo

```
[espn_scoreboard sport="nfl" week="1" seasontype="2" title="NFL - Semana 1" limit="20"]
[espn_scoreboard sport="nfl" week="4" seasontype="3" title="Super Bowl" limit="5"]
[espn_standings sport="nfl" title="Classificação NFL"]
```

---

## Dicas Extras

### 1. Cache e Performance
Como os resultados são cacheados por 1 hora, considere adicionar um botão de "Atualizar resultados":

```php
<a href="?refresh_cache=1&week=<?php echo $week; ?>&seasontype=<?php echo $seasontype; ?>">
    🔄 Atualizar Resultados
</a>

<?php
if (isset($_GET['refresh_cache'])) {
    WP_ESPN_API::clear_cache();
}
?>
```

### 2. Breadcrumbs
Adicione navegação hierárquica:

```php
<div class="breadcrumbs">
    <a href="/nfl/">NFL</a> ›
    <a href="?seasontype=<?php echo $seasontype; ?>">
        <?php echo $season_name; ?>
    </a> ›
    <span><?php echo $week_title; ?></span>
</div>
```

### 3. Sidebar com Navegação Rápida
Crie um widget customizado para navegação rápida entre semanas.

### 4. Meta Description Dinâmica
Para melhor SEO, adicione meta descriptions dinâmicas:

```php
add_filter('wpseo_metadesc', function($desc) {
    if (is_page('nfl-resultados')) {
        $week = $_GET['week'] ?? 1;
        $seasontype = $_GET['seasontype'] ?? 2;
        return "Resultados da NFL - Semana $week. Veja todos os jogos, placares e estatísticas.";
    }
    return $desc;
});
```
