# Guia Rápido de Uso - Season Navigator

## A Forma Mais Fácil de Usar o Plugin

Se você quer criar uma página com TODAS as semanas da NFL (temporada regular + playoffs) da forma mais simples possível, use o **Season Navigator**.

## Passo a Passo Completo

### 1. No painel do WordPress

1. Vá em **Páginas → Adicionar Nova**
2. Dê um título qualquer, por exemplo: **"NFL 2024"**

### 2. No editor de página

Cole APENAS este shortcode no conteúdo:

```
[espn_season_navigator sport="nfl"]
```

### 3. Publique

Clique em **Publicar**.

## Pronto! Você terá:

✅ **Detecção automática da semana atual** - Sempre começa na semana em andamento
✅ **18 semanas da temporada regular** com navegação automática
✅ **4 rodadas de playoffs** (Wild Card, Divisional, Conference, Super Bowl)
✅ **Botões de navegação** entre semanas
✅ **Toggle** entre Temporada Regular e Playoffs
✅ **Design profissional** com cores da NFL
✅ **Responsivo** para mobile
✅ **Tradução automática** para português

## Como Funciona

### Detecção Automática da Semana Atual

Quando alguém acessa a página pela primeira vez, o plugin:
1. Consulta a API da ESPN
2. Detecta qual é a semana atual da temporada
3. Exibe automaticamente essa semana

**Exemplo:** Se estamos na Semana 5 da NFL, a página vai carregar direto na Semana 5!

### URLs Geradas Automaticamente

Quando alguém clicar nos botões de navegação, a URL muda:

- Semana 1: `seusite.com/nfl-2024/?week=1&seasontype=2`
- Semana 18: `seusite.com/nfl-2024/?week=18&seasontype=2`
- Wild Card: `seusite.com/nfl-2024/?week=1&seasontype=3`
- Super Bowl: `seusite.com/nfl-2024/?week=4&seasontype=3`

O plugin detecta automaticamente esses parâmetros e exibe a semana correta.

## Customizações

### Título Personalizado

```
[espn_season_navigator sport="nfl" title="NFL 2024 - Temporada Completa"]
```

### Apenas Temporada Regular (Sem Playoffs)

```
[espn_season_navigator sport="nfl" show_playoffs="false"]
```

### Apenas Playoffs (Sem Temporada Regular)

```
[espn_season_navigator sport="nfl" show_regular="false"]
```

### Limitar Número de Jogos

```
[espn_season_navigator sport="nfl" limit="10"]
```

### Para Outros Esportes

```
[espn_season_navigator sport="nba" regular_weeks="82"]
[espn_season_navigator sport="mlb"]
```

## Comparação com Outras Opções

| Método | Páginas | Código | Navegação |
|--------|---------|--------|-----------|
| **Season Navigator** | 1 | Apenas shortcode | ✅ Automática |
| Páginas Separadas | 22 | Apenas shortcodes | Via menu |
| Código PHP | 1 | PHP + shortcode | ✅ Automática |

## Exemplo Completo Real

Crie uma página chamada "NFL 2024" e use:

```
[espn_season_navigator sport="nfl" title="NFL 2024 - Acompanhe Todos os Jogos"]
```

Isso vai criar automaticamente:
- Header com título "NFL 2024 - Acompanhe Todos os Jogos"
- Subtítulo com a semana atual
- Botões "Temporada Regular" e "Playoffs"
- Grid com botões de Semana 1 até 18
- Resultados dos jogos da semana selecionada

## Perguntas Frequentes

### Preciso criar 22 páginas?
**Não!** Apenas UMA página com o shortcode `[espn_season_navigator]`.

### Preciso saber PHP?
**Não!** É só copiar e colar o shortcode.

### Preciso instalar outros plugins?
**Não!** Tudo funciona direto no WordPress.

### Funciona em mobile?
**Sim!** O design é totalmente responsivo.

### Como mudar as cores?
Você pode customizar o CSS do seu tema. As principais classes são:
- `.espn-season-btn` - Botões de temporada/playoffs
- `.espn-week-btn` - Botões de semanas
- `.espn-playoff-btn` - Botões de playoffs

### Posso usar para outros esportes?
**Sim!** Funciona para NFL, NBA, MLB, NHL, Soccer, etc.

## Suporte

Se tiver dúvidas, consulte:
- **README.md** - Documentação completa
- **EXAMPLES.md** - Mais exemplos
- **WORDPRESS-STRUCTURE.md** - Opções avançadas
