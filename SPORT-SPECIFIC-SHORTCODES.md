# Shortcodes Específicos por Esporte

Este plugin oferece shortcodes específicos para cada esporte, facilitando o uso e fornecendo defaults otimizados.

## Por que usar shortcodes específicos?

✅ **Mais simples** - Não precisa especificar `sport="nfl"` toda vez
✅ **Defaults inteligentes** - Cada esporte tem configurações padrão otimizadas
✅ **Mais limpo** - Código mais legível e fácil de manter
✅ **Documentação clara** - Exemplos específicos para cada esporte

## NFL (Futebol Americano)

### `[espn_nfl_scoreboard]`

Exibe resultados de jogos da NFL.

**Uso básico:**
```
[espn_nfl_scoreboard]
```

**Com parâmetros:**
```
[espn_nfl_scoreboard week="1" seasontype="2" limit="10"]
[espn_nfl_scoreboard week="5" title="NFL - Semana 5"]
```

**Parâmetros disponíveis:**
- `week` - Número da semana (1-18 para temporada regular)
- `seasontype` - Tipo de temporada: 1=Pré, 2=Regular, 3=Playoffs
- `limit` - Número máximo de jogos (padrão: 10)
- `title` - Título da seção (padrão: "NFL - Resultados")

---

### `[espn_nfl_standings]`

Exibe classificação da NFL. Por padrão mostra **por divisão** (AFC East, NFC West, etc).

**Uso básico:**
```
[espn_nfl_standings]
```

**Por conferência:**
```
[espn_nfl_standings group_by="conference"]
```

**Por divisão (padrão):**
```
[espn_nfl_standings group_by="division"]
```

**Parâmetros disponíveis:**
- `group_by` - Agrupamento: "division" ou "conference" (padrão: "division")
- `season` - Ano da temporada (padrão: ano atual)
- `title` - Título da seção (padrão: "NFL - Classificação")

---

### `[espn_nfl_navigator]`

Navegador completo de temporada NFL com todas as semanas e playoffs.

**Uso básico:**
```
[espn_nfl_navigator]
```

**Com título customizado:**
```
[espn_nfl_navigator title="Temporada NFL 2023-2024"]
```

**Características:**
- Detecta automaticamente a semana atual
- 18 semanas de temporada regular
- 4 rounds de playoffs (Wild Card, Divisional, Conference, Super Bowl)
- Navegação por query string

**Parâmetros disponíveis:**
- `title` - Título da seção (padrão: "NFL - Temporada [ANO]")

---

### `[espn_nfl_upcoming]`

Lista os próximos jogos agendados da NFL.

**Uso básico:**
```
[espn_nfl_upcoming]
```

**Com limite customizado:**
```
[espn_nfl_upcoming limit="10" title="Próximos Jogos da NFL"]
```

**Parâmetros disponíveis:**
- `limit` - Número máximo de jogos (padrão: 5)
- `title` - Título da seção (padrão: "NFL - Próximos Jogos")

---

## NBA (Basquete)

### `[espn_nba_scoreboard]`

Exibe resultados de jogos da NBA.

**Uso básico:**
```
[espn_nba_scoreboard]
```

**Com data específica:**
```
[espn_nba_scoreboard date="20231225"]
[espn_nba_scoreboard limit="15" title="NBA - Jogos de Hoje"]
```

**Parâmetros disponíveis:**
- `date` - Data no formato YYYYMMDD (opcional)
- `limit` - Número máximo de jogos (padrão: 10)
- `title` - Título da seção (padrão: "NBA - Resultados")

---

### `[espn_nba_standings]`

Exibe classificação da NBA. Por padrão mostra **por conferência** (Eastern, Western).

**Uso básico:**
```
[espn_nba_standings]
```

**Por divisão:**
```
[espn_nba_standings group_by="division"]
```

**Parâmetros disponíveis:**
- `group_by` - Agrupamento: "conference" ou "division" (padrão: "conference")
- `season` - Ano da temporada (padrão: ano atual)
- `title` - Título da seção (padrão: "NBA - Classificação")

---

### `[espn_nba_upcoming]`

Lista os próximos jogos agendados da NBA.

**Uso básico:**
```
[espn_nba_upcoming]
```

**Com mais jogos:**
```
[espn_nba_upcoming limit="20"]
```

**Parâmetros disponíveis:**
- `limit` - Número máximo de jogos (padrão: 10)
- `title` - Título da seção (padrão: "NBA - Próximos Jogos")

---

## Comparação: Genérico vs Específico

### Shortcodes Genéricos (ainda suportados)

```
[espn_scoreboard sport="nfl" week="1" seasontype="2"]
[espn_standings sport="nfl" group_by="division"]
[espn_season_navigator sport="nfl"]
```

### Shortcodes Específicos (recomendado)

```
[espn_nfl_scoreboard week="1" seasontype="2"]
[espn_nfl_standings group_by="division"]
[espn_nfl_navigator]
```

**Vantagens:**
- ✅ Menos código
- ✅ Defaults inteligentes por esporte
- ✅ Mais fácil de ler e manter

---

## Exemplos Práticos

### Página dedicada à NFL

```
<h1>NFL - Temporada 2023-2024</h1>

<h2>Navegador de Jogos</h2>
[espn_nfl_navigator]

<h2>Classificação por Divisão</h2>
[espn_nfl_standings]

<h2>Próximos Jogos</h2>
[espn_nfl_upcoming limit="5"]
```

### Página dedicada à NBA

```
<h1>NBA - Temporada 2023-2024</h1>

<h2>Jogos de Hoje</h2>
[espn_nba_scoreboard]

<h2>Classificação - Conferências</h2>
[espn_nba_standings]

<h2>Próximos Jogos</h2>
[espn_nba_upcoming limit="10"]
```

### Página com múltiplos esportes

```
<h1>Esportes</h1>

<h2>NFL</h2>
[espn_nfl_scoreboard limit="5"]
[espn_nfl_standings group_by="conference"]

<h2>NBA</h2>
[espn_nba_scoreboard limit="5"]
[espn_nba_standings]
```

---

## Diferenças de Defaults por Esporte

| Shortcode | NFL Default | NBA Default |
|-----------|-------------|-------------|
| **Standings group_by** | `division` | `conference` |
| **Scoreboard limit** | `10` | `10` |
| **Upcoming limit** | `5` | `10` |
| **Título** | "NFL - ..." | "NBA - ..." |

---

## Futuros Esportes

Em desenvolvimento:
- `espn_mlb_*` - Baseball
- `espn_nhl_*` - Hockey
- `espn_soccer_*` - Futebol

---

## Compatibilidade

Os shortcodes genéricos continuam funcionando normalmente:
- `[espn_scoreboard sport="nfl"]`
- `[espn_standings sport="nba"]`
- etc.

Use-os se preferir ou se precisar alternar entre esportes dinamicamente.
