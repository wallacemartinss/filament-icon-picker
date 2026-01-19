# Roadmap v2 - Filament Icon Picker

Este documento descreve os recursos planejados para a versão 2.x do Filament Icon Picker.

## Visão Geral

A v2 traz suporte ao Filament v5 e implementará melhorias significativas no sistema de geração de enums, tornando-o mais flexível e portável.

---

## Recursos Planejados

### 1. Enums no App Space (Fora do Vendor)

**Problema Atual:**
- Enums são gerados em `vendor/wallacemartinss/filament-icon-picker/src/Enums/`
- São perdidos a cada `composer install` ou `composer update`
- Namespace fixo não permite autoloading quando gerado em outro diretório

**Solução Proposta:**
- Caminho padrão: `app/Enums/Icons/`
- Namespace dinâmico baseado no caminho escolhido
- Configuração via `config/filament-icon-picker.php`

**Configuração:**
```php
// config/filament-icon-picker.php
return [
    'enums' => [
        'path' => app_path('Enums/Icons'),
        'namespace' => 'App\\Enums\\Icons',
    ],
];
```

**Uso:**
```php
use App\Enums\Icons\Heroicons;

protected static string|BackedEnum|null $navigationIcon = Heroicons::OutlinedStar;
```

**Complexidade:** Média
**Quebra Compatibilidade:** Não (configurável, mantém padrão antigo como fallback)

---

### 2. Coleções Customizadas de Ícones

**Problema Atual:**
- Só é possível gerar enums para sets completos (milhares de ícones)
- Não há como criar uma coleção com apenas os ícones desejados
- Icon picker pode ficar "inundado" com ícones desnecessários

**Solução Proposta:**
- Novo comando interativo para selecionar ícones específicos
- Configuração de coleções customizadas
- Geração de enums a partir das coleções

**Configuração:**
```php
// config/filament-icon-picker.php
return [
    'collections' => [
        'favoritos' => [
            'heroicon-o-star',
            'heroicon-o-heart',
            'heroicon-o-user',
            'phosphor-whatsapp-logo',
            'fas-check',
        ],
        'redes-sociais' => [
            'fab-facebook',
            'fab-twitter',
            'fab-instagram',
            'fab-linkedin',
            'fab-youtube',
            'phosphor-whatsapp-logo',
        ],
        'navegacao' => [
            'heroicon-o-home',
            'heroicon-o-cog',
            'heroicon-o-user',
            'heroicon-o-bell',
            'heroicon-o-inbox',
        ],
    ],
];
```

**Comandos:**
```bash
# Criar coleção interativamente
php artisan filament-icon-picker:create-collection favoritos

# Gerar enum a partir de coleção
php artisan filament-icon-picker:generate-enums --collection=favoritos

# Listar coleções existentes
php artisan filament-icon-picker:collections --list
```

**Uso no IconPickerField:**
```php
IconPickerField::make('icon')
    ->collection('favoritos')  // Mostra apenas ícones da coleção
```

**Uso como Enum:**
```php
use App\Enums\Icons\Favoritos;

Action::make('star')->icon(Favoritos::Star);
```

**Complexidade:** Alta
**Quebra Compatibilidade:** Não (recurso adicional)

---

### 3. Autodiscovery de Enums

**Problema Atual:**
- Enums customizados não são reconhecidos automaticamente
- Helper `Icon` não conhece enums criados pelo usuário
- Não há integração entre enums gerados e o picker

**Solução Proposta:**
- Autodiscovery de enums em diretório configurado
- Registro automático no IconSetManager
- Integração com o helper `Icon`

**Configuração:**
```php
// config/filament-icon-picker.php
return [
    'enums' => [
        'autodiscover' => true,
        'path' => app_path('Enums/Icons'),
        'namespace' => 'App\\Enums\\Icons',
    ],
];
```

**Como Funciona:**
1. Plugin escaneia `app/Enums/Icons/` automaticamente
2. Registra todos os enums que implementam `ScalableIcon`
3. Disponibiliza no helper `Icon` e no picker

**Uso:**
```php
use Wallacemartinss\FilamentIconPicker\Enums\Icon;

// Acessa enum customizado automaticamente
Icon::from('favoritos', 'star');

// Ou diretamente
use App\Enums\Icons\Favoritos;
Favoritos::Star->value;
```

**Complexidade:** Média
**Quebra Compatibilidade:** Não (recurso adicional)

---

### 4. Suporte a Simple Icons

**Problema Atual:**
- Não há suporte nativo ao pacote Simple Icons
- Usuários precisam configurar manualmente

**Solução Proposta:**
- Adicionar `ublabs/blade-simple-icons` aos pacotes suportados
- Incluir mapeamento de prefixo para geração de enums

**Implementação:**

```php
// InstallIconsCommand.php
'simple-icons' => [
    'package' => 'ublabs/blade-simple-icons',
    'name' => 'Simple Icons - Ícones de marcas (~2,500 ícones)',
    'sets' => ['simple-icons'],
],

// GenerateIconEnumsCommand.php
$prefixes = [
    // ... existentes
    'simple-icons' => 'si-',
];
```

**Instalação:**
```bash
php artisan filament-icon-picker:install-icons
# Selecionar "Simple Icons" no menu interativo
```

**Uso:**
```php
use App\Enums\Icons\SimpleIcons;

// Ícones de marcas
SimpleIcons::Github;
SimpleIcons::Laravel;
SimpleIcons::Php;
SimpleIcons::Docker;
```

**Complexidade:** Baixa
**Quebra Compatibilidade:** Não (recurso adicional)

---

## Resumo de Implementação

| Recurso | Complexidade | Quebra? | Prioridade | Esforço Estimado |
|---------|--------------|---------|------------|------------------|
| Enums no app space | Média | Não* | Alta | 2-3 horas |
| Coleções customizadas | Alta | Não | Média | 4-6 horas |
| Autodiscovery | Média | Não | Média | 2-3 horas |
| Simple Icons | Baixa | Não | Alta | 30 minutos |

*Configurável - mantém compatibilidade com versões anteriores

---

## Notas de Compatibilidade

### O que NÃO muda:
- `IconPickerField` - continua funcionando igual
- `IconPickerColumn` - continua funcionando igual
- `IconPickerEntry` - continua funcionando igual
- Helper `Icon::heroicon()`, `Icon::material()`, etc. - continuam funcionando
- API endpoints - continuam os mesmos

### O que muda (opcional):
- Local padrão dos enums gerados (configurável)
- Namespace dos enums (configurável)
- Novos recursos disponíveis (não obrigatórios)

---

## Migração v1 para v2

### Passo 1: Atualizar pacote
```bash
composer require wallacemartinss/filament-icon-picker:^2.0
```

### Passo 2: Publicar nova config (opcional)
```bash
php artisan vendor:publish --tag="filament-icon-picker-config" --force
```

### Passo 3: Regenerar enums no novo local (opcional)
```bash
php artisan filament-icon-picker:generate-enums --all
```

### Passo 4: Atualizar imports (se mudou namespace)
```php
// Antes (v1)
use Wallacemartinss\FilamentIconPicker\Enums\Heroicons;

// Depois (v2 - se configurou app space)
use App\Enums\Icons\Heroicons;
```

---

## Referências

- [Issue Original](https://github.com/wallacemartinss/filament-icon-picker/issues) - Sugestões da comunidade
- [Blade Icons](https://blade-ui-kit.com/blade-icons) - Pacotes de ícones suportados
- [Simple Icons](https://simpleicons.org/) - Ícones de marcas
- [Filament v5](https://filamentphp.com/docs) - Documentação do Filament
