# Validador de CPF e CNPJ 

[![Build Status](https://travis-ci.org/linvix-sistemas/cpf-cnpj-validator-php.svg?branch=main)](https://travis-ci.org/linvix-sistemas/cpf-cnpj-validator-php)
[![Latest Stable Version](https://poser.pugx.org/linvix-sistemas/cpf-cnpj-validator-php/v)](https://packagist.org/packages/linvix-sistemas/cpf-cnpj-validator-php)
[![Total Downloads](https://poser.pugx.org/linvix-sistemas/cpf-cnpj-validator-php/downloads)](https://packagist.org/packages/linvix-sistemas/cpf-cnpj-validator-php)
[![License](https://poser.pugx.org/linvix-sistemas/cpf-cnpj-validator-php/license)](https://packagist.org/packages/linvix-sistemas/cpf-cnpj-validator-php)

Classe em PHP para validação de CPF e CNPJ.


## Instalação
Via [Composer](http://getcomposer.org)
```bash
composer require linvix-sistemas/cpf-cnpj-validator-php
```


## Como utilizar

Exemplo de uso para validação e formatação de CPF:
```php
// Não importa se já vem formatado ou não
$document = new \LinvixSistemas\ValidadorCpfCnpj\CPF('123.456.789.00');

// Verifica se é um número válido de CPF
// Retorna true/false
$document->isValid();

// Retorna o número de CPF formatado (###.###.###-##)
// ou false caso não seja um número válido
$document->format();

// Retorna o número sem formatação alguma
$document->getValue();
```


Exemplo de uso para validação e formatação de CNPJ:
```php
// Aceita tanto o formato numérico clássico quanto o novo formato alfanumérico
// da Receita Federal (letras A-Z nos 12 primeiros caracteres, dígitos verificadores
// sempre numéricos). Máscara opcional: ##.###.###/####-##
$document = new \LinvixSistemas\ValidadorCpfCnpj\CNPJ('12.345.678/0001-90');
$document = new \LinvixSistemas\ValidadorCpfCnpj\CNPJ('AB.3DE.6GH/0001-94'); // alfanumérico

// Verifica se é um número válido de CNPJ
// Retorna true/false
$document->isValid();

// Retorna o número de CNPJ formatado (##.###.###/####-##)
// ou false caso não seja um número válido
$document->format();

// Retorna o número sem formatação alguma
$document->getValue();
```

### Formato alfanumérico de CNPJ (novo padrão Receita Federal)

A partir do novo padrão, os 12 primeiros caracteres do CNPJ podem conter letras
maiúsculas (A–Z) e dígitos (0–9). Os 2 últimos caracteres (dígitos verificadores)
continuam sendo exclusivamente numéricos. A máscara permanece a mesma:
`##.###.###/####-##`.

| Segmento       | Posições | Conteúdo          |
|----------------|----------|-------------------|
| CNPJ raiz      | 1–8      | A–Z e 0–9         |
| Filial         | 9–12     | A–Z e 0–9         |
| Dígito verif.  | 13–14    | 0–9               |

```php
// Exemplos de CNPJs alfanuméricos
$document = new \LinvixSistemas\ValidadorCpfCnpj\CNPJ('AB3DE6GH000194');
$document->isValid(); // true
$document->format();  // "AB.3DE.6GH/0001-94"
```


Exemplo de uso para validação e formatação de CNPJ ou CPF, já reconhecendo o tipo de documento baseado na quantidade de caracteres:
```php
// Não importa se é CPF ou CNPJ e se já vem formatado (aceita alfanumérico para CNPJ)
$document = new \LinvixSistemas\ValidadorCpfCnpj\Documento('...');

// Retorna "CPF" ou "CNPJ"
$document->getType();

// Verifica se é um número válido de CNPJ ou CPF
// Retorna true/false
$document->isValid();

// Retorna o número formatado de acordo com o tipo de documento informado
// ou false caso não seja um número válido
$document->format();

// Retorna o número sem formatação alguma
$document->getValue();
```

Simples assim!

## Contribuição

- Qualquer contribuição será bem vinda através de Pull Request;
